<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Helpers\Message;
use App\Models\CartItem;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Http\Controllers\Controller;
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

        $carts = Cart::where('user_id', $user->id)->get();

        $payment_methods = PaymentMethod::get();

        return view('ecommerce.cart.index', compact('carts', 'payment_methods'));
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
    public function store(CartRequest $request, Cart $cart)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.cart'));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);
        $cart       =   [];

        try {
            dd($cart);
            $cart = CartFacade::setRequest($request)->storeData()->getModel();

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
            ->withData((array) $cart)
            ->sendJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Cart $cart, CartItem $cart_item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart, CartItem $cart_item)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.item', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            CartFacade::setModel($cart)->removeItemFromCart($cart_item);

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
            ->withData([
                'redirect_to' => route('ecommerce.carts.index')
            ])
            ->sendJson();
    }
}
