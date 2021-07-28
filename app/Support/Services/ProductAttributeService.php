<?php

namespace App\Support\Services;

use App\Models\ProductAttribute;

class ProductAttributeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(ProductAttribute::class);
    }

    public function storeData()
    {
        $this->storeAttribute();
        $this->storePrice();

        return $this;
    }

    public function storeAttribute()
    {
        $attribute = !is_null($this->parent) ? new ProductAttribute() : $this->model;

        $attribute->sku                 =   $this->request->get('sku');
        $attribute->stock_type          =   $this->request->get('stock_type');
        $attribute->quantity            =   $this->request->get('quantity');
        $attribute->status              =   $this->request->get('status');
        $attribute->validity            =   $this->request->get('validity');
        $attribute->slot                =   $this->request->get('slot');
        $attribute->slot_type           =   $this->request->get('slot_type');
        $attribute->total_slots_per_day =   $this->request->get('total_slots_per_day');

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
}
