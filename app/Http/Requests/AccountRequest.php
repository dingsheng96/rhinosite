<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
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
        return Auth::guard('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $is_merchant    =   Auth::user()->is_merchant;
        $is_member      =   Auth::user()->is_member;

        return [
            'name' => [
                'required', 'max:255',
                Rule::unique(User::class, 'name')->ignore(Auth::id(), 'id')->whereNull('deleted_at')
            ],
            'phone' => [
                Rule::requiredIf($is_merchant || $is_member), 'nullable', new PhoneFormat
            ],
            'email' => [
                'required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::id(), 'id')->whereNull('deleted_at')
            ],
            'new_password' => ['nullable', new PasswordFormat, 'confirmed'],
            'pic_name' => [Rule::requiredIf($is_merchant), 'nullable', 'max:255'],
            'pic_phone' => [Rule::requiredIf($is_merchant), 'nullable', new PhoneFormat],
            'pic_email' => [Rule::requiredIf($is_merchant), 'nullable', 'email'],
            'website' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'business_since' => [Rule::requiredIf($is_merchant), 'nullable', 'date_format:Y-m-d'],
            'address_1' =>  [Rule::requiredIf($is_merchant || $is_member), 'nullable', 'min:3', 'max:255'],
            'address_2' =>  ['nullable'],
            'country' =>  [Rule::requiredIf($is_merchant || $is_member), 'nullable', 'exists:' . Country::class . ',id'],
            'postcode' =>  [Rule::requiredIf($is_merchant || $is_member), 'nullable', 'digits:5'],
            'country_state' =>  [
                Rule::requiredIf($is_merchant || $is_member), 'nullable',
                Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))
            ],
            'city' =>  [
                Rule::requiredIf($is_merchant || $is_member), 'nullable',
                Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))
            ],
            'logo' =>  ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png', 'dimensions:max_height=1024,max_width=1024'],
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
            'name'              =>  __('validation.attributes.name'),
            'phone'             =>  __('validation.attributes.phone'),
            'email'             =>  __('validation.attributes.email'),
            'website'           =>  __('validation.attributes.website'),
            'facebook'          =>  __('validation.attributes.facebook'),
            'business_since'    =>  __('validation.attributes.year_of_experience'),
            'logo'              =>  __('validation.attributes.logo'),
            'pic_name'          =>  __('validation.attributes.pic_name'),
            'pic_email'         =>  __('validation.attributes.pic_email'),
            'pic_phone'         =>  __('validation.attributes.pic_phone'),
            'address_1'         =>  __('validation.attributes.address_1'),
            'address_2'         =>  __('validation.attributes.address_2'),
            'country'           =>  __('validation.attributes.country'),
            'postcode'          =>  __('validation.attributes.postcode'),
            'country_state'     =>  __('validation.attributes.country_state'),
            'city'              =>  __('validation.attributes.city'),
        ];
    }
}
