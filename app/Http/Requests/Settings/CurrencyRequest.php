<?php

namespace App\Http\Requests\Settings;

use Illuminate\Validation\Rule;
use App\Models\Settings\Currency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['currency.create', 'currency.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->route('currency')) {
            return [
                'update.name' => [
                    'required',
                    Rule::unique(Currency::class, 'name')
                        ->ignore($this->route('currency'), 'id')
                        ->whereNull('deleted_at')
                ],
                'update.code' => [
                    'required',
                    Rule::unique(Currency::class, 'code')
                        ->ignore($this->route('currency'), 'id')
                        ->whereNull(('deleted_at'))
                ]
            ];
        }

        return [
            'create.name' => [
                'required',
                Rule::unique(Currency::class, 'name')
                    ->whereNull('deleted_at')
            ],
            'create.code' => [
                'required',
                Rule::unique(Currency::class, 'code')
                    ->whereNull(('deleted_at'))
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
            'create.name' => __('validation.attributes.name'),
            'create.code' => __('validation.attributes.code')
        ];
    }
}
