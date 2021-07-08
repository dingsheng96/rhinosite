<?php

namespace App\Models;

use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'user_id', 'cartable_type', 'cartable_id', 'type', 'quantity'
    ];

    // Relationships
    public function cartable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Attributes
    public function getPriceAttribute()
    {
        return $this->cartable->prices()
            ->defaultPrice()->first();
    }

    public function getItemTotalPriceAttribute()
    {
        $sub_total = $this->price->selling_price * $this->quantity;

        return number_format($sub_total, 2, '.', '');
    }

    public function getGrandTotalAttribute()
    {
        $grand_total = $this->price->selling_price * $this->quantity;

        return number_format($grand_total, 2, '.', '');
    }

    public function getUnitPriceAttribute()
    {
        return number_format($this->price->selling_price, 2, '.', '');
    }
}
