<?php

namespace App\Support\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Currency;
use App\Support\Facades\CartFacade;
use Illuminate\Support\Facades\Auth;
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
        $this->cart = Cart::with([
            'cartable.prices' => function ($query) {
                $query->defaultPrice();
            }
        ])->where('user_id', Auth::id())->get();

        $cart_summary = $this->cart
            ->map(function ($item, $key) {
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

        $this->storeOrderItems();
        $this->removeCart();
        $this->createTransaction();

        return $this;
    }

    public function storeOrderItems()
    {
        foreach ($this->cart as $cart_item) {

            $item = $cart_item->cartable
                ->prices()->defaultPrice()->first();

            $this->model->orderItems()->create([
                'orderable_type'    =>  $cart_item->cartable_type,
                'orderable_id'      =>  $cart_item->cartable_id,
                'item'              =>  $cart_item->cartable->name,
                'quantity'          =>  $cart_item->quantity,
                'unit_price'        =>  $item->selling_price,
                'total_price'       =>  $cart_item->quantity * $item->selling_price
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
            foreach ($this->cart as $cart) {

                $cart->delete();
            }
        }
    }
}
