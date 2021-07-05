<?php

namespace App\Support\Services;

use App\Models\Order;

class OrderService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Order::class);
    }

    public function storeData()
    {
        $this->storeOrder();
        $this->storeOrderItems();

        return $this;
    }

    public function storeOrder()
    {
        //
    }

    public function storeOrderItems()
    {
        //
    }
}
