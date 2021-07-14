<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name'
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_method_id', 'id');
    }
}
