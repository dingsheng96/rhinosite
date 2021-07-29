<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductItem extends Model
{
    use SoftDeletes;

    protected $table = 'product_items';

    protected $fillable = [
        'product_id', 'sourceable_type', 'sourceable_id', 'quantity'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function sourceable()
    {
        return $this->morphTo();
    }
}
