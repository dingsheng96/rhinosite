<?php

namespace App\Http\Requests;

use App\Helpers\Status;
use App\Models\Package;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckPackageRecurringProductExists;

class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['package.create', 'package.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'max:255',
                Rule::unique(Package::class, 'name')
                    ->ignore($this->route('package'), 'id')
                    ->whereNull('deleted_at')
            ],
            'status' => [
                'required', Rule::in(array_keys(Status::instance()->activeStatus())),
            ],
            'stock_type' => [
                'required',  Rule::in([Package::STOCK_TYPE_FINITE, Package::STOCK_TYPE_INFINITE])
            ],
            'quantity' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'exists:' . Currency::class . ',id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'discount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable'],
            'items' => [Rule::requiredIf(!$this->route('package')), 'nullable', 'array'],
            'items.*.product' => ['exists:' . Product::class . ',id'],
            'items.*.sku' => ['distinct', Rule::exists(ProductAttribute::class, 'id')->where('status', ProductAttribute::STATUS_ACTIVE)->whereNull('deleted_at')],
            'items.*.quantity' => ['integer', 'min:0']
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
            'status'            =>  __('validation.attributes.status'),
            'stock_type'        =>  __('validation.attributes.stock_type'),
            'quantity'          =>  __('validation.attributes.quantity'),
            'unit_price'        =>  __('validation.attributes.unit_price'),
            'discount'          =>  __('validation.attributes.discount'),
            'description'       =>  __('validation.attributes.description'),
            'items.*.product'   =>  __('validation.attributes.product'),
            'items.*.sku'       =>  __('validation.attributes.sku'),
            'items.*.quantity'  =>  __('validation.attributes.quantity'),
        ];
    }
}
