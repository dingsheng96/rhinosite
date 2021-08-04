<?php

namespace App\Models;

use App\Models\User;
use App\Models\Package;
use App\Models\ProductAttribute;
use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'user_id', 'cartable_type', 'cartable_id', 'quantity'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cartable()
    {
        return $this->morphTo();
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

    public function getUnitPriceWithCurrencyAttribute()
    {
        return $this->price->currency->code . $this->unit_price;
    }

    public function getItemNameAttribute()
    {
        if ($this->cartable_type == ProductAttribute::class) {
            return $this->cartable->product->name;
        }

        return $this->cartable->name;
    }

    public function getItemDescriptionAttribute()
    {
        if ($this->cartable_type == ProductAttribute::class) {
            return $this->cartable->product->description;
        }

        return $this->cartable->description;
    }

    public function getEnableQuantityInputAttribute()
    {
        if ($this->cartable_type == Package::class) {
            return false;
        }

        return true;
    }
}
