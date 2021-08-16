<?php

namespace App\Http\Requests;

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
                Rule::in(array_keys(Status::instance()->activeStatus()))
            ],
            'description' => ['nullable'],
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2000'
            ],
            'files.*' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:10000'
            ],
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
        ];
    }
}
