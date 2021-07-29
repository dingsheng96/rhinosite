<?php

namespace App\Support\Services;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Models\Country;
use App\Models\Package;
use App\Models\Product;
use App\Models\UserDetail;
use App\Helpers\FileManager;
use App\Models\UserAdsQuota;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use App\Models\UserAdsQuotaHistory;
use App\Models\UserSubscriptionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\Services\BaseService;

class MerchantService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData(bool $from_verification = false)
    {
        $this->storeProfile();
        $this->storeDetails($from_verification);
        $this->storeAddress();
        $this->storeImage();
        $this->storeSsmCert();
        $this->storeSubscription();

        return $this;
    }

    public function storeProfile()
    {
        $this->model->name      =  $this->request->get('name');
        $this->model->phone     =  $this->request->get('phone');
        $this->model->email     =  $this->request->get('email');
        $this->model->status    =  $this->request->get('status', User::STATUS_ACTIVE);

        if ($this->request->has('password')) {
            $this->model->password = Hash::make($this->request->get('password'));
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        $this->model->syncRoles([Role::ROLE_MERCHANT]);

        return $this;
    }

    public function storeDetails(bool $from_verification = false)
    {

        $details = $this->model->userDetail()
            ->when($from_verification, function ($query) {
                $query->pendingDetails()
                    ->orWhere(function ($query) {
                        $query->rejectedDetails();
                    });
            })
            ->when(!$from_verification, function ($query) {
                $query->approvedDetails();
            })
            ->firstOr(function () {
                return new UserDetail();
            });

        $details->reg_no            =   $this->request->get('reg_no');
        $details->business_since    =   $this->request->get('business_since');
        $details->website           =   $this->request->get('website');
        $details->facebook          =   $this->request->get('facebook');
        $details->pic_name          =   $this->request->get('pic_name');
        $details->pic_phone         =   $this->request->get('pic_phone');
        $details->pic_email         =   $this->request->get('pic_email');
        $details->status            =   (!$details->exists || $from_verification) // if new details or from verification page, set pending, else set approved
            ? UserDetail::STATUS_PENDING
            : UserDetail::STATUS_APPROVED;

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

    public function storeSubscription()
    {

        if ($this->request->has('new_plan')) {

            // deactivate existing plan
            if ($active_subscription = $this->model->activeSubscription()->first()) {
                $active_subscription->status =  UserSubscription::STATUS_INACTIVE;
                $active_subscription->save();
            }

            // calculate next billing date and expired date
            $new_plan = json_decode($this->request->get('new_plan'));

            $activation_date = Carbon::createFromFormat('Y-m-d', $this->request->get('activate_at', today()->toDateString()));

            $valid_until = $activation_date;

            if ($new_plan->class == Package::class) {

                $package        =   Package::with(['products'])->where('id', $new_plan->id)->firstOrFail();
                $package_items  =   $package->products;

                foreach ($package_items as $item) {

                    $item_qty = $item->pivot->quantity;

                    while ($item_qty > 0) {

                        $item_qty--;

                        if ($item->productCategory->name == ProductCategory::TYPE_SUBSCRIPTION && !empty($item->validity_type)) {
                            switch ($item->validity_type) {
                                case ProductAttribute::VALIDITY_TYPE_DAY:
                                    $valid_until = $valid_until->addDays($item->validity);
                                    break;
                                case ProductAttribute::VALIDITY_TYPE_MONTH:
                                    $valid_until = $valid_until->addMonths($item->validity);
                                    break;
                                case ProductAttribute::VALIDITY_TYPE_YEAR:
                                    $valid_until = $valid_until->addYears($item->validity);
                                    break;
                            }
                        }
                    }

                    // check item whether is ads products from package
                    if ($item->productCategory->name  == ProductCategory::TYPE_ADS) {

                        $user_ads_quota = $this->model->userAdsQuotas()
                            ->where('product_attribute_id', $item->id)
                            ->firstOr(function () {
                                return new UserAdsQuota();
                            });

                        $user_ads_quota->product_attribute_id =   $item->id;
                        $user_ads_quota->quantity             +=  $item->pivot->quantity;

                        $this->model->userAdsQuotas()->save($user_ads_quota);

                        $initial_quantity = 0;
                        if (!$user_ads_quota->wasRecentlyCreated) { // existing records
                            $initial_quantity = $user_ads_quota->userAdsQuotaHistories()
                                ->orderByDesc('created_at')
                                ->first()
                                ->remaining_quantity;
                        }

                        // create new user ads quoata history
                        $sourceable_type = Auth::user();
                        $sourceable_id = Auth::id();

                        $applicable_type = $user_ads_quota;
                        $applicable_id  =   $user_ads_quota->id;

                        $new_user_ads_quota_history = new UserAdsQuotaHistory();
                        $new_user_ads_quota_history->initial_quantity   =   $initial_quantity;
                        $new_user_ads_quota_history->process_quantity   =   $item->pivot->quantity;
                        $new_user_ads_quota_history->remaining_quantity =   $user_ads_quota->quantity;
                        $new_user_ads_quota_history->sourceable_type    =   get_class($sourceable_type);
                        $new_user_ads_quota_history->sourceable_id      =   $sourceable_id;
                        $new_user_ads_quota_history->applicable_type    =   get_class($applicable_type);
                        $new_user_ads_quota_history->applicable_id      =   $applicable_id;
                        $user_ads_quota->userAdsQuotaHistories()->save($new_user_ads_quota_history);
                    }
                }

                $next_bill_date = $valid_until->copy()->addDay();
            }

            if ($new_plan->class == ProductAttribute::class) {

                $attribute = ProductAttribute::with(['product'])->where('id', $new_plan->id)->firstOrFail();

                if ($item->productCategory->name == ProductCategory::TYPE_SUBSCRIPTION && !empty($attribute->validity_type)) {

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

                    $next_bill_date = $valid_until->copy()->addDay();
                }
            }

            // save new plan
            $new_subscription = $this->model->userSubscriptions()
                ->create([
                    'subscribable_type' =>  $new_plan->class,
                    'subscribable_id'   =>  $new_plan->id,
                    'recurring'         =>  $this->request->has('recurring'),
                    'activated_at'      =>  $this->request->get('activate_at'),
                    'status'            =>  UserSubscription::STATUS_ACTIVE,
                    'next_billing_at'   =>  $next_bill_date,
                ]);

            // save subscription logs
            $new_subscription->userSubscriptionLogs()
                ->create([
                    'renewed_at' => $next_bill_date,
                    'expired_at' => $valid_until,
                ]);
        }

        return $this;
    }
}
