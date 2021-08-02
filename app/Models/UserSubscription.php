<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\Status;
use App\Models\ProductAttribute;
use App\Models\UserSubscriptionLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscription extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id', 'subscribable_type', 'subscribable_id',
        'status', 'next_billing_at', 'activated_at'
    ];

    protected $casts = [
        'next_billing_at' => 'datetime',
        'activated_at' => 'datetime'
    ];

    // Relationships
    public function userSubscriptionLogs()
    {
        return $this->hasMany(UserSubscriptionLog::class, 'user_subscription_id', 'id');
    }

    public function subscribable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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

    // Attributes
    public function setNextBillingAtAttribute($value)
    {
        $this->attributes['next_billing_at'] = $value->startOfDay();
    }

    public function getSubscriptionDateAttribute()
    {
        return $this->created_at->format('jS M Y') ?? null;
    }

    public function getNameAttribute()
    {
        if ($this->subscribable) {
            if ($this->subscribable_type == ProductAttribute::class) {

                return $this->subscribable->product->name;
            }

            return $this->subscribable->name;
        }

        return null;
    }

    public function getNextBillingAtAttribute($value)
    {
        if ($value) {
            return date('Y-m-d', strtotime($value));
        }

        return '-';
    }

    public function getExpiredAtAttribute()
    {
        if ($this->active_user_subscription_log) {
            return $this->active_user_subscription_log->expired_at->toDateString();
        }

        return '-';
    }

    public function getActivatedAtAttribute($value)
    {
        if ($value) {
            return date('Y-m-d', strtotime($value));
        }

        return '-';
    }

    public function getStatusLabelAttribute()
    {
        $label =  Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }
}
