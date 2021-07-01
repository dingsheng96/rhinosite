<?php

namespace App\Support\Services;

use App\Models\Price;
use App\Models\Currency;
use App\Models\ProductAttribute;
use App\Support\Facades\PriceFacade;

class ProductAttributeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(ProductAttribute::class);
    }

    public function storeData()
    {
        $this->storeAttribute();
        $this->savePrice();

        return $this;
    }

    public function storeAttribute()
    {
        $attribute = !is_null($this->parent) ? new ProductAttribute() : $this->model;

        $attribute->sku         =   $this->request->get('sku');
        $attribute->stock_type  =   $this->request->get('stock_type');
        $attribute->quantity    =   $this->request->get('quantity');
        $attribute->status      =   $this->request->get('status');
        $attribute->validity    =   $this->request->get('validity');

        if (is_null($this->parent)) {

            if ($attribute->isDirty()) {
                $attribute->save();
            }
        } else {
            $this->parent->productAttributes()->save($attribute);
        }

        $this->setModel($attribute);

        return $this;
    }

    public function savePrice()
    {
        $currencies = Currency::all();

        $default_price = $this->model->prices()
            ->defaultPrice()
            ->firstOr(function () {
                return new Price();
            });

        $default_price->currency_id             =   $this->request->get('currency');
        $default_price->unit_price              =   $this->request->get('unit_price');
        $default_price->discount                =   $this->request->get('discount');
        $default_price->discount_percentage     =   PriceFacade::calcDiscountPercentage($this->request->get('discount'), $this->request->get('unit_price'));
        $default_price->selling_price           =   PriceFacade::calcSellingPrice($this->request->get('discount'), $this->request->get('unit_price'));
        $default_price->is_default              =   true;

        if ($default_price->isDirty()) {
            $this->model->prices()->save($default_price);
        }

        return $this;
    }
}
