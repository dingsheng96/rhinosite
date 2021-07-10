<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Http\Controllers\Controller;
use App\Support\Facades\PriceFacade;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Ecommerce\CartRequest;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $payment_methods = PaymentMethod::get();

        $cart  = Cart::where('user_id', $user->id)->with(['cartItems'])->first();

        $sub_total = CartFacade::setBuyer($user)->getSubTotal();


        return view('cart.index', compact('cart', 'payment_methods', 'sub_total'));
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
    public function store(CartRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.cart'));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $cart = CartFacade::setRequest($request)->storeData()->getModel();

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.cart', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->sendJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.item', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $cart->delete();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.cart', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData(CartFacade::getCarts())
            ->sendJson();
    }
}
