<?php

namespace App\Support\Services;

use App\Models\Cart;
use App\Models\Package;
use App\Models\CartItem;
use Illuminate\Support\Arr;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;

class CartService extends BaseService
{
    public $item_id, $type, $quantity, $action, $item, $buyer;

    public function __construct()
    {
        parent::__construct(Cart::class);
    }

    public function setVariables()
    {
        [
            'item_id'   =>  $this->item_id,
            'type'      =>  $this->type,
            'quantity'  =>  $this->quantity
        ] = $this->request->get('item');

        $this->getItemDetails();

        return $this;
    }

    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function addToCart()
    {
        $this->setVariables();

        // check available stock, throw error if out of stock
        throw_if(
            $this->item->stock_type != get_class($this->item)::STOCK_TYPE_INFINITE &&
                $this->item->quantity < $this->quantity,
            new \Exception(__('messages.out_of_stock'))
        );

        // create new cart if not exists
        $this->model = Cart::firstOrNew(['user_id' => $this->buyer->id]);
        $this->model->save();

        $this->addCartItems();

        return $this;
    }

    public function addCartItems()
    {
        if ($this->model->exists && $this->type == CartItem::TYPE_PACKAGE) {

            $this->model->cartItems()->where('type', CartItem::TYPE_PACKAGE)->delete();
        }

        $cart_item = $this->model->cartItems()
            ->firstOr(function () {
                return new CartItem();
            });

        $cart_item->cartable_type       =   get_class($this->item);
        $cart_item->cartable_id         =   $this->item->id;
        $cart_item->type                =   $this->type;
        $cart_item->quantity            +=  $this->quantity;

        $this->model->cartItems()->save($cart_item);

        return $this;
    }

    public function deductFromCart()
    {
        $this->model = $this->item->carts()
            ->where('user_id', $this->buyer->id)
            ->firstOrFail();

        $this->model->quantity  -=  $this->quantity;

        if ($this->model->isDirty()) {
            $this->item->carts()->save($this->model);
        }

        if ($this->model->quantity = 0) {
            $this->removeItemFromCart();
        }

        return $this;
    }

    public function removeItemFromCart()
    {
        $this->model->delete();

        return $this;
    }

    protected function getItemDetails()
    {
        switch ($this->type) {
            case 'product':
                $this->item = ProductAttribute::where('id', $this->item_id)
                    ->with([
                        'prices' => function ($query) {
                            $query->defaultPrice();
                        }
                    ])
                    ->first();
                break;

            case 'package':
                $this->item = Package::where('id', $this->item_id)
                    ->with([
                        'prices' => function ($query) {
                            $query->defaultPrice();
                        }
                    ])
                    ->first();
                break;

            default:
                throw (new \Exception(__('validation.exists', ['attribute' => trans_choice('labels.item', 1)])));
                break;
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
