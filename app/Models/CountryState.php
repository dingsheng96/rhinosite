<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryState extends Model
{
    use SoftDeletes;

    protected $table = 'country_states';

    protected $fillable = [
        'name', 'country_id'
    ];

    // Relationships
    public function cities()
    {
        return $this->hasMany(City::class, 'country_state_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function addresses()
    {
        return $this->hasManyThrough(Address::class, City::class, 'country_state_id', 'city_id', 'id', 'id');
    }
}
