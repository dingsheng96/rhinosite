<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProductAttributeRequest extends FormRequest
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
            'sku' => [
                'required',
                Rule::unique(ProductAttribute::class, 'sku')
                    ->ignore($this->route('attribute'), 'id')
                    ->whereNull('deleted_at')
            ],
            'stock_type' => [
                'required',
                'in:' . ProductAttribute::STOCK_TYPE_FINITE . ',' . ProductAttribute::STOCK_TYPE_INFINITE
            ],
            'quantity' => [
                'required',
                'integer',
                'min:0'
            ],
            'validity' => [
                'nullable',
                'integer',
                'min:0'
            ],
            'color' => ['nullable'],
            'price' => ['nullable', 'array'],
            ''
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
