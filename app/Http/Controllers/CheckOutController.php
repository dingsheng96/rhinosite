<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Helpers\Message;
use App\Models\Permission;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\TransactionFacade;
use App\Http\Requests\OrderRequest;

class CheckOutController extends Controller
{
    public function index()
    {
        $payment_methods = PaymentMethod::orderBy('name', 'asc')->get();

        return view('checkout.index', compact('payment_methods'));
    }

    public function store(OrderRequest $request)
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

            return redirect()->route('payment.redirect', ['trans_no' => $transaction->transaction_no]);
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
}
