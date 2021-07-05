<?php

namespace App\Models;

use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'total_items', 'sub_total',
        'discount', 'grand_total'
    ];

    // Relationships
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Attributes
    public function setSubTotalAttribute($value)
    {
        $this->attributes['sub_total'] = PriceFacade::convertFloatToInt($value);
    }

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = PriceFacade::convertFloatToInt($value);
    }

    public function setGrandTotalAttribute($value)
    {
        $this->attributes['grand_total'] = PriceFacade::convertFloatToInt($value);
    }

    public function getSubTotalAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getDiscountAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getGrandTotalAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }
}
