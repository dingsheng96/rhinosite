<?php

namespace App\Models;

use App\Helpers\Misc;
use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const MAX_IMAGES = 5;

    protected $table = 'products';

    protected $fillable = [
        'name', 'description', 'product_type_id',
        'currency_id', 'validity', 'is_available'
    ];

    // Relationships
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }

    public function productItems()
    {
        return $this->hasMany(ProductItem::class, 'product_id', 'id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }

    // Scopes
    public function scopePriceChecker($query, $price)
    {
        $tbl_attribute = app(ProductAttribute::class)->getTable();

        return $query->join($tbl_attribute, app(self::class)->getTable() . 'id', '=', $tbl_attribute . '.product_id')
            ->where($tbl_attribute . '.selling_price', Misc::instance()->getPriceFromFloatToInt($price));
    }

    public function scopeProductTypeChecker($query, string $name)
    {
        $tbl_attribute = (new ProductType())->getTable();

        return $query->join($tbl_attribute, (new self())->getTable() . 'id', '=', $tbl_attribute . '.product_id')
            ->where('name', 'like', '%' . $name . '%');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel('availability');

        return '<span class="' . $label[$this->is_available]['class'] . ' px-3">' . $label[$this->is_available]['text'] . '</span>';
    }

    public function getLowestUnitPriceAttribute()
    {
        $product = $this->productAttributes()->orderBy('unit_price', 'asc')->first();

        return Misc::instance()->getPriceFromIntToFloat($product->unit_price ?? 0);
    }

    public function getLowestSellingPriceAttribute()
    {
        $product = $this->productAttributes()->orderBy('selling_price', 'asc')->first();

        return Misc::instance()->getPriceFromIntToFloat($product->selling_price ?? 0);
    }
}
