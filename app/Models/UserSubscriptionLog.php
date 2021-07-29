<?php

namespace App\Models;

use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscriptionLog extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';

    protected $table = 'user_subscription_logs';

    protected $fillable = [
        'user_subscription_id', 'renewed_at', 'expired_at'
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

    public function user()
    {
        return $this->hasOneThrough(User::class, UserSubscription::class, 'id', 'id', 'user_subscription_id', 'user_id');
    }

    // Attributes
    public function setRenewedAtAttribute($value)
    {
        $this->attributes['renewed_at'] = $value->startOfDay();
    }

    public function setExpiredAtAttribute($value)
    {
        $this->attributes['expired_at'] = $value->endOfDay();
    }

    public function getRenewedAtAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getExpiredAtAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }
}
