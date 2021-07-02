<?php

namespace App\Support\Services;

use App\Models\Currency;
use App\Models\CurrencyRate;

class CurrencyService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Currency::class);
    }

    public function storeData()
    {
        $this->storeDetails();
        $this->storeConversionRates();

        return $this;
    }

    public function storeDetails()
    {
        $this->model->name = $this->request->get('name');
        $this->model->code = $this->request->get('code');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        return $this;
    }

    public function storeConversionRates()
    {
        $rates = $this->request->get('rate');

        $rates[$this->model->id] = 1; // self conversion rate

        foreach ($rates as $to_currency => $value) {

            $rate = $this->model->fromCurrencyRates()
                ->where('to_currency', $to_currency)
                ->firstOr(function () {
                    return new CurrencyRate();
                });

            $rate->to_currency  =   $to_currency;
            $rate->rate         =   $value;

            if ($rate->isDirty()) {
                $this->model->fromCurrencyRates()->save($rate);
            }
        }

        return $this;
    }
}
