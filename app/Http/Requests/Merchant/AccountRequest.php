<?php

namespace App\Http\Requests\Merchant;

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
        return Auth::guard(User::TYPE_MERCHANT)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => ['required', 'max:255', Rule::unique(User::class, 'name')->ignore(Auth::id(), 'id')->where('type', User::TYPE_MERCHANT)->whereNull('deleted_at')],
            'phone'             => ['required', new PhoneFormat],
            'email'             => ['required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::id(), 'id')->whereNull('deleted_at')],
            'password'          => ['nullable', new PasswordFormat, 'confirmed'],
            'pic_name'          => ['required', 'max:255'],
            'pic_phone'         => ['required', new PhoneFormat],
            'pic_email'         => ['required', 'email'],
            'website'           => ['nullable', 'url', 'max:255'],
            'facebook'          => ['nullable', 'url', 'max:255'],
            'instagram'         => ['nullable', 'url', 'max:255'],
            'whatsapp'          => ['nullable', new PhoneFormat],
            'business_since'    => ['nullable', 'date_format:Y-m-d'],
            'address_1'         => ['required', 'min:3', 'max:255'],
            'address_2'         => ['nullable'],
            'country'           => ['required', 'exists:' . Country::class . ',id'],
            'postcode'          => ['required', 'digits:5'],
            'country_state'     => ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'              => ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'logo'              => ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'reg_no'            => ['nullable'],
            'about'             => ['nullable', 'max:20000000'],
            'about_service'     => ['nullable', 'max:20000000'],
            'about_team'        => ['nullable', 'max:20000000'],
            'about_other'       => ['nullable', 'max:20000000']
        ];
    }

    public function attributes()
    {
        return [
            'password' => __('validation.attributes.new_password')
        ];
    }
}
