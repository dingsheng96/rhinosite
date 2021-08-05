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
    const BANNER_ADVERTISEMENT = 'Banner Advertisement';

    protected $table = 'ads_boosters';

    protected $fillable = [
        'product_id', 'boostable_type', 'boostable_id', 'boosted_at'
    ];

    protected $casts = [
        'boosted_at' => 'datetime'
    ];

    // Relationships
    public function boostable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
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

    public function scopeBannerAdvertisement($query)
    {
        return $query->whereHas('productAttribute', function ($query) {
            $query->whereHas('product', function ($query) {
                $query->filterCategory(ProductCategory::TYPE_ADS)
                    ->where('name', self::BANNER_ADVERTISEMENT);
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
