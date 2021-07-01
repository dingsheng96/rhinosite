<?php

namespace App\Models;

use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STOCK_TYPE_INFINITE = 'infinite';
    const STOCK_TYPE_FINITE = 'finite';

    protected $table = 'packages';

    protected $fillable = ['name', 'description', 'stock_type', 'quantity', 'status'];

    // Relationships
    public function products()
    {
        return $this->morphToMany(ProductAttribute::class, 'packageable', PackageItem::class, 'package_id', 'packageable_id', 'id', 'id')
            ->withPivot('quantity');
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }
}
