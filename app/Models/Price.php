<?php

namespace App\Models;

use App\Support\Facades\PriceFacade;
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

    // Scopes
    public function scopeDefaultPrice($query)
    {
        $default_currency = Currency::defaultCountryCurrency()->first();

        return $query->where('currency_id', $default_currency->id);
    }

    // Attributes
    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = PriceFacade::convertFloatToInt($value);
    }

    public function setSellingPriceAttribute($value)
    {
        $this->attributes['selling_price'] = PriceFacade::convertFloatToInt($value);
    }

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = PriceFacade::convertFloatToInt($value);
    }

    public function getUnitPriceAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getSellingPriceAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getDiscountAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getDefaultLabelAttribute()
    {
        if ($this->is_default) {
            return '<span class="badge badge-pill badge-success px-3">' . __('labels.default') . '</span>';
        }

        return;
    }
}
