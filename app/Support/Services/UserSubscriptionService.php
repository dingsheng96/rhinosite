<?php

namespace App\Support\Services;

use App\Models\Transaction;
use App\Models\UserSubscription;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Support\Services\BaseService;

class UserSubscriptionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(UserSubscription::class);
    }

    public function terminate()
    {
        $transaction = $this->model->transaction;

        if ($this->model->status == UserSubscription::STATUS_ACTIVE) {

            $body = [
                'MerchantCode' => config('payment.merchant_code'),
                'RefNo' => $transaction->transaction_no,
                'Signature' => $this->generateTerminateSignature()
            ];

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($this->model)
                ->withProperties(json_encode($body))
                ->log('Preparing calling Ipay88 terminate recurring api.');

            $response = Http::post(config('payment.terminate_recurring_payment_url'), $body)->body();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($this->model)
                ->withProperties($response)
                ->log('Return from Ipay88 terminate recurring api.');

            $result = simplexml_load_string($response);

            if ($result->Status == 1) {

                $transaction_details = new TransactionDetail();

                $transaction_details->is_termination = true;
                $transaction_details->status = Transaction::STATUS_SUCCESS;
                $transaction_details->subscription_reference = $result->RefNo;
                $transaction_details->remark = $result->ErrDesc;
                $transaction->save($transaction_details);

                $this->model->status = UserSubscription::STATUS_INACTIVE;
                $this->model->terminated_at = now();
                $this->model->save();
            }
        }

        return $this;
    }

    private function generateTerminateSignature()
    {
        $signature = config('payment.merchant_code') . config('payment.merchant_key') . $this->model->transaction->transaction_no;

        return base64_encode(hex2bin(sha1($signature)));
    }
}
