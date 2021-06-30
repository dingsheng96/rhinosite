<?php

namespace App\Http\Requests\Ecommerce;

use App\Models\Currency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProductPriceRequest extends FormRequest
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
        $action = $this->route('price') ? 'update' : 'create';

        return [
            $action . '.currency'    => ['required', 'exists:' . Currency::class . ',id'],
            $action . '.unit_price'  => ['required', 'numeric'],
            $action . '.discount'    => ['required', 'numeric'],
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
        $action = $this->route('price') ? 'update' : 'create';

        return [
            $action . '.currency'    => __('validation.attributes.currency'),
            $action . '.unit_price'  => __('validation.attributes.unit_price'),
            $action . '.discount'    => __('validation.attributes.discount'),
        ];
    }
}
