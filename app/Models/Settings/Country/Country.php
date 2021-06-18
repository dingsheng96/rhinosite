<?php

namespace App\Models\Settings\Country;

use App\Models\Settings\Currency;
use App\Models\Settings\Country\City;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Country\CountryState;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $table = 'countries';

    protected $fillable = [
        'name', 'currency_id', 'dial_code'
    ];

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
}
