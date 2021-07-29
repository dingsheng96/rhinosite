<?php

namespace App\Http\Requests;

use App\Helpers\Misc;
use App\Helpers\Status;
use App\Models\Currency;
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
        $product = $this->route('product')->load(['productCategory']);

        $enable_slot = (bool) $product->productCategory->enable_slot;

        return [
            'sku'           => ['required', Rule::unique(ProductAttribute::class, 'sku')->ignore($this->route('attribute'), 'id')->whereNull('deleted_at')],
            'stock_type'    => ['required', Rule::in(Misc::instance()->productStockTypes())],
            'quantity'      => ['required', 'integer', 'min:0'],
            'validity'      => ['nullable', 'integer', 'min:0'],
            'validity_type' => ['nullable', Rule::in(Misc::instance()->validityType())],
            'currency'      => ['required', Rule::exists(Currency::class, 'id')],
            'unit_price'    => ['required', 'numeric'],
            'discount'      => ['required', 'numeric'],
            'slot_type'     => [Rule::requiredIf($enable_slot), Rule::in(Misc::instance()->adsSlotType())],
            'slot'          => [Rule::requiredIf($enable_slot), 'nullable', 'integer', 'min:0']
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
