<?php

namespace App\Rules;

use App\Models\User;
use App\Models\Package;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Contracts\Validation\Rule;

class CheckSubscriptionPlanExists implements Rule
{
    private $merchant, $check_trial;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $merchant = null, bool $check_trial = false)
    {
        $this->merchant = $merchant;
        $this->check_trial = $check_trial;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $plan = json_decode(base64_decode($value));

        if ($plan->class == ProductAttribute::class) {

            return ProductAttribute::with(['product', 'userSubscriptions'])
                ->where('id', $plan->id)
                ->whereHas('product', function ($query) {
                    $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
                })
                ->when(!empty($this->merchant), function ($query) {
                    $query->whereDoesntHave('userSubscriptions', function ($query) {
                        $query->where('user_id', $this->merchant->id)->active();
                    });
                })
                ->when($this->check_trial, function ($query) {
                    $query->where('trial_mode', $this->check_trial);
                })
                ->exists();
        }

        return Package::with(['userSubscriptions'])
            ->where('id', $plan->id)
            ->when(!empty($this->merchant), function ($query) {
                $query->whereDoesntHave('userSubscriptions', function ($query) {
                    $query->where('user_id', $this->merchant->id)->active();
                });
            })
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This plan is already subscribed by this contractor or not exists.';
    }
}
