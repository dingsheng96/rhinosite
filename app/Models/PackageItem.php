<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class PackageItem extends MorphPivot
{
    protected $table = 'package_items';

    protected $fillable = [
        'package_id', 'packageable_type', 'packageable_id', 'quantity'
    ];
}
