<?php

namespace App\Http\Requests\Settings;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['category.create', 'category.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->route('category')) {
            return [
                'update.name' => [
                    'required',
                    Rule::unique(Category::class, 'name')
                        ->ignore($this->route('category'), 'id')
                        ->whereNull('deleted_at')
                ],
                'update.description' => ['nullable']
            ];
        }

        return [
            'create.name' => [
                'required',
                Rule::unique(Category::class, 'name')->whereNull('deleted_at')
            ],
            'create.description' => ['nullable']
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
        if ($this->route('category')) {
            return [
                'update.name'           => __('validation.attributes.name'),
                'update.description'    => __('validation.attributes.description')
            ];
        }

        return [
            'create.name'           => __('validation.attributes.name'),
            'create.description'    => __('validation.attributes.description')
        ];
    }
}
