<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{


    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id', 'cartable_type', 'cartable_id', 'type', 'quantity'
    ];

    // Relationships



}
