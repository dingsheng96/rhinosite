<?php

namespace App\Support\Services;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Models\Package;
use App\Models\Product;
use App\Models\UserDetail;
use App\Models\Transaction;
use App\Helpers\FileManager;
use App\Models\UserAdsQuota;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use App\Models\UserAdsQuotaHistory;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\BaseService;
use App\Notifications\FreeTierAccount;
use App\Notifications\VerifyUserDetail;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\FreeTrialSubscription;
use App\Support\Services\UserSubscriptionService;

class MerchantService extends BaseService
{
    private $old_free_tier_status = false;

    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData()
    {
        $this->storeProfile();
        $this->storeDetails();
        $this->storeAddress();
        $this->storeImage();
        $this->storeSsmCert();
        $this->sendFreeTierEmail();

        return $this;
    }

    public function storeProfile()
    {
        if ($this->model->exists) {

            $this->old_free_tier_status = $this->model->free_tier;
        }

        $this->model->name      =   $this->request->get('name');
        $this->model->phone     =   $this->request->get('phone');
        $this->model->email     =   $this->request->get('email');
        $this->model->status    =   $this->request->get('status', User::STATUS_ACTIVE);
        $this->model->password  =   $this->request->get('password');
        $this->model->free_tier =   $this->request->has('free_tier');
        $this->model->type      =   User::TYPE_MERCHANT;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }

    public function sendFreeTierEmail()
    {
        if ($this->request->has('free_tier') && $this->old_free_tier_status != $this->model->free_tier) {
            // send email for free tier
            $this->model->notify(new FreeTierAccount());
        }

        return $this;
    }

    public function storeDetails()
    {
        $details = $this->model->userDetail()
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
        $details->status            =   UserDetail::STATUS_PENDING;

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

            $plan = json_decode(base64_decode($plan));

            // calculate next billing date and expired date
            $activated_at   =   Carbon::createFromFormat('Y-m-d', (!empty($this->request) && !empty($this->request->get('activated_at'))) ? $this->request->get('activated_at') : today()->toDateString());
            $renewed_at     =   $activated_at->copy()->startOfDay();
            $valid_until    =   $activated_at->copy()->startOfDay();
            $is_trial       =   false;

            if ($plan->class == Package::class) { // if plan is a package

                $package = Package::with(['products.productCategory'])->where('id', $plan->id)->firstOrFail();

                foreach ($package->products as $attribute) {

                    $item_qty = $attribute->pivot->quantity;

                    while ($item_qty > 0) {

                        $item_qty--;

                        if (!empty($attribute->validity_type)) {

                            if ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_DAY) {

                                $valid_until = $valid_until->addDays($attribute->validity);
                            } elseif ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_MONTH) {

                                $valid_until = $valid_until->addMonths($attribute->validity);
                            } elseif ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_YEAR) {

                                $valid_until = $valid_until->addYears($attribute->validity);
                            }
                        }
                    }

                    // check item whether is ads products from package
                    if (!empty($attribute->product->slot_type) && !empty($attribute->product->total_slots)) {

                        $this->storeAdsQuota($attribute, $attribute->pivot->quantity);
                    }
                }

                $next_billing_at =  ($package->recurring) ? $valid_until->copy()->addDay()->startOfDay() : null;
                $expired_at      =   $valid_until->copy()->endOfDay();

                if ($attribute->stock_type != ProductAttribute::STOCK_TYPE_INFINITE) {

                    $attribute->stock_quantity = $attribute->stock_quantity - $attribute->quantity;
                    $attribute->save();
                }
            } elseif ($plan->class == ProductAttribute::class) { // if plan is a product

                $attribute  =   ProductAttribute::with(['product'])->where('id', $plan->id)->firstOrFail();

                $is_trial   =   $attribute->trial_mode;

                if (!empty($attribute->validity_type)) {

                    if ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_DAY) {

                        $valid_until = $valid_until->addDays($attribute->validity);
                    } elseif ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_MONTH) {

                        $valid_until = $valid_until->addMonths($attribute->validity);
                    } elseif ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_YEAR) {

                        $valid_until = $valid_until->addYears($attribute->validity);
                    }

                    $next_billing_at    =   ($attribute->recurring) ? $valid_until->copy()->addDay()->startOfDay() : null;
                    $expired_at         =   $valid_until->copy()->endOfDay();
                }

                // update stock quantity
                if ($attribute->stock_type != ProductAttribute::STOCK_TYPE_INFINITE) {

                    $attribute->stock_quantity = $attribute->stock_quantity - $attribute->quantity;
                    $attribute->save();
                }
            }

            // Subscription
            $current_subscription = $this->model->userSubscriptions()->with('subscribable')->active()->first();

            if (!empty($current_subscription->subscribable)) {

                if ($current_subscription->subscribable_id != $plan->id && $current_subscription->subscribable_type != $plan->class) { // if different active subscription found, deactivate it, then create a new record

                    $current_subscription->update([
                        'status' => UserSubscription::STATUS_INACTIVE
                    ]);

                    $subscription = new UserSubscription();
                } else {
                    $subscription = $current_subscription;
                }
            } else {
                $subscription = new UserSubscription();
            }

            // save new subscription
            $subscription->subscribable_type =  $plan->class;
            $subscription->subscribable_id   =  $plan->id;
            $subscription->status            =  UserSubscription::STATUS_ACTIVE;
            $subscription->activated_at      =  empty($subscription->activated_at) ? $activated_at->startOfDay() : $subscription->activated_at;
            $subscription->next_billing_at   =  $next_billing_at;
            $subscription->transaction_id    =  empty($transaction) ? null : $transaction->id;

            $this->model->userSubscriptions()->save($subscription);

            // save subscription logs
            $subscription->userSubscriptionLogs()->create([
                'renewed_at' => $renewed_at,
                'expired_at' => $expired_at,
            ]);

            if ($is_trial) {

                $this->model->notify(new FreeTrialSubscription());
            }
        }

        return $this;
    }

    public function storeAdsQuota(ProductAttribute $item, int $quantity)
    {
        $item->load(['product']);

        $total_quantity = $item->quantity * $quantity;

        // update stock quantity
        if ($item->stock_type != ProductAttribute::STOCK_TYPE_INFINITE) {

            $item->stock_quantity = $item->stock_quantity - $total_quantity;
            $item->save();
        }

        // store user ads quota
        $user_ads_quota = $this->model->userAdsQuotas()
            ->where('product_id', $item->product->id)->firstOr(function () {
                return new UserAdsQuota();
            });

        $user_ads_quota->product_id =   $item->product->id;
        $user_ads_quota->quantity   +=  $total_quantity;
        $this->model->userAdsQuotas()->save($user_ads_quota);

        // store user ads quoata history
        $initial_quantity = 0;

        if (!$user_ads_quota->wasRecentlyCreated) { // existing records

            $history = $user_ads_quota->userAdsQuotaHistories()->orderByDesc('created_at')->first();

            if ($history) {
                $initial_quantity = $history->remaining_quantity;
            }
        }

        // create new user ads quoata history
        $user_ads_quota->userAdsQuotaHistories()->create([
            'initial_quantity'   =>   $initial_quantity,
            'process_quantity'   =>   $total_quantity,
            'remaining_quantity' =>   $user_ads_quota->quantity,
            'sourceable_type'    =>   get_class($user_ads_quota->user),
            'sourceable_id'      =>   $user_ads_quota->user->id,
            'applicable_type'    =>   get_class($user_ads_quota),
            'applicable_id'      =>   $user_ads_quota->id
        ]);

        return $this;
    }

    public function verifiedEmail()
    {
        $this->model->email_verified_at = now();

        if ($this->model->isDirty()) {

            $this->model->save();
        }

        return $this;
    }

    public function setUserDetailStatus(string $status)
    {
        $detail = $this->model->userDetail()->first();

        $detail->status = $status;

        if ($detail->isDirty('status')) {

            $this->model->userDetail()->save($detail);
        }

        return $this;
    }

    public function verifyUserDetail()
    {
        $detail =   $this->model->userDetail()->first();
        $status =   $this->request->get('status');

        if ($status != UserDetail::STATUS_PENDING) {

            $detail->validated_by  =   Auth::id();
            $detail->status        =   $status;
            $detail->validated_at  =   now();
            $detail->save();

            $this->model->notify(new VerifyUserDetail());

            if (!empty($this->request->get('plan')) && $status == UserDetail::STATUS_APPROVED) {

                return $this->storeSubscription($this->request->get('plan'));
            }
        }

        return $this;
    }

    public function refundAdsQuota(Product $product)
    {
        $user_ads_quota = $this->model->userAdsQuotas()
            ->where('product_id', $product->id)->firstOrFail();

        $user_ads_quota->product_id =   $product->id;
        $user_ads_quota->quantity   +=  1;
        $this->model->userAdsQuotas()->save($user_ads_quota);

        $history = $user_ads_quota->userAdsQuotaHistories()->orderByDesc('created_at')->first();

        if ($history) {
            $initial_quantity = $history->remaining_quantity;
        }

        // create new user ads quoata history
        $user_ads_quota->userAdsQuotaHistories()->create([
            'initial_quantity'   =>   $initial_quantity,
            'process_quantity'   =>   1,
            'remaining_quantity' =>   $user_ads_quota->quantity,
            'sourceable_type'    =>   get_class(Auth::user()),
            'sourceable_id'      =>   Auth::id(),
            'applicable_type'    =>   get_class($user_ads_quota),
            'applicable_id'      =>   $user_ads_quota->id
        ]);

        return $this;
    }
}
