<?php

namespace App\Support\Services;

use App\Models\Cart;
use App\Models\Package;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\BaseService;
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

        $item->load([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ]);

        // delete item which will be charged by recurring
        if ($this->buyer->carts()
            ->whereHasMorph('cartable', [Package::class, ProductAttribute::class], function ($query) {
                $query->where('recurring', true);
            })->exists()
        ) {

            $this->buyer->carts()->delete();
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
        ])->where('user_id', Auth::id())->get()
            ->map(function ($cart) {

                $cart_item  =   $cart->cartable;
                $price      =   $cart_item->prices->first();
                $variant    =   '-';

                if (get_class($cart_item) == ProductAttribute::class && $cart_item->stock_type == ProductAttribute::STOCK_TYPE_INFINITE) {

                    $variant = trans_choice('labels.item_unit', $cart_item->quantity, ['value' => $cart_item->quantity]) . ' - ' . $price->selling_price_with_currency;
                }

                return [
                    'id'                    =>  $cart->id,
                    'name'                  =>  $cart->item_name,
                    'description'           =>  $cart->item_description,
                    'quantity'              =>  $cart->quantity,
                    'enable_quantity_input' =>  $cart->enable_quantity_input,
                    'price'                 =>  number_format($price->selling_price * $cart->quantity, 2, '.', ''),
                    'currency'              =>  $price->currency->code,
                    'variant'               =>  $variant
                ];
            });

        return [
            'items' => $carts,
            'sub_total' => $carts->sum('price') ?? 0,
            'currency' => $carts->pluck('currency')->unique('currency')->first()
        ];
    }

    public function purchase()
    {
        foreach ($this->request->get('item') as $item) {

            $quantity = $item['quantity'];

            while ($quantity > 0) {
                $product = ProductAttribute::find($item['variant']);

                $this->addToCart($product);

                $quantity--;
            }
        }

        return $this;
    }
}
