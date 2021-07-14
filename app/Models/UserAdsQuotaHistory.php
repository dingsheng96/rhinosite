<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdsQuotaHistory extends Model
{
    use SoftDeletes;

    protected $table = 'user_ads_quotas';

    protected $fillable = [
        'user_ads_quota_id', 'initial_quantity', 'process_quantity', 'remaining_quantity',
        'sourceable_type', 'sourceable_id', 'applicable_type', 'applicable_id'
    ];

    // Relationships
    public function userAdsQuota()
    {
        return $this->belongsTo(UserAdsQuota::class, 'user_ads_quota_id', 'id');
    }

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function applicable()
    {
        return $this->morphTo();
    }
}
