<?php

namespace App\Http\Requests;

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
        return Auth::guard('web')->check() &&
            (Auth::user()->is_merchant || Auth::user()->is_admin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'merchant'          =>  ['nullable', new ExistMerchant()],
            'plan'              =>  ['required', new CheckSubscriptionPlanExists(User::find($this->get('merchant', Auth::id())))],

            'trans_no'          =>  ['required_with:merchant', 'nullable', Rule::unique(Transaction::class, 'transaction_no')->whereNull('deleted_at')],
            'payment_method'    =>  ['required_with:merchant', 'nullable', 'exists:' . PaymentMethod::class . ',id']
        ];
    }
}
