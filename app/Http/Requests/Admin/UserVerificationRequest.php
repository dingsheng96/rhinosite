<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Helpers\Status;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Rules\CheckSubscriptionPlanExists;
use Illuminate\Foundation\Http\FormRequest;

class UserVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check()
            && Gate::any(['merchant.create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status'    =>  ['required', Rule::in(array_keys(Status::instance()->verificationStatus()))],
            'plan'      =>  ['nullable', new CheckSubscriptionPlanExists(null, true)]
        ];
    }

    public function messages()
    {
        return [
            'plan' => __('validation.attributes.free_trial_plan')
        ];
    }
}
