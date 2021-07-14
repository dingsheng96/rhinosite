<?php

namespace App\Rules;

use App\Models\Package;
use App\Models\ProductAttribute;
use Illuminate\Contracts\Validation\Rule;

class CheckCartItem implements Rule
{
    public $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $type)
    {
        $this->type = $type;
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
        if ($this->type == 'product') {

            return ProductAttribute::where('id', $value)->exists();
        } elseif ($this->type == 'package') {

            return Package::where('id', $value)->exists();
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.exists');
    }
}
