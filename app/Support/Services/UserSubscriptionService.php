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

            if (empty($transaction) || !$transaction->paymentMethod->system_default) {

                $this->model->terminated_at = now();
                $this->model->save();

                return $this;
            }

            $xml = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:pay="https://payment.ipay88.com.my">
                    <soap:Header/>
                    <soap:Body>
                    <pay:Termination>
                    <pay:MerchantCode>' . config('payment.merchant_code') . '</pay:MerchantCode>
                    <pay:Refno>' . $transaction->transaction_no . '</pay:Refno>
                    <pay:Signature>' . $this->generateTerminateSignature() . '</pay:Signature>
                    </pay:Termination>
                    </soap:Body>
                    </soap:Envelope>';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($this->model)
                ->withProperties($xml)
                ->log('Preparing calling Ipay88 terminate recurring api.');

            $response = Http::withBody($xml, 'text/xml')
                ->post(config('payment.terminate_recurring_payment_url'))
                ->body();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($this->model)
                ->withProperties($response)
                ->log('Return from Ipay88 terminate recurring api.');

            $response = str_replace(
                'xmlns:soap="http://www.w3.org/2003/05/soap-envelope"',
                '',
                $response
            );

            $result = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR);

            $encoded_result =   json_encode($result);
            $array_result   =   json_decode($encoded_result, true);
            $data           =   $array_result['soap:Body']['TerminationResponse']['TerminationResult'];

            if ($data['Status'] == 1) {

                $transaction_details = new TransactionDetail();
                $transaction_details->is_termination = true;
                $transaction_details->status = Transaction::STATUS_SUCCESS;
                $transaction_details->subscription_reference = $data['Refno'];
                $transaction_details->remark = json_encode($data['Errdesc']);
                $transaction->transactionDetails()->save($transaction_details);

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

    public function setSubscriptionStatus(string $status = UserSubscription::STATUS_ACTIVE)
    {
        $this->model->status = $status;
        $this->model->save();

        return $this;
    }
}
