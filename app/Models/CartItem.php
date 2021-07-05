<?php

namespace App\Models;

use App\Models\Cart;
use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'cart_id', 'item_index', 'cartable_type', 'cartable_id',
        'quantity', 'total_price'
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function product()
    {
        return $this->morphTo(Product::class, 'cartable_type', 'cartable_id', 'id');
    }

    public function package()
    {
        return $this->morphTo(Package::class, 'cartable_type', 'cartable_id', 'id');
    }

    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = PriceFacade::convertFloatToInt($value);
    }

    public function getTotalPriceAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }
}
