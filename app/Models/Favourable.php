<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Favourable extends MorphPivot
{
    protected $table = 'favourables';

    protected $fillable = [
        'user_id', 'favourable_type', 'favourable_id', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
