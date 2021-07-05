<?php

namespace App\Http\Requests\Ecommerce;

use App\Rules\CheckCartItem;
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
            && Gate::any(['order.create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items'             =>  ['required', 'array'],
            'items.*.type'      =>  ['required', 'in:product,package'],
            'items.*.item_id'   =>  ['required', new CheckCartItem(data_get($this->get('items'), '*.type'))],
            'items.*.quantity'  =>  ['required', 'integer', 'min:0'],
            'items.*.action'    =>  ['required', 'in:add,minus,delete']
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
            'items.*' => __('validation.attributes.item')
        ];
    }
}
