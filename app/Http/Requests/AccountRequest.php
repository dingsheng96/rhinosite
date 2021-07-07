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
        return [
            'name' => [
                'required', 'max:255',
                Rule::unique(User::class, 'name')->ignore(Auth::id(), 'id')->whereNull('deleted_at')
            ],
            'phone' => [
                'required', new PhoneFormat
            ],
            'email' => [
                'required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::id(), 'id')->whereNull('deleted_at')
            ],
            'new_password' => ['nullable', 'confirmed', new PasswordFormat],
            'pic_name' => ['required', 'max:255'],
            'pic_phone' => ['required', new PhoneFormat],
            'pic_email' => ['required', 'email'],
            'website' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'industry_since' => ['required', 'date_format:d/m/Y'],
            'address_1' =>  ['required', 'min:3', 'max:255'],
            'address_2' =>  ['nullable'],
            'country' =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode' =>  ['required', 'digits:5'],
            'country_state' =>  [
                'required',
                Rule::exists(CountryState::class, 'id')
                    ->where('country_id', $this->get('country'))
            ],
            'city' =>  [
                'required',
                Rule::exists(City::class, 'id')
                    ->where('country_state_id', $this->get('country_state'))
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
            'industry_since'    =>  __('validation.attributes.industry_since'),
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
