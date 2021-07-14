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

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAutoBilling($query)
    {
        return $query->where('auto_billing', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    // Attributes
    public function getSubscriptionDateAttribute()
    {
        return $this->created_at->format('jS M Y') ?? null;
    }

    public function getValidityInMonthAttribute()
    {
        return $this->validity / 30;
    }

    public function getActiveSubscriptionLogAttribute()
    {
        return $this->userSubscriptionLogs()->active()->first();
    }

    public function getExpiredDateAttribute()
    {
        return $this->active_subscription_log->expired_at->format('jS M Y');
    }
}
