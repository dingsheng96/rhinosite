<?php

namespace App\Models;

use Carbon\Carbon;
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
        'product_id', 'boost_index', 'boostable_type', 'boostable_id', 'boosted_at'
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
        return $query->whereDate('boosted_at', today()->toDateString());
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('boosted_at', '<', today()->toDateString());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('boosted_at', '>', today()->toDateString());
    }

    public function scopeCategoryBump($query)
    {
        return $query->whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_ADS)
                ->where('name', self::CATEGORY_BUMP);
        });
    }

    public function scopeCategoryHighlight($query)
    {
        return $query->whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_ADS)
                ->where('name', self::CATEGORY_HIGHLIGHT);
        });
    }

    public function scopeBannerAdvertisement($query)
    {
        return $query->whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_ADS)
                ->where('name', self::BANNER_ADVERTISEMENT);
        });
    }

    // Attributes
    public function setBoostedAtAttribute($value)
    {
        $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);

        $this->attributes['boosted_at'] = $value->startOfDay();
    }

    public function getBoostedAtAttribute($value)
    {
        return Carbon::parse($value)->startOfDay();
    }

    public function getStatusLabelAttribute()
    {
        if ($this->boosted_at->eq(today()->startOfDay())) {
            $index = 'boosting';
        } elseif ($this->boosted_at->gt(today()->startOfDay())) {
            $index = 'upcoming';
        } else {
            $index = 'expired';
        }

        $status = Status::instance()->statusLabel($index);

        return '<span class="px-3 ' . $status['class'] . '">' . $status['text'] . '</span>';
    }

    public function getBadgeColorAttribute()
    {
        switch ($this->product->name) {
            case Product::NAME_CATEGORY_BUMP:
                $color = 'bg-success';
                break;
            case Product::NAME_CATEGORY_BUMP:
                $color = 'bg-purple';
                break;
            case Product::NAME_CATEGORY_HIGHLIGHT:
                $color = 'bg-pink';
                break;
            default:
                $color = 'bg-primary';
                break;
        }

        return $color;
    }

    public function getBoostingDateRangeStatusLabelAttribute()
    {
        $today = today()->toDateString();

        $index = 'boosting';

        if ($today < $this->min_date) {
            $index = 'upcoming';
        } elseif ($today > $this->max_date) {
            $index = 'expired';
        }

        $status = Status::instance()->statusLabel($index);

        return '<span class="px-3 ' . $status['class'] . '">' . $status['text'] . '</span>';
    }

    public function getIsInBoostingDateRangeAttribute()
    {
        $today = today()->toDateString();

        if ($today >= $this->min_date && $today <= $this->max_date) {
            return true;
        }

        return false;
    }

    public function getIsInUpcomingBoostingDateRangeAttribute()
    {
        $today = today()->toDateString();

        if ($today < $this->min_date) {
            return true;
        }

        return false;
    }
}
