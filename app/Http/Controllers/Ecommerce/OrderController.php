<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\User;
use App\Models\Order;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\DataTables\OrderDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Ecommerce\OrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('ecommerce.order.index');
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
        $module     =   strtolower(__('modules.submodules.order'));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $user = User::where('id', Auth::id())->firstOrFail();

            // store order
            $proceed_gateway = OrderFacade::setRequest($request)
                ->setBuyer($user)
                ->createOrder()
                ->getRedirectGatewayPermission();

            if ($proceed_gateway) {
                DB::commit();

                // redirect to payment gateway
                return $proceed_gateway;
            }
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Order())
                ->withProperties($ex)
                ->log($ex->getMessage());

            return redirect()->back()->with('fail', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
