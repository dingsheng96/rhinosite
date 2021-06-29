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
        return (int) $price * 100;
    }

    public function calcDiscountPercentage(int $discount, int $price): float
    {
        return (float) ($discount / $price) * 100;
    }
}
