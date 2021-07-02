<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyRate extends Model
{
    use SoftDeletes;

    protected $table = 'currency_rates';

    protected $fillable = ['from_currency', 'to_currency', 'rate'];

    // Relationships
    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency', 'id');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency', 'id');
    }

    // Attributes
    public function getRateAttribute($value)
    {
        return number_format($value, 5, '.', '');
    }
}
