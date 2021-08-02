<?php

namespace App\Support\Services;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\UserDetail;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\BaseService;
use App\Notifications\VerifyUserDetail;
use App\Notifications\FreeTrialSubscription;

class UserDetailService extends BaseService
{
    public function __construct()
    {
        parent::__construct(UserDetail::class);
    }

    public function verify()
    {
        $status     =   $this->request->get('status');
        $free_trial =   $this->request->get('trial');

        if ($status != UserDetail::STATUS_PENDING) {

            $this->model->validated_by  =   Auth::id();
            $this->model->status        =   $status;
            $this->model->validated_at  =   now();
            $this->model->save();

            if (!empty($free_trial) && $status == UserDetail::STATUS_APPROVED) {

                return $this->freeTrialUser();
            }

            // send email notification
            $this->model->user->notify(new VerifyUserDetail());
        }

        return $this;
    }

    public function freeTrialUser()
    {
        $active_subscription = $this->model->user
            ->userSubscriptions()->active()->first();

        if ($active_subscription) {
            $active_subscription->status =  UserSubscription::STATUS_INACTIVE;
            $active_subscription->save();
        }

        $free_trial = ProductAttribute::where('id', $this->request->get('trial'))
            ->trialMode(true)
            ->whereHas('product', function ($query) {
                $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
            })->first();

        $activation_date = Carbon::createFromFormat('Y-m-d', today()->toDateString());

        $valid_until = $activation_date->copy();

        if (!empty($free_trial->validity_type)) {

            switch ($free_trial->validity_type) {
                case ProductAttribute::VALIDITY_TYPE_DAY:
                    $valid_until = $valid_until->addDays($free_trial->validity);
                    break;
                case ProductAttribute::VALIDITY_TYPE_MONTH:
                    $valid_until = $valid_until->addMonths($free_trial->validity);
                    break;
                case ProductAttribute::VALIDITY_TYPE_YEAR:
                    $valid_until = $valid_until->addYears($free_trial->validity);
                    break;
            }
        }

        // save new plan
        $new_subscription = $this->model->user->userSubscriptions()
            ->create([
                'subscribable_type' =>  get_class($free_trial),
                'subscribable_id'   =>  $free_trial->id,
                'recurring'         =>  false,
                'activated_at'      =>  $activation_date,
                'status'            =>  UserSubscription::STATUS_ACTIVE,
            ]);

        // save subscription logs
        $new_subscription->userSubscriptionLogs()
            ->create([
                'renewed_at' => $activation_date->startOfDay(),
                'expired_at' => $valid_until->endOfDay(),
            ]);

        // send email
        $this->model->user->notify(new FreeTrialSubscription());

        return $this;
    }
}
