<?php

namespace App\Support\Services;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Models\Package;
use App\Models\UserDetail;
use App\Models\Transaction;
use App\Helpers\FileManager;
use App\Models\UserAdsQuota;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use App\Notifications\VerifyEmail;
use App\Models\UserAdsQuotaHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\FreeTrialSubscription;

class MerchantService extends BaseService
{
    public $from_verification;

    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData(bool $from_verification = false)
    {
        $this->from_verification = $from_verification;

        $this->storeProfile();
        $this->storeDetails();
        $this->storeAddress();
        $this->storeImage();
        $this->storeSsmCert();

        return $this;
    }

    public function storeProfile()
    {
        $this->model->name      =  $this->request->get('name');
        $this->model->phone     =  $this->request->get('phone');
        $this->model->email     =  $this->request->get('email');
        $this->model->status    =  $this->request->get('status', User::STATUS_ACTIVE);

        if ($this->request->has('password') && !empty($this->request->get('password'))) {
            $this->model->password = Hash::make($this->request->get('password'));
        }

        if (!$this->from_verification) {
            $this->model->email_verified_at = now();
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        $this->model->syncRoles([Role::ROLE_MERCHANT]);

        return $this;
    }

    public function storeDetails()
    {
        $details = $this->model->userDetail()
            ->when($this->from_verification, function ($query) {
                $query->pendingDetails()
                    ->orWhere(function ($query) {
                        $query->rejectedDetails();
                    });
            })
            ->when(!$this->from_verification, function ($query) {
                $query->approvedDetails();
            })
            ->firstOr(function () {
                return new UserDetail();
            });

        $details->service_id        =   $this->request->get('service');
        $details->reg_no            =   $this->request->get('reg_no');
        $details->business_since    =   $this->request->get('business_since');
        $details->website           =   $this->request->get('website');
        $details->facebook          =   $this->request->get('facebook');
        $details->whatsapp          =   $this->request->get('whatsapp');
        $details->pic_name          =   $this->request->get('pic_name');
        $details->pic_phone         =   $this->request->get('pic_phone');
        $details->pic_email         =   $this->request->get('pic_email');
        $details->status            =   ($this->from_verification) ? UserDetail::STATUS_PENDING : UserDetail::STATUS_APPROVED; // if details created from verification page, set pending, else set approved

        $this->model->userDetail()->save($details);

        return $this;
    }

    public function storeImage()
    {
        if ($this->request->hasFile('logo')) {

            $file  =   $this->request->file('logo');

            $config = [
                'save_path'     =>   User::STORE_PATH,
                'type'          =>   Media::TYPE_LOGO,
                'filemime'      =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                'filename'      =>   $file->getClientOriginalName(),
                'extension'     =>   $file->getClientOriginalExtension(),
                'filesize'      =>   $file->getSize(),
            ];

            $media = $this->model->media()->logo()
                ->firstOr(function () {
                    return new Media();
                });

            return $this->storeMedia($media, $config, $file);
        }

        return $this;
    }

    public function storeSsmCert()
    {
        if ($this->request->hasFile('ssm_cert')) {

            $file = $this->request->file('ssm_cert');

            $config = [
                'save_path'     =>   User::STORE_PATH,
                'type'          =>   Media::TYPE_SSM,
                'filemime'      =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                'filename'      =>   $file->getClientOriginalName(),
                'extension'     =>   $file->getClientOriginalExtension(),
                'filesize'      =>   $file->getSize(),
            ];

            $media = $this->model->media()->ssm()
                ->firstOr(function () {
                    return new Media();
                });

            return $this->storeMedia($media, $config, $file);
        }

        return $this;
    }

    public function storeSubscription(string $plan = null, Transaction $transaction = null)
    {
        if (!empty($plan)) {

            $plan = json_decode($plan);

            // calculate next billing date and expired date
            $activation_date    =   Carbon::createFromFormat('Y-m-d', today()->toDateString());
            $valid_until        =   $activation_date->copy();
            $trial              =   false;

            if ($plan->class == Package::class) { // if plan is package

                $package        =   Package::with(['products.productCategory'])->where('id', $plan->id)->firstOrFail();
                $package_items  =   $package->products;

                foreach ($package_items as $attribute) {

                    $item_qty = $attribute->pivot->quantity;

                    while ($item_qty > 0) {

                        $item_qty--;

                        if ($attribute->productCategory->name == ProductCategory::TYPE_SUBSCRIPTION && !empty($attribute->validity_type)) {
                            switch ($attribute->validity_type) {
                                case ProductAttribute::VALIDITY_TYPE_DAY:
                                    $valid_until = $valid_until->addDays($attribute->validity);
                                    break;
                                case ProductAttribute::VALIDITY_TYPE_MONTH:
                                    $valid_until = $valid_until->addMonths($attribute->validity);
                                    break;
                                case ProductAttribute::VALIDITY_TYPE_YEAR:
                                    $valid_until = $valid_until->addYears($attribute->validity);
                                    break;
                            }
                        }
                    }

                    // check item whether is ads products from package
                    if ($attribute->productCategory->name  == ProductCategory::TYPE_ADS) {

                        $this->storeAdsQuota($attribute, $attribute->pivot->quantity);
                    }
                }

                $next_bill_date = ($package->recurring) ? $valid_until->copy()->addDay()->startOfDay() : null;
            }

            if ($plan->class == ProductAttribute::class) { // if plan is product

                $attribute = ProductAttribute::with(['product'])->where('id', $plan->id)->firstOrFail();

                if ($attribute->productCategory->name == ProductCategory::TYPE_SUBSCRIPTION && !empty($attribute->validity_type)) {

                    switch ($attribute->validity_type) {
                        case ProductAttribute::VALIDITY_TYPE_DAY:
                            $valid_until = $valid_until->addDays($attribute->validity);
                            break;
                        case ProductAttribute::VALIDITY_TYPE_MONTH:
                            $valid_until = $valid_until->addMonths($attribute->validity);
                            break;
                        case ProductAttribute::VALIDITY_TYPE_YEAR:
                            $valid_until = $valid_until->addYears($attribute->validity);
                            break;
                    }

                    $next_bill_date = ($attribute->recurring) ? $valid_until->copy()->addDay()->startOfDay() : null;

                    $trial = $attribute->trial_mode;
                }
            }

            $subscription = $this->model->userSubscriptions()->active()
                ->whereHasMorph('subscribable', $plan->class, function (Builder $query) use ($plan) {
                    $query->where('id', $plan->id);
                })->firstOr(function () {
                    return new UserSubscription();
                });

            if (!$subscription) { // if different active subscription found, deactivate it
                $this->model->userSubscriptions()
                    ->active()->first()->update([
                        'status' => UserSubscription::STATUS_INACTIVE
                    ]);
            }

            // save subscription
            $subscription->subscribable_type =  $plan->class;
            $subscription->subscribable_id   =  $plan->id;
            $subscription->status            =  UserSubscription::STATUS_ACTIVE;
            $subscription->activated_at      =  $activation_date->startOfDay();
            $subscription->next_billing_at   =  $next_bill_date;
            $subscription->transaction_id    =  empty($transaction) ? null : $transaction->id;

            $this->model->userSubscriptions()->save($subscription);

            // save subscription logs
            $subscription->userSubscriptionLogs()
                ->create([
                    'renewed_at' => $activation_date->startOfDay(),
                    'expired_at' => $valid_until->endOfDay(),
                ]);

            if ($trial) {
                $this->model->notify(new FreeTrialSubscription());
            }
        }

        return $this;
    }

    public function storeAdsQuota(ProductAttribute $item, int $quantity)
    {
        $item->load(['product']);

        if ($item->stock_type == ProductAttribute::STOCK_TYPE_INFINITE) {
            $quantity = $item->quantity * $quantity;
        } elseif ($item->stock_type == ProductAttribute::STOCK_TYPE_FINITE) {
            $item->quantity -= $quantity;
            $item->save();
        }

        $user_ads_quota = $this->model->userAdsQuotas()
            ->where('product_id', $item->product->id)
            ->firstOr(function () {
                return new UserAdsQuota();
            });

        $user_ads_quota->product_id =   $item->product->id;
        $user_ads_quota->quantity   +=  $quantity;

        $this->model->userAdsQuotas()->save($user_ads_quota);

        $initial_quantity = 0;
        if (!$user_ads_quota->wasRecentlyCreated) { // existing records

            $history = $user_ads_quota->userAdsQuotaHistories()->orderByDesc('created_at')->first();
            if ($history) {
                $initial_quantity = $history->remaining_quantity;
            }
        }

        // create new user ads quoata history
        $sourceable_type    =   Auth::user();
        $sourceable_id      =   Auth::id();
        $applicable_type    =   $user_ads_quota;
        $applicable_id      =   $user_ads_quota->id;

        $new_user_ads_quota_history = new UserAdsQuotaHistory();
        $new_user_ads_quota_history->initial_quantity   =   $initial_quantity;
        $new_user_ads_quota_history->process_quantity   =   $quantity;
        $new_user_ads_quota_history->remaining_quantity =   $user_ads_quota->quantity;
        $new_user_ads_quota_history->sourceable_type    =   get_class($sourceable_type);
        $new_user_ads_quota_history->sourceable_id      =   $sourceable_id;
        $new_user_ads_quota_history->applicable_type    =   get_class($applicable_type);
        $new_user_ads_quota_history->applicable_id      =   $applicable_id;
        $user_ads_quota->userAdsQuotaHistories()->save($new_user_ads_quota_history);

        return $this;
    }
}
