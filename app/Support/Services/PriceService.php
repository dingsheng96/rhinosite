<?php

namespace App\Support\Services;


use App\Models\Price;

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
        return (float) ($discount / $price) * 100;
    }

    public function calcSellingPrice($discount, $price)
    {
        return $price - $discount;
    }
}
