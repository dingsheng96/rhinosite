<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PackageItem extends Pivot
{
    protected $table = 'package_items';

    protected $fillable = [
        'package_id', 'product_attribute_id', 'quantity'
    ];
}
