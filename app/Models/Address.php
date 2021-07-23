<?php

namespace App\Models;

use App\Models\City;
use App\Models\CountryState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = [
        'sourceable_type', 'sourceable_id', 'address_1',
        'address_2', 'postcode', 'city_id'
    ];

    // Relationships
    public function sourceable()
    {
        return $this->morphTo();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function countryState()
    {
        return $this->hasOneThrough(CountryState::class, City::class, 'id', 'id', 'city_id', 'country_state_id');
    }
}
