<?php

namespace App\Http\Requests;

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
        return Auth::guard('web')->check()
            && Gate::any(['admin.create', 'admin.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->route('admin')) {
            return [
                'update.name' => [
                    'required',
                    Rule::unique(User::class, 'name')
                        ->ignore($this->route('admin'), 'id')
                        ->whereNull('deleted_at')
                ],
                'update.email' => [
                    'required',
                    'email',
                    Rule::unique(User::class, 'email')
                        ->ignore($this->route('admin'), 'id')
                        ->whereNull('deleted_at')
                ],
                'update.status' => [
                    'required',
                    Rule::in(array_keys(Status::instance()->activeStatus()))
                ],
                'update.password' => [
                    'nullable',
                    'confirmed',
                    new PasswordFormat()
                ]
            ];
        }

        return [
            'create.name' => [
                'required',
                Rule::unique(User::class, 'name')
                    ->whereNull('deleted_at')
            ],
            'create.email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email')
                    ->whereNull('deleted_at')
            ],
            'create.status' => [
                'required',
                Rule::in(array_keys(Status::instance()->activeStatus()))
            ],
            'create.password' => [
                'required',
                'confirmed',
                new PasswordFormat()
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
        return [
            '*.name'        =>  __('validation.attributes.name'),
            '*.email'       =>  __('validation.attributes.email'),
            '*.status'      =>  __('validation.attributes.status'),
            '*.password'    =>  __('validation.attributes.password'),
        ];
    }
}
