<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    use SoftDeletes;

    protected $table = 'transaction_details';

    protected $fillable = [
        'transaction_id', 'subscription_reference', 'remark', 'ipay_transaction_id',
        'auth_code', 'status', 'description', 'is_termination'
    ];

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
