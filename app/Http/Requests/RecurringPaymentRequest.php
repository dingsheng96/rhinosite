<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use App\Rules\CheckMalaysiaIc;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class RecurringPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check() && Auth::user()->is_merchant;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              =>  ['required', 'max:255'],
            'phone'             =>  ['required', new PhoneFormat],
            'email'             =>  ['required', 'email'],
            'nric'              =>  ['required', new CheckMalaysiaIc],
            'address_1'         =>  ['required', 'max:255'],
            'address_2'         =>  ['nullable', 'max:255'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
        ];
    }
}
