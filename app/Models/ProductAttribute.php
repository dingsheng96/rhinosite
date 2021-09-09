<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Price;
use App\Helpers\Status;
use App\Models\Package;
use App\Models\Product;
use App\Models\PackageItem;
use App\Models\ProductCategory;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ProductAttributeObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;

    const STOCK_TYPE_INFINITE = 'infinite';
    const STOCK_TYPE_FINITE = 'finite';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const SLOT_TYPE_DAILY = 'daily';
    const SLOT_TYPE_WEEKLY = 'weekly';
    const SLOT_TYPE_MONTHLY = 'monthly';

    const VALIDITY_TYPE_DAY = 'day';
    const VALIDITY_TYPE_MONTH = 'month';
    const VALIDITY_TYPE_YEAR = 'year';

    protected $table = 'product_attributes';

    protected $fillable = [
        'product_id', 'sku', 'stock_type', 'stock_quantity', 'quantity', 'status', 'published',
        'validity_type', 'validity', 'recurring', 'trial_mode',
    ];

    // Functions
    protected static function boot()
    {
        parent::boot();

        self::observe(ProductAttributeObserver::class);
    }

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
        return $this->belongsToMany(Package::class, PackageItem::class, 'product_attribute_id', 'package_id', 'id', 'id')->withPivot('quantity');
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'cartable');
    }

    public function productCategory()
    {
        return $this->hasOneThrough(ProductCategory::class, Product::class, 'id', 'id', 'product_id', 'product_category_id');
    }

    public function userSubscriptions()
    {
        return $this->morphMany(UserSubscription::class, 'subscribable');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeRecurring($query, bool $status = true)
    {
        return $query->where('recurring', $status);
    }

    public function scopeTrialMode($query, bool $status = true)
    {
        return $query->where('trial_mode', $status);
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

    public function getSlotWithTypeAttribute()
    {
        $postfix = [
            self::SLOT_TYPE_DAILY => __('labels.per_day'),
            self::SLOT_TYPE_WEEKLY => __('labels.per_week'),
            self::SLOT_TYPE_MONTHLY => __('labels.per_month'),
        ];

        if (!empty($this->slot) && !empty($this->slot_type)) {

            return $this->slot . ' ' . $postfix[$this->slot_type];
        }

        return;
    }

    public function getNameAttribute()
    {
        if ($this->trial_mode) {
            return $this->product->name . ' (' . __('labels.free_trial') . ')';
        }

        return $this->product->name;
    }
}
