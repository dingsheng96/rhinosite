<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Helpers\Status;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\UserDetail;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use App\Rules\PasswordFormat;
use App\Rules\UniqueMerchant;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Rules\CheckSubscriptionPlanExists;
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
        return Auth::guard('web')->check()
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

            'reg_no'            =>  ['required', Rule::unique(UserDetail::class, 'reg_no')->ignore($this->route('merchant')->id ?? $this->route('merchant'), 'user_id')->whereNull('deleted_at')],
            'status'            =>  ['required', Rule::in(array_keys(Status::instance()->activeStatus()))],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'whatsapp'          =>  ['nullable', new PhoneFormat],
            'business_since'    =>  ['required', 'date_format:Y-m-d'],
            'logo'              =>  [Rule::requiredIf(empty($this->route('merchant'))), 'nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],
            'ssm_cert'          =>  [Rule::requiredIf(empty($this->route('merchant'))), 'nullable', 'file', 'max:2000', 'mimes:pdf'],
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
            'category'          =>  __('validation.attributes.category'),
            'business_since'    =>  __('validation.attributes.business_since'),
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
            'reg_no'            =>  __('validation.attributes.reg_no'),
        ];
    }
}
