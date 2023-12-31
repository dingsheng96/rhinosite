<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Price;
use App\Helpers\Status;
use App\Models\PackageItem;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use App\Observers\PackageObserver;
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

    protected $fillable = [
        'name', 'description', 'stock_type', 'stock_quantity', 'quantity', 'status', 'recurring', 'published'
    ];

    // Functions
    protected static function boot()
    {
        parent::boot();

        self::observe(PackageObserver::class);
    }

    // Relationships
    public function products()
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            PackageItem::class,
            'package_id',
            'product_attribute_id',
            'id',
            'id'
        )->withPivot('quantity');
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'cartable');
    }

    public function userSubscriptions()
    {
        return $this->morphMany(UserSubscription::class, 'subscribable');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getPriceAttribute()
    {
        return $this->prices()->defaultPrice()->first();
    }

    public function getSellingPriceWithCurrencyAttribute()
    {
        return $this->price->currency->code . $this->price->selling_price;
    }

    public function getItemNameAttribute()
    {
        return $this->name;
    }
}
