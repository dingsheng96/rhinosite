<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
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
        $cart = Auth::user()->cart()->with([
            'cardItems' => function ($query) {
                $query->orderBy('item_index', 'asc');
            }
        ])->first();

        return view('ecommerce.cart.index', compact('cart'));
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
        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.cart'));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);
        $cart       =   [];

        try {
            $cart = CartFacade::setRequest($request)->storeData();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);
        } catch (\Error | \Exception $ex) {

            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.cart', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData($cart)
            ->sendJson();
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
