<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use App\Models\User;
use App\Helpers\Status;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Rules\UniqueMember;
use App\Models\CountryState;
use App\Rules\PasswordFormat;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['member.create', 'member.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              =>  ['required', 'min:3', 'max:255', new UniqueMember('name', $this->route('member'))],
            'phone'             =>  ['required', new PhoneFormat],
            'email'             =>  ['required', 'email', new UniqueMember('email', $this->route('member'))],
            'password'          =>  [Rule::requiredIf(empty($this->route('member'))), 'nullable', new PasswordFormat, 'confirmed'],
            'logo'              =>  [Rule::requiredIf(empty($this->route('member'))), 'nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'status'            =>  ['required', Rule::in(array_keys(Status::instance()->activeStatus()))],
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
            'logo' => __('validation.attributes.profile_picture')
        ];
    }
}
