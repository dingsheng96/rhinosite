<?php

namespace App\Models;

use App\Models\Cart;
use Illuminate\Support\Str;
use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    const TYPE_PRODUCT = 'product';
    const TYPE_PACKAGE = 'package';

    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id', 'cartable_type', 'cartable_id',
        'type', 'quantity', 'total_price'
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function cartable()
    {
        return $this->morphTo();
    }

    // Attributes
    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = PriceFacade::convertFloatToInt($value);
    }

    public function getTotalPriceAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getItemNameAttribute()
    {
        if ($this->cartable_type == ProductAttribute::class) {
            return $this->cartable->product->name;
        }

        return $this->cartable->name;
    }
}
