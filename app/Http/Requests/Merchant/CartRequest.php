<?php

namespace App\Http\Requests\Merchant;

use App\Models\User;
use App\Rules\CheckCartItem;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
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
        return Auth::guard(User::TYPE_MERCHANT)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('item')) {

            $rules = ['item' =>  ['required', 'array', 'min:1']];
            $other_rules = [];

            $item_variants = collect($this->get('item'))->pluck('variant')->filter(function ($value) {
                return !is_null($value);
            });

            if (count($item_variants) < 1) {

                $other_rules = ['item.*.variant' => ['required']];
            } else {
                foreach ($this->get('item') as $index => $item) {

                    if (!empty($item['variant']) && $item['quantity'] < 1) {

                        $other_rules = [
                            'item.' . $index . '.quantity'   =>  ['required', 'integer', 'min:1'],
                            'item.' . $index . '.variant'    =>  ['nullable', Rule::exists(ProductAttribute::class, 'id')->where('published', true)->where('status', ProductAttribute::STATUS_ACTIVE)],
                        ];
                    }
                }
            }

            return array_merge($rules, $other_rules);
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
        return [
            'item.*.variant.required' => 'At least 1 variant is required.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('item') as $index => $item) {

            if (!empty($item['variant']) && $item['quantity'] < 1) {

                $attributes = [
                    'item.' . $index . '.quantity'   =>  __('validation.attributes.quantity'),
                    'item.' . $index . '.variant'    =>  __('validation.attributes.variant'),
                ];
            }
        }

        return array_merge($attributes, [
            'item.*'            =>  __('validation.attributes.item'),
            'item.*.variant'    =>  __('validation.attributes.variant'),
            'item.*.quantity'   =>  __('validation.attributes.quantity')
        ]);
    }
}
