<?php

namespace App\Models;

use App\Helpers\Status;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsBooster extends Model
{
    use SoftDeletes;

    const CATEGORY_BUMP = 'Category Bump';
    const CATEGORY_HIGHLIGHT = 'Category Highlight';

    protected $table = 'ads_boosters';

    protected $fillable = [
        'product_attribute_id', 'boostable_type', 'boostable_id', 'boosted_at'
    ];

    protected $casts = [
        'boosted_at' => 'datetime'
    ];

    // Relationships
    public function boostable()
    {
        return $this->morphTo();
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id', 'id');
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductAttribute::class, 'id', 'id', 'product_attribute_id', 'product_id');
    }

    // Scopes
    public function scopeBoosting($query)
    {
        return $query->whereDate('boosted_at', today()->format('Y-m-d'));
    }

    public function scopeCategoryBump($query)
    {
        return $query->whereHas('productAttribute', function ($query) {
            $query->whereHas('product', function ($query) {
                $query->filterCategory(ProductCategory::TYPE_ADS)
                    ->where('name', self::CATEGORY_BUMP);
            });
        });
    }

    public function scopeCategoryHighlight($query)
    {
        return $query->whereHas('productAttribute', function ($query) {
            $query->whereHas('product', function ($query) {
                $query->filterCategory(ProductCategory::TYPE_ADS)
                    ->where('name', self::CATEGORY_HIGHLIGHT);
            });
        });
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $current_boosting_date = $this->boosted_at->toDateString();
        $today = today()->toDateString();

        if ($current_boosting_date == $today) {
            $index = 'boosting';
        } elseif ($current_boosting_date < $today) {
            $index = 'expired';
        } else {
            $index = 'incoming';
        }

        $status = Status::instance()->statusLabel($index);

        return '<span class="px-3 ' . $status['class'] . '">' . $status['text'] . '</span>';
    }
}
