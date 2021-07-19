<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use App\Rules\UniqueMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class VerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard()->check()
            && Auth::user()->is_merchant;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_since'    =>  ['required', 'date_format:Y-m-d'],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'country_state'     =>  [
                'required',
                Rule::exists(CountryState::class, 'id')
                    ->where('country_id', $this->get('country'))
            ],
            'city'          =>  [
                'required',
                Rule::exists(City::class, 'id')
                    ->where('country_state_id', $this->get('country_state'))
            ],
            'ssm_cert' => [
                'required',
                'file',
                'max:2000',
                'mimes:pdf'
            ],
            'reg_no'    =>  ['required'],
            'pic_name'  =>  ['required'],
            'pic_phone' =>  ['required', new PhoneFormat],
            'pic_email' =>  ['required', 'email'],
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

            'website'           =>  __('validation.attributes.website'),
            'facebook'          =>  __('validation.attributes.facebook'),
            'category'          =>  __('validation.attributes.category'),
            'business_since'    =>  __('validation.attributes.year_of_experience'),
            'pic_name'          =>  __('validation.attributes.pic_name'),
            'pic_email'         =>  __('validation.attributes.pic_email'),
            'pic_phone'         =>  __('validation.attributes.pic_phone'),
            'address_1'         =>  __('validation.attributes.address_1'),
            'address_2'         =>  __('validation.attributes.address_2'),
            'country'           =>  __('validation.attributes.country'),
            'postcode'          =>  __('validation.attributes.postcode'),
            'country_state'     =>  __('validation.attributes.country_state'),
            'city'              =>  __('validation.attributes.city'),
            'ssm_cert'          =>  __('validation.attributes.ssm_cert'),
            'reg_no'            =>  __('validation.attributes.reg_no')
        ];
    }
}
