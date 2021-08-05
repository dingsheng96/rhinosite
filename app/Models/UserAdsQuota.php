<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\UserAdsQuotaHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdsQuota extends Model
{
    use SoftDeletes;

    protected $table = 'user_ads_quotas';

    protected $fillable = [
        'user_id', 'product_id', 'quantity'
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
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
