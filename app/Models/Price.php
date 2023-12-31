<?php

namespace App\Models;

use App\Models\Currency;
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
        return $query->with('currency')
            ->whereHas('currency', function ($query) {
                $query->defaultCountryCurrency();
            });
    }

    public function scopePriceRange($query, $min, $max = null)
    {
        $min = PriceFacade::convertFloatToInt($min);
        $max = empty($max) ? null : PriceFacade::convertFloatToInt($max);

        return $query->where(function ($query) use ($min, $max) {
            $query->when(empty($max), function ($query) use ($min) {
                $query->where('selling_price', '>=', $min);
            })->when(!empty($max), function ($query) use ($min, $max) {
                $query->whereBetween('selling_price', [$min, $max]);
            });
        });
    }

    public function scopePriceWithDefaultCurrency($query)
    {
        return $query->whereHas('currency', function ($query) {
            $query->defaultCountryCurrency();
        });
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

    public function getUnitPriceWithCurrencyAttribute()
    {
        return $this->currency->code . ' ' . $this->unit_price;
    }

    public function getDiscountWithCurrencyAttribute()
    {
        return $this->currency->code . ' ' . $this->discount;
    }

    public function getSellingPriceWithCurrencyAttribute()
    {
        return $this->currency->code . ' ' . $this->selling_price;
    }

    public function getDiscountWithPercentageAttribute()
    {
        return $this->discount_percentage . '%';
    }
}
