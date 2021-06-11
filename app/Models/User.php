<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, HasRoles;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'mobile_no', 'tel_no', 'reg_no',
        'password', 'remember_token', 'status', 'registration_id', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id');
    }

    public function profileImage()
    {
        return $this->morphOne(Media::class, 'sourceable');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    // Attributes
    public function getFullAddressAttribute()
    {
        $address = $this->address()->first();

        $full_address = '';

        if ($address) {
            $full_address  .=   ($address->address_1 . ', ') ?? '';
            $full_address  .=   ($address->address_2 . ', ') ?? '';
            $full_address  .=   ($address->postcode . ', ') ?? '';
            $full_address  .=   ($address->city->name . ', ') ?? '';
            $full_address  .=   ($address->city->country_state_name . ', ') ?? '';
            $full_address  .=   ($address->city->country_name . ', ') ?? '';
        }

        return $full_address;
    }
}
