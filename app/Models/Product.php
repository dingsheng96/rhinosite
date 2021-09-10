<?php

namespace App\Models;

use App\Models\Media;
use App\Helpers\Status;
use App\Models\UserAdsQuota;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const STORE_PATH = '/products';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const NAME_CATEGORY_BUMP = 'Category Bump';
    const NAME_CATEGORY_HIGHLIGHT = 'Category Highlight';
    const NAME_BANNER_ADVERTISEMENT = 'Banner Advertisement';

    const SLOT_TYPE_DAILY = 'daily';
    const SLOT_TYPE_WEEKLY = 'weekly';
    const SLOT_TYPE_MONTHLY = 'monthly';

    protected $table = 'products';

    protected $fillable = [
        'name', 'description', 'status', 'product_category_id', 'slot_type', 'total_slots'
    ];

    // Functions
    protected static function boot()
    {
        parent::boot();

        self::observe(ProductObserver::class);
    }

    // Relationships
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function userAdsQuotas()
    {
        return $this->hasMany(UserAdsQuota::class, 'product_id', 'id');
    }

    public function adsBoosters()
    {
        return $this->hasMany(AdsBooster::class, 'product_id', 'id');
    }

    // Scopes
    public function scopeFilterCategory($query, $keyword)
    {
        return $query->whereHas('productCategory', function ($query) use ($keyword) {
            $query->where('name', $keyword);
        });
    }

    public function scopeActive($query, bool $status = true)
    {
        $status = $status ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;

        return $query->where('status', $status);
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getThumbnailAttribute()
    {
        return $this->media()->thumbnail()->first();
    }

    public function getImagesAttribute()
    {
        return $this->media()->image()->get();
    }
}
