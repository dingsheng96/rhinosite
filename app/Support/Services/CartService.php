<?php

namespace App\Support\Services;

use App\Models\Cart;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CartService extends BaseService
{
    public $item_id, $type, $quantity, $action, $item, $buyer;

    public function __construct()
    {
        parent::__construct(Cart::class);
    }

    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function addToCart(Model $item)
    {
        // check available stock, throw error if out of stock
        throw_if(
            $item->stock_type != get_class($item)::STOCK_TYPE_INFINITE &&
                $item->quantity < $this->quantity,
            new \Exception(__('messages.out_of_stock'))
        );

        // delete the package item in cart when item is package
        if (get_class($item) == Package::class) {

            Cart::where('user_id', $this->buyer->id)
                ->where('cartable_type', Package::class)
                ->delete();
        }

        // create new cart if not exists
        $this->model = $item->carts()
            ->where('user_id', $this->buyer->id)
            ->firstOr(function () {
                return new Cart();
            });

        $this->model->user_id   =   $this->buyer->id;
        $this->model->quantity  +=  1;

        $item->carts()->save($this->model);

        return $this;
    }

    public function deductFromCart(Model $item)
    {
        $this->model = $item->carts()
            ->where('user_id', $this->buyer->id)
            ->firstOrFail();

        $this->model->quantity  -=  1;

        if ($this->model->isDirty()) {
            $item->carts()->save($this->model);
        }

        if ($this->model->quantity == 0) {
            $this->model->delete();
        }

        return $this;
    }

    public function getSubTotal()
    {
        $sub_total = 0;

        $cart = Cart::where('user_id', $this->buyer->id)->first();

        if ($cart) {
            foreach ($cart->cartItems()->get() as $item) {
                $sub_total += $item->quantity * $item->cartable->price->selling_price;
            }
        }

        return $sub_total;
    }

    public function getGrandTotal()
    {
        $grand_total = 0;

        $cart = Cart::where('user_id', $this->buyer->id)->first();

        if ($cart) {
            foreach ($cart->cartItems()->get() as $item) {
                $grand_total += $item->quantity * $item->cartable->price->selling_price;
            }
        }

        return $grand_total;
    }

    public function getTotalItemsInCart()
    {
        $total = 0;

        $cart = Cart::where('user_id', $this->buyer->id)->first();

        if ($cart) {
            $total = $cart->cartItems()->sum('quantity');
        }

        return $total;
    }

    public function getCarts(): array
    {
        $carts = Cart::with([
            'cartable.prices' => function ($query) {
                $query->defaultPrice();
            }
        ])
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($cart, $key) {
                return [
                    'id' => $cart->id,
                    'name' => $cart->cartable->item_name,
                    'quantity' => $cart->quantity,
                    'price' => $cart->cartable->price->selling_price,
                    'currency' => $cart->cartable->price->currency->code,
                    'enable_quantity_input' => $cart->enable_quantity_input
                ];
            });

        return [
            'items' => $carts,
            'sub_total' => $carts->sum('price') ?? 0,
            'currency' => $carts->pluck('currency')->unique('currency')->first()
        ];
    }
}
