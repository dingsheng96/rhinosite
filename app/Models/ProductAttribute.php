<?php

namespace App\Models;

use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;

    protected $table = 'product_attributes';

    protected $fillable = [
        'sku', 'is_available'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productPrices()
    {
        return $this->morphMany(ProductPrice::class, 'priceable');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel('availability');

        return '<span class="' . $label[$this->is_available]['class'] . ' px-3">' . $label[$this->is_available]['text'] . '</span>';
    }
}
