<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\Country;
use App\Models\Package;
use App\Helpers\Message;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\TransactionFacade;
use App\Http\Requests\RecurringPaymentRequest;

class CheckOutController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->carts();

        $has_recurring = $cart->whereHasMorph(
            'cartable',
            [Package::class, ProductAttribute::class],
            function ($query) {
                $query->where('recurring', true);
            }
        )->exists();

        if ($has_recurring) {

            return view('checkout.recurring');
        }

        return view('checkout.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.order', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $user = User::where('id', Auth::id())->firstOrFail();

            // store order
            $order = OrderFacade::setRequest($request)
                ->setBuyer($user)
                ->createOrder()
                ->getModel();

            // create new transaction from order
            $transaction = TransactionFacade::setParent($order)
                ->setRequest($request)
                ->newTransaction()
                ->getModel();

            DB::commit();

            return redirect()->route('payment.redirect', ['trans_no' => $transaction->transaction_no, 'recurring' => false]);
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Order())
                ->withProperties($ex)
                ->log($ex->getMessage());

            return redirect()->back()->with('fail', $message);
        }
    }

    public function recurring(RecurringPaymentRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.order', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            if ($request->has('cancel')) {
                $message = __('messages.order_cancelled');
                Auth::user()->carts()->delete();
                DB::commit();
                return redirect()->route('dashboard')->withSuccess($message);
            }

            $user = User::where('id', Auth::id())->firstOrFail();

            // store order
            $order = OrderFacade::setRequest($request)
                ->setBuyer($user)
                ->createOrder()
                ->getModel();

            // create new transaction from order
            $transaction = TransactionFacade::setParent($order)
                ->setRequest($request)
                ->newTransaction()
                ->getModel();

            DB::commit();

            $country            =   Country::find($request->get('country'));
            $country_state      =   CountryState::find($request->get('country_state'));
            $city               =   City::find($request->get('city'));

            return view('payment.index', [
                'redirect_url'  =>  config('payment.recurring_payment_url'),
                'credentials'   =>  [
                    'MerchantCode'      =>  config('payment.merchant_code'),
                    'RefNo'             =>  $transaction->transaction_no,
                    'FirstPaymentDate'  =>  now()->format('DDMMYYYY'),
                    'Currency'          =>  $transaction->currency->code,
                    'Amount'            =>  $transaction->getFormattedAmount(),
                    'NumberofPayments'  =>  9999, // Unlimited
                    'Frequency'         =>  $this->getRecurringFrequency($order->orderItems()->first()),
                    'Desc'              =>  $transaction->sourceable->concat_item_name,
                    'CC_Ic'             =>  $request->get('nric'),
                    'CC_Email'          =>  $request->get('email'),
                    'CC_Phone'          =>  $request->get('phone'),
                    'P_Name'            =>  $request->get('name'),
                    'P_Email'           =>  $request->get('email'),
                    'P_Phone'           =>  $request->get('phone'),
                    'P_Addrl1'          =>  $request->get('address_1'),
                    'P_Addrl2'          =>  $request->get('address_2'),
                    'P_Zip'             =>  $request->get('postcode'),
                    'P_City'            =>  $city->name,
                    'P_State'           =>  $country_state->name,
                    'P_Country'         =>  $country->name,
                    'Signature'         =>  $this->generateSignature(true),
                    'ResponseURL'       =>  route('payment.response', ['trans_no' => $this->ref_no]),
                    'BackendURL'        =>  route('payment.backend', ['trans_no' => $this->ref_no]),
                ]
            ]);
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Order())
                ->withProperties($ex)
                ->log($ex->getMessage());

            return redirect()->back()->with('fail', $message);
        }
    }

    public function getRecurringFrequency($order_item)
    {
        $frequencies = [
            'weekly' => 1,
            'montly' => 2,
            'quarterly' => 3,
            'half-yearly' => 4,
            'yearly' => 5
        ];

        if ($order_item->orderable_type == Package::class) {

            $package_items = $order_item->orderable->products()->get();

            // foreach($package_items as $item) {
            //     $item->
            // }
        }
    }
}
