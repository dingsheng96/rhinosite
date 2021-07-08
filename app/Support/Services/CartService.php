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

    public function addToCart()
    {
        // check available stock, throw error if out of stock
        throw_if(
            $this->item->stock_type != get_class($this->item)::STOCK_TYPE_INFINITE &&
                $this->item->quantity < $this->quantity,
            new \Exception(__('messages.out_of_stock'))
        );

        // add into user cart
        $this->model = $this->item->carts()
            ->where('user_id', $this->buyer->id)
            ->firstOr(function () {
                return new Cart();
            });

        $this->model->quantity  +=  $this->quantity;

        if (!$this->model->exists) {
            $this->model->user_id   =   $this->buyer_id;
            $this->model->type      =   $this->type;
        }

        if ($this->model->isDirty()) {
            $this->item->carts()->save($this->model);
        }

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

    public function getSubTotalInCart()
    {
        $sub_total = 0;

        foreach ($this->parent->carts()->get() as $cart) {
            $sub_total += $cart->quantity * $cart->cartable->price->selling_price;
        }

        return $sub_total;
    }

    public function getTotalItemsInCart()
    {
        return $this->parent->carts()->sum('quantity');
    }

    public function setBuyer($buyer)
    {
        return $this->buyer = $buyer;
    }
}
