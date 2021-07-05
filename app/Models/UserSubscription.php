<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscription extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id', 'package_id', 'status', 'auto_billing'
    ];

    // Relationships
    public function userSubscriptionLogs()
    {
        return $this->hasMany(UserSubscriptionLog::class, 'user_subscription_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Attributes
    public function getSubscriptionDateAttribute()
    {
        return $this->created_at->format('Sj M Y') ?? null;
    }

    public function getValidityInMonthAttribute()
    {
        return $this->validity / 30;
    }
}
