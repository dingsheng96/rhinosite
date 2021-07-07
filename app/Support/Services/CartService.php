<?php

namespace App\Support\Services;

use App\Models\Cart;
use App\Models\Package;
use App\Models\CartItem;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CartService extends BaseService
{
    public $item_id, $type, $quantity, $action, $item;

    public function __construct()
    {
        parent::__construct(Cart::class);
    }

    public function addToCart()
    {
        [
            'item_id'   =>  $this->item_id,
            'type'      =>  $this->type,
            'quantity'  =>  $this->quantity,
            'action'    =>  $this->action
        ] = $this->request->get('item');

        $this->getItemDetails();

        $this->checkAvailableStock();

        $this->model = Auth::user()->cart()
            ->firstOr(function () {
                return new Cart();
            });

        if (!$this->model->exists) {
            $this->model->id = time();
            $this->model->total_items   =   0;
            $this->model->sub_total     =   0;
            $this->model->discount      =   0;
            $this->model->grand_total   =   0;
            Auth::user()->cart()->save($this->model);
        }

        $this->storeCartItems();

        $this->calculateTotalInCart();

        return $this;
    }

    public function updateCartItem(CartItem $cart_item, string $action, $quantity)
    {
        $this->type     =   $cart_item->type;
        $this->quantity =   $quantity;
        $this->item     =   $cart_item;

        switch ($action) {

            case 'minus':

                $cart_item->quantity    -=  $this->quantity;
                $cart_item->total_price -=  $this->quantity * $this->item->prices()->first()->selling_price;

                if ($cart_item->quantity == 0) {
                    $this->removeItemFromCart($cart_item);
                }

                break;

            case 'add':

                $this->checkAvailableStock();

                $cart_item->quantity    +=  $this->quantity;
                $cart_item->total_price +=  $this->quantity * $this->item->prices()->first()->selling_price;

                break;
        }

        $this->model->cartItems()->save($cart_item);

        return $this;
    }

    protected function storeCartItems()
    {
        $cart_item = $this->model->cartItems()
            ->whereHasMorph(
                'cartable',
                get_class($this->item),
                function (Builder $query) {
                    $query->where('cartable_id', $this->item->id);
                }
            )->firstOr(function () {
                return new CartItem();
            });

        if (!$cart_item->exists) { // create new cart items
            $cart_item->cartable_type   =   get_class($this->item);
            $cart_item->cartable_id     =   $this->item->id;
            $cart_item->type            =   $this->type;
            $cart_item->quantity        =   $this->quantity;
            $cart_item->unit_price      =   $this->item->prices->first()->selling_price;
            $cart_item->total_price     =   $this->quantity * $this->item->prices()->first()->selling_price;
        } else {

            $cart_item->quantity    +=   $this->quantity;
            $cart_item->total_price +=   $this->quantity * $this->item->prices()->first()->selling_price;
        }

        $this->model->cartItems()->save($cart_item);

        return $this;
    }

    protected function checkAvailableStock()
    {
        throw_if(
            $this->item->stock_type != get_class($this->item)::STOCK_TYPE_INFINITE &&
                $this->item->quantity < $this->quantity,
            new \Exception(__('messages.out_of_stock'))
        );
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
    }

    public function removeItemFromCart(CartItem $cart_item)
    {
        $cart_item->delete();

        if ($this->model->cartItems()->count() > 0) {

            $this->calculateTotalInCart();

            return $this;
        }

        $this->model->delete();

        return $this;
    }

    public function calculateTotalInCart()
    {
        $cart_items = $this->model->cartItems()->get();

        if ($cart_items) {
            $quantity   =   $cart_items->sum('quantity');
            $sub_total  =   $cart_items->sum('total_price');

            $this->model->total_items    =   $quantity;
            $this->model->sub_total      =   $sub_total;
            $this->model->grand_total    =   $sub_total - $this->model->discount;

            Auth::user()->cart()->save($this->model);
        }

        return $this;
    }
}
