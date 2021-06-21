<?php

namespace App\Helpers;

class Misc
{
    public static function instance()
    {
        return new self();
    }

    public function getPriceFromIntToFloat(int $price): string
    {
        return number_format(($price / 100), 2, '.', ',');
    }

    public function getPriceFromFloatToInt(float $price): int
    {
        return intval($price * 100);
    }
}
