<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Status;
use App\Rules\PasswordFormat;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['admin.create', 'admin.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => ['required', Rule::unique(User::class, 'name')->ignore($this->route('admin'), 'id')->where('type', User::TYPE_ADMIN)->whereNull('deleted_at')],
            'email'     => ['required', 'email', Rule::unique(User::class, 'email')->ignore($this->route('admin'), 'id')->where('type', User::TYPE_ADMIN)->whereNull('deleted_at')],
            'status'    => ['required', Rule::in(array_keys(Status::instance()->activeStatus()))],
            'password'  => [Rule::requiredIf(empty($this->route('admin'))), 'nullable', 'confirmed', new PasswordFormat()],
            'role'      => ['required', 'exists:' . Role::class . ',id']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'        =>  __('validation.attributes.name'),
            'email'       =>  __('validation.attributes.email'),
            'status'      =>  __('validation.attributes.status'),
            'password'    =>  __('validation.attributes.password'),
        ];
    }
}
