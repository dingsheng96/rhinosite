<?php

namespace App\Models;

use App\Helpers\Status;
use App\Models\Settings\Role\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, HasRoles, Notifiable;

    const STATUS_ACTIVE     =   'active';
    const STATUS_INACTIVE   =   'inactive';

    protected $table = 'users';

    protected $fillable = [
        'name', 'phones', 'email', 'password',
        'remember_token', 'status', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function userDetails()
    {
        return $this->hasMany(UserDetails::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, UserCategory::class, 'user_id', 'category_id', 'id', 'id');
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_SUPER_ADMIN);
        });
    }

    public function scopeMerchant($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_MERCHANT);
        });
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

    public function getRoleNameAttribute()
    {
        return $this->getRoleNames()->first();
    }

    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<h5><span class="' . $label['class'] . '">' . $label['text'] . '</span></h5>';
    }

    public function getProfileImageAttribute()
    {
        return $this->media()
            ->where('type', Media::TYPE_PROFILE)
            ->first();
    }

    public function getFolderNameAttribute()
    {
        $folders = [
            Role::ROLE_SUPER_ADMIN => 'admin',
            Role::ROLE_MERCHANT => 'merchant'
        ];

        return $folders[$this->roles()->first()->name];
    }

    public function getIsAdminAttribute()
    {
        return $this->role_name == Role::ROLE_SUPER_ADMIN;
    }

    public function getIsMerchantAttribute()
    {
        return $this->role_name == Role::ROLE_MERCHANT;
    }

    public function getUserCategoryAttribute()
    {
        return $this->category()->first();
    }
}
