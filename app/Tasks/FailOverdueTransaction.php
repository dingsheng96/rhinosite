<?php

namespace App\Tasks;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;

class FailOverdueTransaction
{
    public function __invoke()
    {
        DB::beginTransaction();

        $today = today()->startOfDay();

        $expired_subscriptions = UserSubscription::with(['userSubscriptionLogs'])
            ->active()->whereHas('userSubscriptionLogs', function ($query) use ($today) {
                $query->where('expired_at', '<', $today)->orderByDesc('created_at')->limit(1);
            })->get();

        try {

            foreach ($expired_subscriptions as $subscription) {
                $subscription->status = UserSubscription::STATUS_INACTIVE;
                $subscription->save();
            }

            activity()->useLog('task_deactivate_expired_subscription')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $expired_subscriptions->count());
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            activity()->useLog('task_deactivate_expired_subscription')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Processed Tasks Revert. Error found: ' . $ex->getMessage());
        }
    }
}
