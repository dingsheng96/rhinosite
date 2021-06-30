<?php

namespace App\Models;

use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;

    const STOCK_TYPE_INFINITE = 'infinite';
    const STOCK_TYPE_FINITE = 'finite';

    protected $table = 'product_attributes';

    protected $fillable = [
        'product_id', 'sku', 'stock_type', 'quantity', 'is_available', 'validity'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel('availability');

        return '<span class="' . $label[$this->is_available]['class'] . ' px-3">' . $label[$this->is_available]['text'] . '</span>';
    }
}
