<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Helpers\Misc;
use App\Helpers\Status;
use App\Models\Currency;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use App\Rules\CheckValidityMatch;
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
        return Auth::guard(User::TYPE_ADMIN)->check()
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
            'sku'               => ['required', Rule::unique(ProductAttribute::class, 'sku')->ignore($this->route('attribute'), 'id')->whereNull('deleted_at')],
            'stock_type'        => ['required', Rule::in(Misc::instance()->productStockTypes())],
            'stock_quantity'    => ['required', 'integer', 'min:0'],
            'quantity'          => ['required', 'integer', 'min:1'],
            'validity_type'     => ['nullable', Rule::in(Misc::instance()->validityType())],
            'validity'          => ['nullable', 'integer', 'min:0', new CheckValidityMatch($this->get('validity_type', $this->has('recurring')))],
            'currency'          => ['required', Rule::exists(Currency::class, 'id')],
            'unit_price'        => ['required', 'numeric'],
            'discount'          => ['required', 'numeric'],
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
