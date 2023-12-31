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
    public function getRenewedDateAttribute()
    {
        return optional($this->renewed_at)->format('jS M Y');
    }

    public function getExpiredDateAttribute()
    {
        return optional($this->expired_at)->format('jS M Y');
    }
}
