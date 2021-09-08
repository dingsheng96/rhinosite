<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Price;
use App\Models\Country;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Model;
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

    public function prices()
    {
        return $this->hasMany(Price::class, 'currency_id', 'id');
    }

    public function fromCurrencyRates()
    {
        return $this->hasMany(CurrencyRate::class, 'from_currency', 'id');
    }

    public function toCurrencyRates()
    {
        return $this->hasMany(CurrencyRate::class, 'to_currency', 'id');
    }

    // Scopes
    public function scopeDefaultCountryCurrency($query)
    {
        return $query->whereHas('countries', function ($query) {
            $query->where('set_default', true);
        });
    }

    // Attributes
    public function getNameWithCodeAttribute()
    {
        return $this->name . ' (' . $this->code . ')';
    }

    // Functions
    public function getConversionRate(int $to_currency)
    {
        return $this->fromCurrencyRates->where('to_currency', $to_currency)->first();
    }
}
