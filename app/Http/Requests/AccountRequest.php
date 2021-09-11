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
            'name'          => ['required', 'max:255', Rule::unique(User::class, 'name')->ignore(Auth::id(), 'id')->whereNull('deleted_at')],
            'phone'         => ['required', new PhoneFormat],
            'email'         => ['required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::id(), 'id')->whereNull('deleted_at')],
            'password'      => ['nullable', new PasswordFormat, 'confirmed'],
            'address_1'     => ['required', 'min:3', 'max:255'],
            'address_2'     => ['nullable'],
            'country'       => ['required', 'exists:' . Country::class . ',id'],
            'postcode'      => ['required', 'digits:5'],
            'country_state' => ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'          => ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'logo'          => ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
        ];
    }
}
