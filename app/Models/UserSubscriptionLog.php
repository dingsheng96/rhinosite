<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscriptionLog extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';

    protected $table = 'user_subscription_logs';

    protected $fillable = [
        'user_subscription_id', 'renewed_at', 'expired_at', 'status'
    ];

    protected $casts = [
        'renewed_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    // Relationships
    public function userSubscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
