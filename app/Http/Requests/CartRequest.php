<?php

namespace App\Http\Requests;

use App\Rules\CheckCartItem;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['order.create', 'product.read']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('from_page') && $this->get('from_page') == 1) {

            return [
                'item'  => ['required', 'array'],
                'item.*.variant' => [
                    'nullable',
                    Rule::exists(ProductAttribute::class, 'id')->where('published', true)
                        ->where('status', ProductAttribute::STATUS_ACTIVE)
                ],
                'item.*.quantity' => [
                    'required',
                    'integer',
                    'min:0'
                ]
            ];
        }

        return [
            'type'      =>  ['required', 'in:product,package'],
            'action'    =>  ['required', 'in:add,minus'],
            'item_id'   =>  ['required', new CheckCartItem($this->get('type'))],
            'quantity'  =>  ['required', 'integer', 'min:0'],
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
            'item.*' => __('validation.attributes.item'),
            'item.*.variant' => __('validation.attributes.variant'),
            'item.*.quantity' => __('validation.attributes.quantity')
        ];
    }
}
