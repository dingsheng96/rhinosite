<?php

namespace App\Models;

use App\Models\Role;
use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes;

    const STATUS_PENDING    =   'pending';
    const STATUS_REJECTED   =   'rejected';
    const STATUS_APPROVED   =   'approved';

    protected $table = 'user_details';

    protected $fillable = [
        'user_id', 'industry_since', 'website', 'facebook', 'pic_name',
        'pic_phone', 'pic_email', 'validated_by', 'status', 'validated_at'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'industry_since' => 'date'
    ];

    // Relationships
    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_SUPER_ADMIN);
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_MERCHANT);
            });
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    // Scopes
    public function scopePendingVerifications($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApprovedDetails($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getYearsOfExperienceAttribute()
    {
        return now()->diffInYears($this->industry_since);
    }
}
