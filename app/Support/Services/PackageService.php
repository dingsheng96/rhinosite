<?php

namespace App\Support\Services;

use App\Models\Package;

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
        $this->model->name              =   $this->request->get('name');
        $this->model->description       =   $this->request->get('description');
        $this->model->stock_type        =   $this->request->get('stock_type');
        $this->model->quantity          =   $this->request->get('quantity');
        $this->model->status            =   $this->request->get('status');
        $this->model->purchase_limit    =   $this->request->get('purchase_limit');

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
                $items_array[$item['sku']] = ['quantity' => $item['quantity']];
            }

            $this->model->products()->attach($items_array);
        }

        return $this;
    }
}
