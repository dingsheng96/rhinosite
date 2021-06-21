<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Country\Country;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    protected $table = 'currencies';

    protected $fillable = [
        'name', 'code'
    ];

    // Relationships
    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_id', 'id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'currency_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'currency_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'currency_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'currency_id', 'id');
    }

    // Attributes
    public function getNameWithCodeAttribute()
    {
        return $this->name . ' (' . $this->code . ')';
    }
}