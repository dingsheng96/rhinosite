<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Admin\OrderDataTable;
use App\Http\Requests\Admin\OrderRequest;
use App\Support\Facades\TransactionFacade;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(__('modules.order'));
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

            activity()->useLog('admin:order')
                ->causedBy(Auth::user())
                ->performedOn(new Order())
                ->withProperties($ex)
                ->log($ex->getMessage());

            return redirect()->back()->with('fail', $message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load(['transaction.paymentMethod', 'orderItems', 'currency']);

        return view('admin.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
