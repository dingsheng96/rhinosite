<?php

namespace App\Support\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Currency;
use App\Support\Facades\TransactionFacade;

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
        // get currency
        $currency_id = Currency::defaultCountryCurrency()->first()->id;

        $this->cart = Cart::where('user_id', $this->buyer->id)->with(['cartItems'])->firstOrFail();

        // create order
        $this->model = $this->buyer->orders()
            ->create([
                'order_no'      =>  $this->generateReportNo(Order::class, 'order_no', Order::REPORT_PREFIX),
                'currency_id'   =>  $currency_id,
                'total_items'   =>  $this->cart->total_items,
                'sub_total'     =>  $this->cart->sub_total,
                'discount'      =>  0,
                'tax'           =>  0,
                'grand_total'   =>  $this->cart->grand_total,
                'status'        =>  Order::STATUS_PENDING,
            ]);

        $this->storeOrderItems();
        $this->removeCart();
        $this->createTransaction();

        return $this;
    }

    public function storeOrderItems()
    {
        foreach ($this->cart->cartItems()->get() as $cart_item) {

            $selling_price = $cart_item->cartable
                ->prices()->defaultPrice()
                ->first()->selling_price;

            $this->model->orderItems()->create([
                'orderable_type'    =>  $cart_item->cartable_type,
                'orderable_id'      =>  $cart_item->cartable_id,
                'item'              =>  $cart_item->cartable->name,
                'quantity'          =>  $cart_item->quantity,
                'unit_price'        =>  $selling_price,
                'total_price'       =>  $cart_item->quantity * $selling_price
            ]);
        }

        return $this;
    }

    public function setBuyer(User $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    private function createTransaction()
    {
        $transaction = TransactionFacade::setParent($this->model)
            ->setRequest($this->request)->newTransaction()->getModel();

        if (!empty($transaction)) {

            $this->redirect_gateway_permission = true;
        }
    }

    public function getRedirectGatewayPermission()
    {
        return $this->redirect_gateway_permission;
    }

    private function removeCart()
    {
        if ($this->model) {
            $this->cart->cartItems()->delete();
            $this->cart->delete();
        }
    }
}
