<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Helpers\Misc;
use App\Helpers\Status;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
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
        $enable_slot = (bool) ProductCategory::find($this->get('category'))->enable_slot;

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
            'slot_type' => [Rule::requiredIf($enable_slot), Rule::in(Misc::instance()->adsSlotType())],
            'slot'      => [Rule::requiredIf($enable_slot), 'nullable', 'integer', 'min:0']
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
        ];
    }
}
