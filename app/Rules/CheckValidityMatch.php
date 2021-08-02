<?php

namespace App\Rules;

use App\Models\ProductAttribute;
use Illuminate\Contracts\Validation\Rule;

class CheckValidityMatch implements Rule
{
    private $validity_type, $recurring;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($validity_type, bool $recurring = false)
    {
        $this->validity_type = $validity_type;
        $this->recurring = $recurring;
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
        if ($this->recurring) {

            if ($this->validity_type == ProductAttribute::VALIDITY_TYPE_DAY && $value != 7) {
                return false;
            }

            if ($this->validity_type == ProductAttribute::VALIDITY_TYPE_MONTH && ($value != 3 && $value != 6)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
