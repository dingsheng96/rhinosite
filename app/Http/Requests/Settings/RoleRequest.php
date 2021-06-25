<?php

namespace App\Http\Requests\Settings;

use Illuminate\Validation\Rule;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
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
        return Auth::guard('web')->check()
            && Gate::any(['role.create', 'role.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->route('role')) {
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

        return [
            'create.name' => [
                'required',
                Rule::unique(Role::class, 'name')
                    ->whereNull('deleted_at')
            ],
            'create.description' => [
                'nullable'
            ]
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
        if ($this->route('currency')) {
            return [
                'update.name' => __('validation.attributes.name'),
                'update.code' => __('validation.attributes.code')
            ];
        }

        return [
            'create.name'           =>  __('validation.attributes.name'),
            'create.description'    =>  __('validation.attributes.description')
        ];
    }
}
