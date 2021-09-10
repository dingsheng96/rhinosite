<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Project;
use App\Rules\ExistMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class AdsBoosterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['ads.create', 'ads.update']);
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
            'project' => [
                'required',
                Rule::exists(Project::class, 'id')->where('user_id', $merchant_id)->whereNull('deleted_at')
            ],
            'ads_type' =>  [
                'required',
                Rule::exists(Product::class, 'id')->whereNotNull('total_slots')->whereNotNull('slot_type')->whereNull('deleted_at')
            ],
            'date_from'     =>  [Rule::requiredIf(!empty($this->get('ads_type'))), 'nullable', 'date', 'date_format:Y-m-d', 'after:today'],
            'merchant'      =>  [Rule::requiredIf(Auth::user()->is_admin), 'nullable', new ExistMerchant()]
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
