<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Rules\PasswordFormat;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'max:255',
                Rule::unique(User::class, 'name')->ignore(Auth::id(), 'id')->whereNull('deleted_at')
            ],
            'email' => [
                'required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::id(), 'id')->whereNull('deleted_at')
            ],
            'new_password' => ['nullable', new PasswordFormat, 'confirmed']
        ];
    }
}
