<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function paymentStatus(Request $request)
    {
        $transaction = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('transaction_no', $request->get('ref_no'))->first();

        $status = $transaction->status == Transaction::STATUS_SUCCESS ? true : false;

        $guard_type = $transaction->sourceable->user->type != User::TYPE_MEMBER ? $transaction->sourceable->user->type : 'web';

        Auth::guard($guard_type)->login($transaction->sourceable->user);

        return view('payment.status', compact('transaction', 'status'));
    }
}
