<?php

namespace App\Http\Requests;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['country.create', 'country.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!empty($this->route('country'))) {
            return [
                'name' => [
                    'required',
                    Rule::unique(Country::class, 'name')
                        ->ignore($this->route('country'), 'id')
                        ->whereNull('deleted_at')
                ],
                'currency' => [
                    'required',
                    Rule::exists(Currency::class, 'id')
                        ->whereNull('deleted_at')
                ],
                'dial' => ['required'],
            ];
        }

        return [
            'create.name' => [
                'required',
                Rule::unique(Country::class, 'name')
                    ->ignore($this->route('country'), 'id')
                    ->whereNull('deleted_at')
            ],
            'create.currency' => [
                'required',
                Rule::exists(Currency::class, 'id')
                    ->whereNull('deleted_at')
            ],
            'create.dial' => ['required'],
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
        if (!empty($this->route('country'))) {
            return [
                'name'      => __('validation.attributes.name'),
                'currency'  => __('validation.attributes.currency'),
                'dial'      => __('validation.attributes.dial')
            ];
        }

        return [
            'create.name'      => __('validation.attributes.name'),
            'create.currency'  => __('validation.attributes.currency'),
            'create.dial'      => __('validation.attributes.dial')
        ];
    }
}
