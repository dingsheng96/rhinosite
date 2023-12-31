<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Order;
use App\Models\Package;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\TransactionFacade;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\Merchant\RecurringPaymentRequest;

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

            return view('merchant.checkout.recurring');
        }

        return view('merchant.checkout.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.order', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $payment_method = PaymentMethod::systemDefault()->firstOrFail();

            $request->request->add(['payment_method' => $payment_method->id]);

            // store order
            $order = OrderFacade::setRequest($request)
                ->setBuyer(Auth::user())
                ->createOrder()
                ->getModel();

            // create new transaction from order
            $transaction = TransactionFacade::setParent($order)
                ->setRequest($request)
                ->newTransaction()
                ->getModel();

            $request->request->add(['transaction_id' => $transaction->id, 'recurring' => false]);

            DB::commit();

            return (new PaymentController())->redirect($request);
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            activity()->useLog('merchant:checkout')
                ->causedBy(Auth::user())
                ->performedOn(new Order())
                ->withProperties($ex)
                ->log($ex->getMessage());

            return redirect()->back()->with('fail', $message);
        }
    }

    public function recurring(RecurringPaymentRequest $request)
    {
        if ($request->has('cancel')) {

            Auth::user()->carts()->delete();

            return redirect()->route('merchant.subscriptions.index')->withSuccess(__('messages.order_cancelled'));
        }

        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.order', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $payment_method = PaymentMethod::systemDefault()->firstOrFail();

            $request->request->add(['payment_method' => $payment_method->id]);

            // store order
            $order = OrderFacade::setRequest($request)
                ->setBuyer(Auth::user())
                ->createOrder()
                ->getModel();

            // create new transaction from order
            $transaction = TransactionFacade::setParent($order)
                ->setRequest($request)
                ->newTransaction()
                ->getModel();

            $request->request->add(['transaction_id' => $transaction->id, 'recurring' => true]);

            DB::commit();

            return (new PaymentController())->redirect($request);
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            activity()->useLog('merchant:checkout')
                ->causedBy(Auth::user())
                ->performedOn(new Order())
                ->withProperties($ex)
                ->log($ex->getMessage());

            return redirect()->back()->with('fail', $message);
        }
    }
}
