<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductAttribute;

class Misc
{
    public static function instance()
    {
        return new self();
    }

    public function adsSlotType(): array
    {
        return [
            Product::SLOT_TYPE_DAILY,
            Product::SLOT_TYPE_WEEKLY,
            Product::SLOT_TYPE_MONTHLY
        ];
    }

    public function validityType(): array
    {
        return [
            ProductAttribute::VALIDITY_TYPE_DAY,
            ProductAttribute::VALIDITY_TYPE_MONTH,
            ProductAttribute::VALIDITY_TYPE_YEAR
        ];
    }

    public function packageStockTypes(): array
    {
        return [
            Package::STOCK_TYPE_FINITE,
            Package::STOCK_TYPE_INFINITE
        ];
    }

    public function productStockTypes(): array
    {
        return [
            ProductAttribute::STOCK_TYPE_FINITE,
            ProductAttribute::STOCK_TYPE_INFINITE
        ];
    }

    public function stripTagsAndAddCountryCodeToPhone(string $phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $country = Country::defaultCountry()->first();

        if (!in_array(substr($phone, 0, 2), $country->dial_code)) {

            $phone = $country->dial_code[0] . ltrim($phone, '0');
        }

        return $phone;
    }

    public function addTagsToPhone(string $phone)
    {
        $format = chunk_split($phone, 4, ' ');

        return '+' . rtrim($format, ' ');
    }
}
