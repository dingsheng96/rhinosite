<?php

namespace App\Models;

use App\Helpers\Misc;
use App\Models\Settings\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'title', 'description', 'user_id', 'services', 'materials',
        'currency_id', 'unit_price', 'unit_id', 'unit_value', 'on_listing'
    ];

    // Relationships
    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    // Scopes
    public function scopeListing($query, bool $status)
    {
        return $query->where('on_listing', $status);
    }

    // Attributes
    public function getPriceAttribute()
    {
        $currency   =   $this->currency->code;
        $price      =   Misc::instance()->getPriceFromIntToFloat($this->unit_price);
        $unit       =   $this->unit->display;
        $unit_value =   $this->unit_value;

        return $currency . $price . ' / ' . $unit_value . $unit;
    }

    public function getLocationAttribute()
    {
        $city           =   $this->city->name ?? '';
        $country_state  =   $this->city->countryState->name ?? '';
        $country        =   $this->city->country->name ?? '';

        return $city . ', ' . $country_state . ', ' . $country;
    }

    public function getEnglishTitleAttribute()
    {
        return $this->translations()
        ->whereHas('language', function ($query) {
            $query->where('code', Language::CODE_EN);
        })->first()
        ->value;
    }
}
