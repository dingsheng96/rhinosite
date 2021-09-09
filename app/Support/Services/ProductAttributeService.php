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
        $attribute = $this->parent->productAttributes()
            ->where('id', $this->model->id)
            ->firstOr(function () {
                return new ProductAttribute();
            });

        $attribute->sku                 =   $this->request->get('sku');
        $attribute->stock_type          =   $this->request->get('stock_type');
        $attribute->stock_quantity      =   $this->request->get('stock_quantity');
        $attribute->quantity            =   $this->request->get('quantity');
        $attribute->status              =   $this->request->get('status');
        $attribute->validity_type       =   $this->request->get('validity_type');
        $attribute->validity            =   $this->request->get('validity');
        $attribute->recurring           =   $this->request->has('recurring');
        $attribute->published           =   $this->request->has('published');
        $attribute->trial_mode          =   $this->request->has('trial_mode');

        if ($attribute->isDirty()) {

            $this->parent->productAttributes()->save($attribute);
        }

        $this->setModel($attribute);

        return $this;
    }
}
