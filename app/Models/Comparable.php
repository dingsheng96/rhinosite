<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Comparable extends MorphPivot
{
    protected $table = 'comparables';

    protected $fillable = [
        'user_id', 'comparable_type', 'comparable_id'
    ];
}
