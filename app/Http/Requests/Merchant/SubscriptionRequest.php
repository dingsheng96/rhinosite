<?php

namespace App\Http\Requests\Merchant;

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
        return Auth::guard(User::TYPE_MERCHANT)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'plan' => ['required', new CheckSubscriptionPlanExists(Auth::user(), false)],
        ];
    }
}
