<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'sourceable_type', 'sourceable_id', 'currency_id', 'amount',
        'payment_method_id', 'status', 'remarks'
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
}
