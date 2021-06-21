<?php

namespace App\Http\Requests;

use App\Models\AdsType;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Settings\Country\City;
use App\Models\Settings\Country\Country;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Settings\Country\CountryState;

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
        return [
            'title_en'          =>  ['required'],
            'title_cn'          =>  ['required'],
            'category'          =>  ['required', 'exists:' . Category::class . ',id'],
            'thumbnail'         =>  ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2000', 'dimensions:max_height=1024,max_width=1024'],
            'files'             =>  ['required', 'array'],
            'files.*'           =>  ['image', 'mimes.jpg,jpeg,png', 'max:2000', 'dimensions:max_height=1024,max_width=1024'],
            'description'       =>  ['nullable'],
            'materials'         =>  ['nullable'],
            'services'          =>  ['nullable'],
            'address_1'         =>  ['required'],
            'address_2'         =>  ['required'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $this->get('country'))],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'ads_type'          =>  ['filled', 'exists:' . AdsType::class . ',id'],
            'boost_ads_date'    =>  ['required_with:ads_type', 'date', 'date_format:d/m/Y']
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
            'title_en',
            'title_cn',
            'category',
            'thumbnail',
            'files.*',
            'description',
            'materials',
            'services',
            'address_1',
            'address_2',
            'country',
            'postcode',
            'country_state',
            'city',
            'ads_type',
            'boost_ads_date'
        ];
    }
}
