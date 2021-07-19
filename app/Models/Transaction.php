<?php

namespace App\Models;

use App\Helpers\Status;
use App\Models\Currency;
use App\Models\PaymentMethod;
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

    public function getAmountWithCurrencyCodeAttribute()
    {
        return $this->currency->code . ' ' . $this->amount_with_thousand_symbol;
    }

    public function getStatusLabelAttribute()
    {
        $label =  Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }
}
