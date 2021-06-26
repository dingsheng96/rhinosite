<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes;

    protected $table = 'prices';

    protected $fillable = [
        'priceable_type', 'priceable_id', 'currency_id', 'unit_price',
        'discount', 'discount_percentage', 'selling_price'
    ];

    // Relationships
    public function priceable()
    {
        return $this->morphTo();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
