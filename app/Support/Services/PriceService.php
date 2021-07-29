<?php

namespace App\Support\Services;

use App\Models\Price;
use App\Models\Currency;

class PriceService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Price::class);
    }

    public function convertIntToFloat(int $price): float
    {
        return (float) $price / 100;
    }

    public function convertFloatToInt(float $price): int
    {
        $split = explode('.', (string) $price);

        $result = $split[0] . '00';

        if (count($split) > 1) {
            $numerator = rtrim($split[1], '0');
            $result += $numerator . '0';
        }

        return (int) $result;
    }

    public function calcDiscountPercentage($discount, $price): float
    {
        if ($price == 0 || $discount == 0) {
            return 0;
        }

        return (float) ($discount / $price) * 100;
    }

    public function calcSellingPrice($discount, $price)
    {
        return $price - $discount;
    }

    public function storeConvertedPrice($default_price)
    {
        $currencies = Currency::get()->except($default_price->currency_id);

        foreach ($currencies as $currency) {

            $convert_rate = $default_price->currency
                ->fromCurrencyRates()
                ->where('to_currency', $currency->id)
                ->first()
                ->rate;

            $price = $this->parent->prices()
                ->where('currency_id', $currency->id)
                ->firstOr(function () {
                    return new Price();
                });

            $price->currency_id             =   $currency->id;
            $price->unit_price              =   $default_price->unit_price * $convert_rate;
            $price->discount                =   $default_price->discount * $convert_rate;
            $price->discount_percentage     =   $default_price->discount_percentage;
            $price->selling_price           =   $default_price->selling_price * $convert_rate;

            $this->parent->prices()->save($price);
        }

        return $this;
    }
}
