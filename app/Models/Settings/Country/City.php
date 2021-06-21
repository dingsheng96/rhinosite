<?php

namespace App\Models\Settings\Country;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Country\Country;
use App\Models\Settings\Country\CountryState;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $table = 'cities';

    protected $fillable = ['name', 'country_state_id'];

    // Relationships
    public function countryState()
    {
        return $this->belongsTo(CountryState::class, 'country_state_id', 'id');
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, CountryState::class, 'id', 'id', 'country_state_id', 'country_id');
    }

    // Attributes
    public function getCountryStateName()
    {
        return $this->country_state->name ?? '';
    }

    public function getCountryName()
    {
        return $this->country->name ?? '';
    }
}
