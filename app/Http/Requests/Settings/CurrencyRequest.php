<?php

namespace App\Http\Requests\Settings;

use App\Models\Currency;
use Illuminate\Validation\Rule;
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
        return [
            'name' => [
                'required',
                Rule::unique(Currency::class, 'name')
                    ->ignore($this->route('currency'), 'id')
                    ->whereNull('deleted_at')
            ],
            'code' => [
                'required',
                Rule::unique(Currency::class, 'code')
                    ->ignore($this->route('currency'), 'id')
                    ->whereNull(('deleted_at'))
            ],
            'rate.*' => [
                'required',
                'numeric'
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
            'name'  =>  __('validation.attributes.name'),
            'code'  =>  __('validation.attributes.code'),
            'rate'  =>  __('validation.attributes.rate')
        ];
    }
}
