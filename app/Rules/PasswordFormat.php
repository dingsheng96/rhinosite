<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordFormat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // Minimum 8 characters with at least 1 uppercase letter, 1 lowercase letter and 1 number
        return preg_match_all("%^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$%", $value);

        // Minimum 8 characters with at least 1 uppercase letter, 1 number and 1 special character
        // return preg_match_all("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.regex');
    }
}
