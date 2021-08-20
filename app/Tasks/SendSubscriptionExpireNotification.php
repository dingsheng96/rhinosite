<?php

namespace App\Tasks;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Notifications\SubscriptionExpired;

class SendSubscriptionExpireNotification
{
    public function __invoke()
    {
        DB::beginTransaction();

        $expired_subscriptions = UserSubscription::with(['userSubscriptionLogs', 'user'])->active()
            ->whereHas('userSubscriptionLogs', function ($query) {
                $query->whereDate('expired_at', today()->startOfDay()) // on the day of expired
                    ->orderByDesc('created_at')
                    ->limit(1);
            })->whereHas('user', function ($query) {
                $query->merchant()->active()->withApprovedDetails();
            })->get();

        try {

            foreach ($expired_subscriptions as $subscription) {
                $subscription->user->notify(new SubscriptionExpired());
            }

            activity()->useLog('task_send_subscription_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $expired_subscriptions->count());

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            activity()->useLog('task_send_subscription_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Processed Tasks Revert. Error found: ' . $ex->getMessage());
        }
    }
}
