<?php

namespace App\Tasks;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Notifications\DeactivateAccount;
use App\Notifications\SubscriptionExpiredAfterSixDays;
use App\Notifications\SubscriptionExpiredAfterThreeDays;

class SendSubscriptionPostExpireNotification
{
    public function __invoke()
    {
        DB::beginTransaction();

        $expired_subscriptions = UserSubscription::with(['userSubscriptionLogs', 'user'])
            ->inactive()
            ->whereHas('userSubscriptionLogs', function ($query) {
                $query->orderByDesc('created_at')->limit(1);
            })->whereHas('user', function ($query) {
                $query->merchant()->active()->withApprovedDetails();
            })
            ->orderByDesc('created_at')
            ->get()
            ->filter(function ($value, $key) {
                return !in_array($value->user_id, UserSubscription::active()->pluck('user_id')->toArray());
            })
            ->unique('user_id');

        try {

            foreach ($expired_subscriptions as $subscription) {

                $expired_date = Carbon::parse($subscription->userSubscriptionLogs()->first()->expired_at);

                switch (today()->toDateString()) {
                    case $expired_date->copy()->addDays(3)->toDateString():

                        $subscription->user->notify(new SubscriptionExpiredAfterThreeDays());
                        break;

                    case $expired_date->copy()->addDays(6)->toDateString():

                        $subscription->user->notify(new SubscriptionExpiredAfterSixDays());
                        break;

                    case $expired_date->copy()->addDays(7)->toDateString():

                        $subscription->user->status = User::STATUS_INACTIVE;
                        $subscription->save();

                        $subscription->user->notify(new DeactivateAccount());
                        break;
                }
            }

            activity()->useLog('task_send_subscription_post_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $expired_subscriptions->count());

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            activity()->useLog('task_send_subscription_post_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Processed Tasks Revert. Error found: ' . $ex->getMessage());
        }
    }
}
