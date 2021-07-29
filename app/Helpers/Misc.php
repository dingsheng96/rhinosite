<?php

namespace App\Helpers;

use App\Models\Package;
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
            ProductAttribute::SLOT_TYPE_DAILY,
            ProductAttribute::SLOT_TYPE_WEEKLY,
            ProductAttribute::SLOT_TYPE_MONTHLY
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
}
