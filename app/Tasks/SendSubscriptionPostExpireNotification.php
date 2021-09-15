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

        $expired_subscriptions = UserSubscription::with([
            'user', 'userSubscriptionLogs' => function ($query) {
                $query->orderByDesc('created_at');
            }
        ])->inactive()->whereHas('userSubscriptionLogs')
            ->whereHas('user', function ($query) {
                $query->merchant()->active()->withApprovedDetails();
            })->orderByDesc('created_at')->get()->filter(function ($value, $key) {
                return !in_array($value->user_id, UserSubscription::active()->pluck('user_id')->toArray());
            })->unique('user_id');

        $tasks_count = 0;

        try {

            $today = today()->toDateString();

            foreach ($expired_subscriptions as $subscription) {

                $expired_date = Carbon::parse($subscription->userSubscriptionLogs->first()->expired_at);

                $user = $subscription->user;

                if ($expired_date->copy()->addDays(3)->toDateString() == $today) {

                    $user->notify(new SubscriptionExpiredAfterThreeDays());
                }

                if ($expired_date->copy()->addDays(6)->toDateString() == $today) {

                    $user->notify(new SubscriptionExpiredAfterSixDays());
                }

                if ($expired_date->copy()->addDays(7)->toDateString() == $today) {

                    $user->status = User::STATUS_INACTIVE;
                    $user->save();

                    $user->notify(new DeactivateAccount());
                }

                $tasks_count++;
            }

            activity()->useLog('task_send_subscription_post_expire_notification')
                ->performedOn(new UserSubscription())
                ->withProperties(['target_id' => $expired_subscriptions->pluck('id')->toArray()])
                ->log('Successfully Processed Tasks: ' . $tasks_count);

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
