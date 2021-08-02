<?php

namespace App\Models;

use App\Models\Media;
use App\Helpers\Status;
use App\Models\UserAdsQuota;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const STORE_PATH = '/products';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $table = 'products';

    protected $fillable = [
        'name', 'description', 'status', 'product_category_id'
    ];

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

    public function userAdsQuota()
    {
        return $this->hasManyThrough(UserAdsQuota::class, ProductAttribute::class, 'product_id', 'product_attribute_id', 'id', 'id');
    }

    // Scopes
    public function scopeFilterCategory($query, $keyword)
    {
        return $query->whereHas('productCategory', function ($query) use ($keyword) {
            $query->where('name', $keyword);
        });
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
