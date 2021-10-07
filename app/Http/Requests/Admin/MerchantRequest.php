<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use App\Models\User;
use App\Helpers\Status;
use App\Models\Country;
use App\Models\Service;
use App\Models\UserDetail;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use App\Rules\PasswordFormat;
use App\Rules\UniqueMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class MerchantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['merchant.create', 'merchant.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              =>  ['required', 'min:3', 'max:255', new UniqueMerchant('name', $this->route('merchant'))],
            'phone'             =>  ['required', new PhoneFormat],
            'email'             =>  ['required', 'email', new UniqueMerchant('email', $this->route('merchant'))],
            'password'          =>  [Rule::requiredIf(empty($this->route('merchant'))), 'nullable', new PasswordFormat, 'confirmed'],

            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],

            'service'           =>  ['required', Rule::exists(Service::class, 'id')->whereNull('deleted_at')],
            'reg_no'            =>  ['nullable', Rule::unique(UserDetail::class, 'reg_no')->ignore($this->route('merchant')->id ?? $this->route('merchant'), 'user_id')->whereNull('deleted_at')],
            'status'            =>  ['required', Rule::in(array_keys(Status::instance()->activeStatus()))],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'whatsapp'          =>  ['nullable', new PhoneFormat],
            'business_since'    =>  ['nullable', 'date_format:Y-m-d'],
            'logo'              =>  [Rule::requiredIf(empty($this->route('merchant'))), 'nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],
            'ssm_cert'          =>  ['nullable', 'file', 'max:2000', 'mimes:pdf'],
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
        return [];
    }
}
