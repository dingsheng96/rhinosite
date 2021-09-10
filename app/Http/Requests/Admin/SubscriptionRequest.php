<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Models\Transaction;
use App\Rules\ExistMerchant;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckSubscriptionPlanExists;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::TYPE_ADMIN)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $plan = json_decode(base64_decode($this->get('plan')));

        return [

            'merchant'          =>  [Rule::requiredIf(Auth::user()->is_admin), 'nullable', new ExistMerchant()],
            'plan'              =>  ['required', new CheckSubscriptionPlanExists(User::find($this->get('merchant')), true)],
            'activated_at'      =>  ['required', 'date_format:Y-m-d'],
            'trans_no'          =>  [Rule::requiredIf((bool) !optional($plan)->trial), 'nullable', Rule::unique(Transaction::class, 'transaction_no')->whereNull('deleted_at')],
            'payment_method'    =>  [Rule::requiredIf((bool) !optional($plan)->trial), 'nullable', 'exists:' . PaymentMethod::class . ',id']
        ];
    }
}
