<?php

namespace App\Tasks;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Notifications\SubscriptionExpired;
use App\Support\Facades\UserSubscriptionFacade;

class DeactivateExpiredSubscription
{
    public function __invoke()
    {
        DB::beginTransaction();

        $expired_subscriptions = UserSubscription::with(['userSubscriptionLogs', 'subscribable', 'user'])
            ->active()
            ->whereHas('userSubscriptionLogs', function ($query) {
                $query->where('expired_at', '<', today()->startOfDay())->orderByDesc('created_at')->limit(1);
            })->get();

        try {

            foreach ($expired_subscriptions as $subscription) {

                $subscription = UserSubscriptionFacade::setModel($subscription)->setSubscriptionStatus(UserSubscription::STATUS_INACTIVE)->getModel();

                if (empty($subscription->subscribable->trial_mode) || !$subscription->subscribable->trial_mode) {
                    $subscription->user->notify(new SubscriptionExpired());
                }

                $subscription->user->free_tier = true;
                $subscription->user->save();
            }

            activity()->useLog('task_deactivate_expired_subscription')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $expired_subscriptions->count());

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            activity()->useLog('task_deactivate_expired_subscription')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Processed Tasks Revert. Error found: ' . $ex->getMessage());
        }
    }
}
