<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdsQuota extends Model
{
    use SoftDeletes;

    protected $table = 'user_ads_quotas';

    protected $fillable = [
        'user_id', 'product_attribute_id', 'quantity'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userAdsQuotaHistories()
    {
        return $this->hasMany(UserAdsQuotaHistory::class, 'user_ads_quota_id', 'id');
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductAttribute::class, 'id', 'id', 'product_attribute_id', 'product_id');
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id', 'id');
    }
}
