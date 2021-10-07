<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Models\Service;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
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
            'name'              =>  ['required', 'string', 'max:255'],
            'phone'             =>  ['required', 'string', new PhoneFormat],
            'email'             =>  ['required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::id(), 'id')->whereNull('deleted_at')],
            'business_since'    =>  ['nullable', 'date_format:Y-m-d'],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'whatsapp'          =>  ['nullable', new PhoneFormat],
            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'ssm_cert'          =>  ['nullable', 'file', 'max:2000', 'mimes:pdf'],
            'logo'              =>  ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2000'],
            'reg_no'            =>  ['nullable'],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],
            'service'           =>  ['required', Rule::exists(Service::class, 'id')->whereNull('deleted_at')]
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
