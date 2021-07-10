<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Arr;
use App\Support\Facades\CartFacade;

class CartComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  CartRepository $cart
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $cart_details = CartFacade::getCarts();

        $view->with('carts', $cart_details['items']);
        $view->with('sub_total', $cart_details['sub_total']);
        $view->with('cart_currency', $cart_details['currency']);
    }
}
