<?php

namespace App\Support\Services;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Support\Facades\CartFacade;
use Illuminate\Support\Facades\Auth;

class PackageService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Package::class);
    }

    public function storeData()
    {
        $this->storeDetails();
        $this->storePrice();
        $this->storeItems();

        return $this;
    }

    public function storeDetails()
    {
        $this->model->name          = $this->request->get('name');
        $this->model->description   = $this->request->get('description');
        $this->model->stock_type    = $this->request->get('stock_type');
        $this->model->quantity      = $this->request->get('quantity');
        $this->model->status        = $this->request->get('status');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        return $this;
    }

    public function storeItems()
    {
        if ($this->request->has('items')) {

            $items = $this->request->get('items');

            $items_array = [];

            foreach ($items as $item) {
                $items_array = [
                    $item['sku'] => ['quantity' => $item['quantity']]
                ];
            }

            $this->model->products()->sync($items_array);
        }

        return $this;
    }

    public function purchaseNewPackage()
    {
        $cart = Auth::user()->cart()->first();

        if ($cart) {
            // check cart items whether has package
            if ($cart_item = $cart->cartItems()->where('type', 'package')->first()) {
                $cart_item->delete();
            }
        }

        // add into cart
        $request = new Request();
        $request->request->add(['item' => [
            'item_id'   =>  $this->model->id,
            'type'      =>  'package',
            'quantity'  =>  1,
            'action'    =>  'add'
        ]]);

        return CartFacade::setRequest($request)->addToCart()->getModel();
    }
}
