<?php

namespace App\Http\Requests\Ecommerce;

use App\Helpers\Status;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['product.create', 'product.update']);
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
                'required',
                'max:255',
                Rule::unique(Product::class, 'name')
                    ->ignore($this->route('product'), 'id')
                    ->whereNull('deleted_at')
            ],
            'category' => [
                'required',
                'exists:' . ProductCategory::class . ',id'
            ],
            'status' => [
                'required',
                Rule::in(array_keys(Status::instance()->productStatus()))
            ],
            'description' => [
                'required',
            ],
            'thumbnail' => [
                Rule::requiredIf(empty($this->route('product'))),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2000',
                'dimensions:max_height=1024,max_width=1024'
            ],
            'files.*' => [
                Rule::requiredIf(empty($this->route('product'))),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:10000'
            ],
            'attributes' => [
                Rule::requiredIf(empty($this->route('product'))),
                'required',
                'array'
            ],
            'attributes.*.sku' => [
                'distinct',
                Rule::unique(ProductAttribute::class, 'sku')->whereNull('deleted_at')
            ],
            'attributes.*.stock_type' => [
                'in:' . ProductAttribute::STOCK_TYPE_FINITE . ',' . ProductAttribute::STOCK_TYPE_INFINITE
            ],
            'attributes.*.quantity' => [
                'integer',
                'min:0'
            ],
            'attributes.*.validity' => [
                'nullable',
                'integer',
                'min:0'
            ],
            'attributes.*.color' => [
                'nullable',
            ]
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
            'name'                      =>  __('validation.attributes.name'),
            'type'                      =>  __('validation.attributes.type'),
            'description'               =>  __('validation.attributes.description'),
            'thumbnail'                 =>  __('validation.attributes.thumbnail'),
            'files.*'                   =>  __('validation.attributes.file'),
            'attributes.*.sku'          =>  __('validation.attributes.sku'),
            'attributes.*.stock_type'   =>  __('validation.attributes.stock_type'),
            'attributes.*.quantity'     =>  __('validation.attributes.quantity'),
            'attributes.*.validity'     =>  __('validation.attributes.validity'),
            'attributes.*.color'        =>  __('validation.attributes.color'),
        ];
    }
}
