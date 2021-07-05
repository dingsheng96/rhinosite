<?php

namespace App\Support\Services;

use Exception;
use App\Models\Package;
use Illuminate\Support\Arr;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CartService extends BaseService
{
    public $items = [];
    public $total_items = 0;
    public $total_price = 0;
    public $currency = 'RM';
    public $item_id, $type, $quantity;
    public $cart, $item;
    public $cart_item, $cart_item_quantity, $cart_item_price;

    public function storeData()
    {
    }

    private function getItemDetails()
    {
    }

    private function checkAvailableStock()
    {
    }

    private function calculateItemPrice()
    {
    }
}
