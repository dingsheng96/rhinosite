<?php

namespace App\Models;

use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const MAX_IMAGES = 5;
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

    // Scopes
    public function scopeProductCategoryChecker($query, $name)
    {
        return $this->join(app(ProductCategory::class)->getTable(), $this->table . '.product_category_id', '=', app(ProductCategory::class)->getTable() . '.id')
            ->where(app(ProductCategory::class)->getTable() . '.name', $name);
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }
}
