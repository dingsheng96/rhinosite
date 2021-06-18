<?php

namespace App\Models\Settings\Country;

use App\Models\Settings\Country\City;
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
}
