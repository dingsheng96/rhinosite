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
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $table = 'product_attributes';

    protected $fillable = [
        'product_id', 'sku', 'stock_type', 'quantity', 'status', 'validity'
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

    public function package()
    {
        return $this->belongsToMany(
            Package::class,
            PackageItem::class,
            'product_attribute_id',
            'package_id',
            'id',
            'id'
        )->withPivot('quantity');
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'cartable');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getDefaultPriceAttribute()
    {
        return $this->prices()->defaultPrice()->first();
    }
}
