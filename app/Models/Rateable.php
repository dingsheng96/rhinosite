<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Rateable extends MorphPivot
{
    protected $table = 'rateables';

    protected $fillable = [
        'user_id', 'rateable_type', 'rateable_id', 'scale', 'created_at'
    ];
}
