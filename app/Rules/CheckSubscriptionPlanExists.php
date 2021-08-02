<?php

namespace App\Rules;

use App\Models\User;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Contracts\Validation\Rule;

class CheckSubscriptionPlanExists implements Rule
{
    private $merchant;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $merchant)
    {
        $this->merchant = $merchant;
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
        $value = json_decode($value);

        if ($value->class == ProductAttribute::class) {

            return ProductAttribute::with(['product', 'userSubscriptions'])
                ->where('id', $value->id)
                ->whereHas('product', function ($query) {
                    $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
                })
                ->whereDoesntHave('userSubscriptions', function ($query) {
                    $query->where('user_id', $this->merchant->id)->active();
                })->exists();
        }

        return Package::with(['userSubscriptions'])
            ->where('id', $value->id)
            ->whereDoesntHave('userSubscriptions', function ($query) {
                $query->where('user_id', $this->merchant->id)->active();
            })->exists();
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
