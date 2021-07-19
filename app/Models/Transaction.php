<?php

namespace App\Models;

use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';
    const STATUS_SUCCESS = 'success';
    const REPORT_PREFIX = 'TXN';

    protected $table = 'transactions';

    protected $fillable = [
        'transaction_no', 'sourceable_type', 'sourceable_id', 'currency_id',
        'amount', 'payment_method_id', 'status', 'remarks'
    ];

    // Relationships
    public function sourceable()
    {
        return $this->morphTo();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    // Attributes
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = PriceFacade::convertFloatToInt($value);
    }

    public function getAmountWithThousandSymbolAttribute()
    {
        return number_format(PriceFacade::convertIntToFloat($this->amount), 2, '.', ',');
    }
}
