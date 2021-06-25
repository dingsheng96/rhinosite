<?php

namespace App\Rules;

use App\Models\User;
use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;

class UniqueMerchant implements Rule
{
    public $ignore_id, $ignore_column, $unique_column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $unique_column, $ignore_id = null, string $ignore_column = 'id')
    {
        $this->ignore_id = $ignore_id;
        $this->ignore_column = $ignore_column;
        $this->unique_column = $unique_column;
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
        return !User::where($this->unique_column, $value)
            ->when(!empty($this->ignore_id), function ($query) {

                if (is_object($this->ignore_id)) {
                    $query->where($this->ignore_column, '!=', $this->ignore_id->{$this->ignore_column});
                } else {
                    $query->where($this->ignore_column, '!=', $this->ignore_id);
                }
            })
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_MERCHANT);
            })->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.unique');
    }
}
