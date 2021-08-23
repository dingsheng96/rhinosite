<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Address;
use App\Models\Country;
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
        'user_id', 'service_id', 'business_since', 'website', 'facebook', 'whatsapp',
        'pic_name', 'pic_phone', 'pic_email', 'validated_by', 'status', 'validated_at'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'business_since' => 'date'
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
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    // Scopes
    public function scopePendingDetails($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApprovedDetails($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejectedDetails($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Attributes
    public function setPicPhoneAttribute($value)
    {
        $this->attributes['pic_phone'] = Misc::instance()->stripTagsAndAddCountryCodeToPhone($value);
    }

    public function setWhatsappAttribute($value)
    {
        if (!empty($value)) {
            $value = Misc::instance()->stripTagsAndAddCountryCodeToPhone($value);
        }

        $this->attributes['whatsapp'] = $value;
    }

    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getYearsOfExperienceAttribute()
    {
        return now()->diffInYears($this->business_since);
    }

    public function getBusinessSinceAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getFormattedPicPhoneAttribute()
    {
        if (empty($this->pic_phone)) {
            return null;
        }

        return Misc::instance()->addTagsToPhone($this->pic_phone);
    }

    public function getFormattedWhatsappAttribute()
    {
        if (empty($this->whatsapp)) {
            return null;
        }

        return Misc::instance()->addTagsToPhone($this->whatsapp);
    }
}
