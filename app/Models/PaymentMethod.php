<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name', 'system_default'
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_method_id', 'id');
    }

    // Scopes
    public function scopeSystemDefault($query)
    {
        return $query->where('system_default', true);
    }
}
