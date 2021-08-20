<?php

namespace App\Tasks;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Notifications\SubscriptionPreExpire;

class SendSubscriptionPreExpireNotification
{
    public function __invoke()
    {
        DB::beginTransaction();

        $pre_expired_subscriptions = UserSubscription::with(['userSubscriptionLogs', 'user'])->active()
            ->whereHas('userSubscriptionLogs', function ($query) {
                $query->whereDate('expired_at', today()->addDays(3)->startOfDay()) // 3 days before
                    ->orderByDesc('created_at')
                    ->limit(1);
            })->whereHas('user', function ($query) {
                $query->merchant()->active()->withApprovedDetails();
            })->get();

        try {

            foreach ($pre_expired_subscriptions as $subscription) {

                $subscription->user->notify(new SubscriptionPreExpire());
            }

            activity()->useLog('task_send_subscription_pre_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $pre_expired_subscriptions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $pre_expired_subscriptions->count());

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            activity()->useLog('task_send_subscription_pre_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $pre_expired_subscriptions->pluck('id')->toArray()])
                ->log('Processed Tasks Revert. Error found: ' . $ex->getMessage());
        }
    }
}
