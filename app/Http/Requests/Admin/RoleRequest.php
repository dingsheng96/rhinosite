<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['role.create', 'role.update']);
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
                'required',
                Rule::unique(Role::class, 'name')
                    ->ignore($this->route('role'), 'id')
                    ->whereNull('deleted_at')
            ],
            'description' => [
                'nullable'
            ],
            'permissions' => [
                'nullable',
                'array'
            ],
            'permissions.*' => [
                Rule::exists(Permission::class, 'id')
                    ->whereNull('deleted_at')
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'permissions.*' =>  __('validation.attributes.permission'),
        ];
    }
}
