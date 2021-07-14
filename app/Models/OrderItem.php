<?php

namespace App\Models;

use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 'orderable_type', 'orderable_id', 'item',
        'quantity', 'unit_price', 'total_price'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function orderable()
    {
        return $this->morphTo();
    }

    // Attributes
    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = PriceFacade::convertFloatToInt($value);
    }

    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = PriceFacade::convertFloatToInt($value);
    }

    public function getUnitPriceAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getTotalPriceAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }
}
