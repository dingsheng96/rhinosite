<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $payment_url, $merchant_key, $merchant_code,
        $ref_no, $currency, $amount,
        $payment_id, $status;

    public function __construct()
    {
        // $this->payment_url      =   config('payment.payment_url');
        $this->merchant_key     =   config('payment.merchant_key');
        $this->merchant_code    =   config('payment.merchant_code');
    }

    public function redirect(Transaction $trans_no)
    {
        $this->ref_no   =   $trans_no->transaction_no;
        $this->currency =   $trans_no->currency->code;
        $this->amount   =   $trans_no->amount;

        // temp status
        $this->payment_url = route('payment.backend', ['trans_no' => $this->ref_no]);
        $this->payment_id = 5;
        $this->status = 1;

        $credentials = [
            'merchant_code'     =>  $this->merchant_code,
            'ref_no'            =>  $this->ref_no,
            'currency'          =>  $this->currency,
            'amount'            =>  $trans_no->amount_with_thousand_symbol,
            'prod_desc'         =>  $trans_no->sourceable->concat_item_name,
            'user_name'         =>  $trans_no->sourceable->user->name,
            'user_email'        =>  $trans_no->sourceable->user->email,
            'user_contact'      =>  $trans_no->sourceable->user->phone,
            'remark'            =>  '',
            'payment_id'        =>  '5',
            'lang'              =>  'UTF-8',
            'signature_type'    =>  'SHA256',
            'signature'         =>  $this->generateSignature(true),
            'response_url'      =>  route('payment.response', ['trans_no' => $this->ref_no]),
            'backend_url'       =>  route('payment.backend', ['trans_no' => $this->ref_no])
        ];

        return view('payment.index', [
            'credentials'   =>  $credentials,
            'redirect_url'  =>  $this->payment_url
        ]);
    }

    public function response(Request $request, Transaction $trans_no)
    {
        $status = $request->get('Status');

        $trans_no->status = Transaction::STATUS_FAILED;

        if ($status == 1) {

            $trans_no->status = Transaction::STATUS_SUCCESS;

            //
        }

        $trans_no->save();

        return redirect()->route('payment.status', ['trans_no' => $trans_no->ref_no, 'status' => $status]);
    }

    public function backendResponse(Request $request, Transaction $trans_no)
    {
        $this->status       =   $request->get('Status');
        $this->payment_id   =   $request->get('PaymentId');

        $this->ref_no   =   $trans_no->transaction_no;
        $this->currency =   $trans_no->currency->code;
        $this->amount   =   $trans_no->amount;

        $message = '';

        $trans_no->status = Transaction::STATUS_FAILED;

        if (!empty($this->status) && $this->status == 1) {

            if ($this->generateSignature(true) == $request->get('Signature')) {

                $trans_no->status = Transaction::STATUS_SUCCESS;
            }

            $message = 'RECEIVEOK';
        }

        if ($trans_no->isDirty()) {
            $trans_no->save();
        }

        return $message;
    }

    public function paymentStatus(Request $request, Transaction $trans_no)
    {
        return view('payment.status', ['transaction' => $trans_no, 'status' => $request->get('status')]);
    }

    private function generateSignature(bool $backend_response = false)
    {
        $signature = [
            $this->merchant_key,
            $this->merchant_code,
            $this->ref_no,
            $this->amount,
            $this->currency
        ];

        if ($backend_response) {

            $signature[] = $this->status;
            array_splice($signature, 2, 0, [$this->payment_id]);
        }

        return hash('sha256', implode('', $signature));
    }
}
