<?php

namespace App\Support\Services;

use App\Models\Price;
use App\Models\Package;
use App\Models\Currency;
use App\Support\Facades\PriceFacade;

class PackageService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Package::class);
    }

    public function storeData()
    {
        $this->storeDetails();
        $this->savePrice();
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

    public function savePrice()
    {
        $default_price = $this->model->prices()
            ->where('currency_id', $this->request->get('currency'))
            ->firstOr(function () {
                return new Price();
            });

        $default_price->currency_id             =   $this->request->get('currency');
        $default_price->unit_price              =   $this->request->get('unit_price');
        $default_price->discount                =   $this->request->get('discount');
        $default_price->discount_percentage     =   PriceFacade::calcDiscountPercentage($this->request->get('discount'), $this->request->get('unit_price'));
        $default_price->selling_price           =   PriceFacade::calcSellingPrice($this->request->get('discount'), $this->request->get('unit_price'));

        if ($default_price->isDirty()) {
            $this->model->prices()->save($default_price);
        }

        PriceFacade::setParent($this->model)->storeConvertedPrice($default_price);

        return $this;
    }
}
