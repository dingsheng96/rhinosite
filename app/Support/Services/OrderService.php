<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Order;

class OrderService extends BaseService
{
    public $buyer, $cart;
    public $redirect_gateway_permission = false;

    public function __construct()
    {
        parent::__construct(Order::class);
    }

    public function createOrder()
    {
        $this->cart = $this->buyer->carts()
            ->with([
                'cartable.prices' => function ($query) {
                    $query->defaultPrice();
                }
            ])->get();

        $cart_summary = $this->cart->map(function ($item, $key) {
            return [
                'currency_id'   =>  $item->price->currency_id,
                'price'         =>  $item->unit_price,
                'quantity'      =>  $item->quantity,
                'total_price'   =>  $item->item_total_price
            ];
        });

        // create order
        $this->model = $this->buyer->orders()
            ->create([
                'order_no'      =>  $this->generateReportNo(Order::class, 'order_no', Order::REPORT_PREFIX),
                'currency_id'   =>  $cart_summary->pluck('currency_id')->unique()->first(),
                'total_items'   =>  $cart_summary->sum('quantity'),
                'sub_total'     =>  $cart_summary->sum('total_price'),
                'discount'      =>  0,
                'tax'           =>  0,
                'grand_total'   =>  $cart_summary->sum('total_price'),
                'status'        =>  Order::STATUS_PENDING,
            ]);

        $this->setModel($this->model);

        $this->storeOrderItems();

        $this->removeCart();

        return $this;
    }

    public function storeOrderItems()
    {
        foreach ($this->cart as $cart_item) {

            $item   =   $cart_item->cartable;
            $price  =   $item->prices->first();

            $this->model->orderItems()
                ->create([
                    'quantity'          =>  $cart_item->quantity,
                    'orderable_type'    =>  get_class($item),
                    'orderable_id'      =>  $item->id,
                    'item'              =>  $item->name ?? $item->product->name,
                    'unit_price'        =>  $price->selling_price,
                    'total_price'       =>  $cart_item->quantity * $price->selling_price,
                ]);
        }

        return $this;
    }

    public function setBuyer(User $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    private function removeCart()
    {
        if ($this->model) {
            $this->buyer->carts()->delete();
        }
    }

    public function setOrderStatus(string $status = Order::STATUS_PENDING)
    {
        $this->model->status = $status;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }
}
