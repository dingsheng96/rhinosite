<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Http\Controllers\Controller;
use App\Support\Facades\PriceFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            throw_if(
                !$request->has('from_page') && $request->get('from_page') != 1,
                new \Exception('Unable add to cart.')
            );

            $cart = CartFacade::setRequest($request)->setBuyer(Auth::user())->purchase()->getModel();

            DB::commit();

            return redirect()->route('products.index')->withSuccess('Added to cart!');
        } catch (\Error | \Exception $ex) {

            DB::rollback();
            $message = $ex->getMessage();

            return redirect()->back()->with('fail', $message);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('labels.item', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $cart_action =   $request->get('action');

            $item   =   $cart->cartable()->first();

            if ($cart_action == 'decrement') {
                CartFacade::setBuyer(Auth::user())->deductFromCart($item)->getModel();
            } elseif ($cart_action == 'increment') {
                CartFacade::setBuyer(Auth::user())->addToCart($item)->getModel();
            }

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollback();

            request()->session()->flash('fail', $ex->getMessage());

            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.cart', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData(CartFacade::getCarts())
            ->sendJson();
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
