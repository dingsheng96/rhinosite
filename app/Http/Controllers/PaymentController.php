<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Helpers\Misc;
use App\Models\Order;
use App\Models\Country;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\CountryState;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\Support\Facades\TransactionFacade;

class PaymentController extends Controller
{
    private $merchant_key, $merchant_code;

    public function __construct()
    {
        $this->merchant_key     =   config('payment.merchant_key');
        $this->merchant_code    =   config('payment.merchant_code');
    }

    public function redirect(Request $request)
    {
        $trans = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('id', $request->get('transaction_id'))
            ->pending()->first();

        if ($request->get('recurring')) {

            return $this->recurringPayment($request, $trans);
        }

        return $this->oneTimePayment($trans);
    }

    private function oneTimePayment($transaction)
    {
        $ref_no     =   $transaction->transaction_no;
        $currency   =   $transaction->currency->code;
        $amount     =   $transaction->amount;

        $credentials = [
            'MerchantCode'  =>  $this->merchant_code,
            'RefNo'         =>  $ref_no,
            'Currency'      =>  $currency,
            'Amount'        =>  $transaction->getFormattedAmount(true),
            'ProdDesc'      =>  $transaction->sourceable->concat_item_name,
            'UserName'      =>  $transaction->sourceable->user->name,
            'UserEmail'     =>  $transaction->sourceable->user->email,
            'UserContact'   =>  $transaction->sourceable->user->phone,
            'Remark'        =>  '',
            'PaymentId'     =>  '',
            'Lang'          =>  'UTF-8',
            'SignatureType' =>  'SHA256',
            'Signature'     =>  $this->generateRequestSignature($ref_no, $currency, $amount),
            'ResponseURL'   =>  route('payment.response'),
            'ResponseURL'   =>  route('payment.backend')
        ];

        activity()->useLog('onetime_payment')
            ->causedBy(Auth::user())
            ->withProperties(json_encode($credentials))
            ->log('Redirecting Ipay88 payment gateway');

        return view('payment.index', [
            'credentials'   =>  $credentials,
            'payment_url'   =>  config('payment.recurring_payment_url')
        ]);
    }

    public function response(Request $request)
    {
        $status =   $request->get('Status');
        $ref_no =   $request->get('RefNo');

        $transaction = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('transaction_no', $ref_no)
            ->pending()->first();

        activity()->useLog('onetime_payment')
            ->performedOn($transaction)
            ->withProperties($request->all())
            ->log('Response from Ipay88 payment gateway');

        $transaction->status = (!empty($status) && $status == 1) ? Transaction::STATUS_SUCCESS : Transaction::STATUS_FAILED;
        $transaction->save();

        return redirect()->route('payment.status', ['ref_no' => $transaction->transaction_no]);
    }

    public function backend(Request $request)
    {
        $status         =   $request->get('Status');
        $payment_id     =   $request->get('PaymentId');
        $ref_no         =   $request->get('RefNo');
        $message        =   '';

        $transaction = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('transaction_no', $ref_no)->first();

        activity()->useLog('onetime_payment')
            ->performedOn($transaction)
            ->withProperties($request->all())
            ->log('Backend Response from Ipay88 payment gateway');

        $order  =   $transaction->sourceable;

        if (!empty($status) && $status == 1) {

            $backend_signature = $this->generateBackendSignature($transaction->transaction_no, $transaction->currency->code, $transaction->amount, $status, $payment_id);

            if ($backend_signature == $request->get('Signature')) {

                $transaction->status = Transaction::STATUS_SUCCESS;

                $order = OrderFacade::setModel($transaction->sourceable)
                    ->setOrderStatus(Order::STATUS_PAID)
                    ->getModel();

                $order_items = $order->orderItems;

                foreach ($order_items as $item) {

                    if ($item->orderable_type == Package::class || ($item->orderable_type == ProductAttribute::class && $item->orderable->productCategory->name == ProductCategory::TYPE_SUBSCRIPTION)) {

                        $merchant = MerchantFacade::setModel($transaction->sourceable->user)
                            ->storeSubscription(json_encode(['id' => $item->orderable_id, 'class' => $item->orderable_type]))
                            ->getModel();
                    }

                    if ($item->orderable_type == ProductAttribute::class && $item->orderable->productCategory->name == ProductCategory::TYPE_ADS) {

                        $merchant = MerchantFacade::setModel($transaction->sourceable->user)
                            ->storeAds($item->orderable, $item->quantity)
                            ->getModel();
                    }
                }
            }

            $message = 'RECEIVEOK';
        } else {

            $transaction->status = Transaction::STATUS_FAILED;
        }

        if ($transaction->isDirty()) {
            $transaction->save();
        }

        return $message;
    }

    private function generateRequestSignature($ref_no, $currency, $amount)
    {
        $signature = $this->merchant_key . $this->merchant_code . $ref_no . $amount . $currency;

        return hash('sha256', $signature);
    }

    private function generateBackendSignature($ref_no, $currency, $amount, $status, $payment_id)
    {
        $signature = $this->merchant_key . $this->merchant_code . $payment_id . $ref_no . $amount . $currency . $status;

        return hash('sha256', $signature);
    }

    public function recurringResponse(Request $request)
    {
        $status =   $request->get('Status');
        $ref_no =   $request->get('RefNo');

        $transaction = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('transaction_no', $ref_no)
            ->pending()->first();

        activity()->useLog('recurring_payment')
            ->performedOn($transaction)
            ->withProperties($request->all())
            ->log('Response from Ipay88 payment gateway');

        $transaction->status = (!empty($status) && (int) $status == 1) ? Transaction::STATUS_SUCCESS : Transaction::STATUS_FAILED;
        $transaction->save();

        return redirect()->route('payment.status', ['ref_no' => $transaction->transaction_no]);
    }

    public function recurringBackend(Request $request)
    {
        $status         =   $request->get('Status');
        $payment_id     =   $request->get('PaymentId');
        $recurring_ref  =   $request->get('RecurringRefno');
        $ref_no         =   $request->get('RefNo');

        $transaction = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('transaction_no', $recurring_ref)->first();

        activity()->useLog('recurring_payment')
            ->performedOn($transaction)
            ->withProperties($request->all())
            ->log('Backend Response from Ipay88 payment gateway');

        $currency       =   $transaction->currency->code;
        $amount         =   $transaction->amount;
        $message        =   '';

        if (!empty($status) && $status == 1) {

            $backend_signature = $this->generateRecurringBackendSignature($ref_no, $currency, $amount, $status, $payment_id);

            if ($backend_signature == $request->get('Signature')) {

                $transaction->status = Transaction::STATUS_SUCCESS;

                $transaction = TransactionFacade::setModel($transaction)
                    ->setRequest($request)
                    ->setTransactionStatus(Transaction::STATUS_SUCCESS)
                    ->storeTransactionDetails()
                    ->getModel();

                $order = OrderFacade::setModel($transaction->sourceable)
                    ->setOrderStatus(Order::STATUS_PAID)
                    ->getModel();

                $order_item = $order->orderItems->first();

                $merchant = MerchantFacade::setModel($transaction->sourceable->user)
                    ->storeSubscription(json_encode(['id' => $order_item->orderable_id, 'class' => $order_item->orderable_type]))
                    ->getModel();
            }

            $message = 'RECEIVEOK';
        } else {

            $transaction = TransactionFacade::setModel($transaction)
                ->setTransactionStatus(Transaction::STATUS_FAILED)
                ->getModel();
        }

        return $message;
    }

    private function recurringPayment(Request $request, Transaction $transaction)
    {
        $ref_no                 =   $transaction->transaction_no;
        $currency               =   $transaction->currency->code;
        $amount                 =   $transaction->amount;

        $country                =   Country::find($request->get('country'));
        $country_state          =   CountryState::find($request->get('country_state'));
        $city                   =   City::find($request->get('city'));
        $recurring_item         =   $transaction->sourceable->orderItems()->first();

        $number_of_payments     =   9999; // Unlimited
        $frequencies            =   $this->getRecurringFrequency($recurring_item);
        $first_payment_date     =   now()->format('dmY');

        $credentials = [
            'MerchantCode'      =>  $this->merchant_code,
            'RefNo'             =>  $ref_no,
            'FirstPaymentDate'  =>  $first_payment_date,
            'NumberofPayments'  =>  $number_of_payments, // Unlimited
            'Frequency'         =>  $number_of_payments,
            'Currency'          =>  $currency,
            'Amount'            =>  $transaction->getFormattedAmount(),
            'Desc'              =>  $transaction->sourceable->concat_item_name,
            'CC_Ic'             =>  $request->get('nric'),
            'CC_Email'          =>  $request->get('email'),
            'CC_Phone'          =>  Misc::instance()->stripTagsAndAddCountryCodeToPhone($request->get('phone')),
            'P_Name'            =>  $request->get('name'),
            'P_Email'           =>  $request->get('email'),
            'P_Phone'           =>  Misc::instance()->stripTagsAndAddCountryCodeToPhone($request->get('phone')),
            'P_Addrl1'          =>  $request->get('address_1'),
            'P_Addrl2'          =>  $request->get('address_2'),
            'P_Zip'             =>  $request->get('postcode'),
            'P_City'            =>  $city->name,
            'P_State'           =>  $country_state->name,
            'P_Country'         =>  $country->name,
            'ResponseURL'       =>  route('payment.recurring.response', ['trans' => $ref_no]),
            'BackendURL'        =>  route('payment.recurring.backend', ['trans' => $ref_no]),
            'Signature'         =>  $this->generateRecurringRequestSignature([
                'number_of_payments'    =>  $number_of_payments,
                'frequency'             =>  $frequencies,
                'first_payment_date'    =>  $first_payment_date,
                'ref_no'                =>  $ref_no,
                'currency'              =>  $currency,
                'amount'                =>  $amount,
            ]),
        ];

        activity()->useLog('recurring_payment')
            ->causedBy(Auth::user())
            ->withProperties(json_encode($credentials))
            ->log('Redirecting Ipay88 payment gateway');

        return view('payment.index', [
            'credentials'   =>  $credentials,
            'payment_url'   =>  config('payment.recurring_payment_url')
        ]);
    }


    private function getRecurringFrequency($recurring_item)
    {
        $frequencies = [
            'weekly' => 1,
            'monthly' => 2,
            'quarterly' => 3,
            'half-yearly' => 4,
            'yearly' => 5
        ];

        $attribute = $recurring_item->orderable;

        if ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_DAY && $attribute->validity == 7) {

            return $frequencies['weekly'];
        }

        if ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_MONTH) {

            if ($attribute->validity == 3) {
                return $frequencies['quarterly'];
            }

            if ($attribute->validity == 6) {
                return $frequencies['half-yearly'];
            }

            return $frequencies['monthly'];
        }

        if ($attribute->validity_type == ProductAttribute::VALIDITY_TYPE_DAY && $attribute->validity == 7) {
            return $frequencies['weekly'];
        }

        return $frequencies['yearly'];
    }

    private function generateRecurringRequestSignature(array $fields)
    {
        $requirements = $this->merchant_code . $this->merchant_key . $fields['ref_no'] . $fields['first_payment_date'] .
            $fields['currency'] . $fields['amount'] . $fields['number_of_payments'] . $fields['frequency'];

        return hash('sha1', $requirements);
    }

    private function generateRecurringBackendSignature($ref_no, $currency, $amount, $status, $payment_id)
    {
        $signature = $this->merchant_key . $this->merchant_code . $payment_id . $ref_no . $amount . $currency . $status;

        return hash('sha1', $signature);
    }

    public function paymentStatus(Request $request)
    {
        $transaction = Transaction::with([
            'currency',
            'paymentMethod',
            'sourceable' => function ($query) {
                $query->with(['user', 'orderItems.orderable']);
            },
        ])->where('transaction_no', $request->get('ref_no'))->first();

        return view('payment.status', compact('transaction'));
    }
}
