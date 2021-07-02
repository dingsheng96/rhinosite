<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Unit;
use App\Models\AdsType;
use App\Models\Country;
use App\Models\Project;
use App\Models\Currency;
use App\Models\CountryState;
use App\Rules\ExistMerchant;
use Illuminate\Validation\Rule;
use App\Rules\UniquePriceCurrency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['project.create', 'project.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $merchant_id = $this->get('merchant') ?? Auth::id();

        return [
            'title_en' =>  [
                'required', 'min:3', 'max:100',
                Rule::unique(Project::class, 'title')
                    ->ignore($this->route('project'), 'id')
                    ->where('user_id', $merchant_id)
                    ->whereNull('deleted_at')
            ],
            'title_cn' =>  ['required', 'min:3', 'max:100'],
            'currency'      =>  ['required', 'exists:' . Currency::class . ',id'],
            'unit_price'    =>  ['numeric'],
            'unit_value'    =>  ['required', 'numeric'],
            'unit'          =>  ['required', 'exists:' . Unit::class . ',id'],
            'thumbnail'     =>  [Rule::requiredIf(empty($this->route('project'))), 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2000', 'dimensions:max_height=1024,max_width=1024'],
            'files'         =>  [Rule::requiredIf(empty($this->route('project'))), 'nullable', 'array'],
            'files.*'       =>  ['image', 'mimes:jpg,jpeg,png', 'max:2000', 'dimensions:max_height=1024,max_width=1024'],
            'description'   =>  ['required'],
            'materials'     =>  ['required'],
            'services'      =>  ['required'],
            'address_1'     =>  ['required', 'min:3', 'max:255'],
            'address_2'     =>  ['nullable'],
            'country'       =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'      =>  ['required', 'digits:5'],
            'country_state' =>  [
                'required',
                Rule::exists(CountryState::class, 'id')
                    ->where('country_id', $this->get('country'))
            ],
            'city'              =>  [
                'required',
                Rule::exists(City::class, 'id')
                    ->where('country_state_id', $this->get('country_state'))
            ],
            'ads_type'          =>  ['filled', 'exists:' . AdsType::class . ',id'],
            'boost_ads_date'    =>  ['required_with:ads_type', 'nullable', 'date', 'date_format:d/m/Y'],
            'merchant'          =>  [Rule::requiredIf(Auth::user()->is_admin), 'nullable', new ExistMerchant()]
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
            'title_en'          =>  __('validation.attributes.title_en'),
            'title_cn'          =>  __('validation.attributes.title_cn'),
            'thumbnail'         =>  __('validation.attributes.thumbnail'),
            'files.*'           =>  __('validation.attributes.file'),
            'description'       =>  __('validation.attributes.description'),
            'materials'         =>  __('validation.attributes.materials'),
            'services'          =>  __('validation.attributes.services'),
            'address_1'         =>  __('validation.attributes.address_1'),
            'address_2'         =>  __('validation.attributes.address_2'),
            'country'           =>  __('validation.attributes.country'),
            'postcode'          =>  __('validation.attributes.postcode'),
            'country_state'     =>  __('validation.attributes.country_state'),
            'city'              =>  __('validation.attributes.city'),
            'ads_type'          =>  __('validation.attributes.ads_type'),
            'boost_ads_date'    =>  __('validation.attributes.boost_ads_date'),
            'unit_price'        =>  __('validation.attributes.unit_price'),
            'currency'          =>  __('validation.attributes.currency'),
            'unit_value'        =>  __('validation.attributes.unit_value'),
            'unit'              =>  __('validation.attributes.unit'),
        ];
    }
}
