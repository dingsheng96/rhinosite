<?php

namespace App\Models;

use App\Models\City;
use App\Models\Currency;
use App\Models\CountryState;
use App\Observers\CountryObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $table = 'countries';

    protected $fillable = [
        'name', 'code', 'set_default', 'currency_id', 'dial_code'
    ];

    // Functions
    protected static function boot()
    {
        parent::boot();

        self::observe(CountryObserver::class);
    }

    // Relationships
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function countryStates()
    {
        return $this->hasMany(CountryState::class, 'country_id', 'id');
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, CountryState::class, 'country_id', 'country_state_id', 'id', 'id');
    }

    // Scope
    public function scopeDefaultCountry()
    {
        return $this->where('set_default', true);
    }

    // Attributes
    public function getDialCodeAttribute($value)
    {
        return explode(',', $value);
    }

    public function getFormattedDialCodeAttribute()
    {
        return implode(',', $this->dial_code);
    }
}
