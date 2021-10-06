<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use App\Models\Unit;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use App\Models\Project;
use App\Models\Currency;
use App\Models\CountryState;
use App\Rules\ExistMerchant;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
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
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['project.create', 'project.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_en'      =>  ['required', 'min:3', 'max:100', Rule::unique(Project::class, 'title')->ignore($this->route('project'), 'id')->where('user_id', $this->get('merchant'))->whereNull('deleted_at')],
            'title_cn'      =>  ['nullable', 'max:100'],
            'currency'      =>  ['required', 'exists:' . Currency::class . ',id'],
            'unit_price'    =>  ['nullable', 'numeric'],
            'unit_value'    =>  ['nullable', 'numeric'],
            'unit'          =>  ['nullable', 'exists:' . Unit::class . ',id'],
            'thumbnail'     =>  [Rule::requiredIf(empty($this->route('project'))), 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2000'],
            'files'         =>  [Rule::requiredIf(empty($this->route('project'))), 'nullable', 'array'],
            'files.*'       =>  ['image', 'mimes:jpg,jpeg,png', 'max:10000'],
            'description'   =>  ['required'],
            'materials'     =>  ['nullable'],
            'address_1'     =>  ['required', 'min:3', 'max:255'],
            'address_2'     =>  ['nullable'],
            'country'       =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'      =>  ['required', 'digits:5'],
            'country_state' =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'          =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'ads_type'      =>  ['nullable', Rule::exists(Product::class, 'id')->whereNotNull('slot_type')->whereNotNull('total_slots')->whereNull('deleted_at')],
            'date_from'     =>  [Rule::requiredIf(!empty($this->get('ads_type'))), 'nullable', 'date', 'date_format:Y-m-d', 'after:today'],
            'merchant'      =>  ['required', 'nullable', new ExistMerchant()],
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
            'date_from' => __('validation.attributes.boosting_date_from')
        ];
    }
}
