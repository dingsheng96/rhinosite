<?php

namespace App\Models;

use App\Models\Role;
use App\Helpers\Status;
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
    const STORE_PATH        =   '/users';

    protected $table = 'users';

    protected $fillable = [
        'name', 'phones', 'email', 'password',
        'remember_token', 'status', 'last_login_at', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime'
    ];

    // Relationships
    public function userDetails()
    {
        return $this->hasMany(UserDetail::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, UserCategory::class, 'user_id', 'category_id', 'id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id', 'id');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id', 'id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id', 'id');
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

    public function scopeMember($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::ROLE_MEMBER);
        });
    }

    // Attributes
    public function getFullAddressAttribute()
    {
        if ($this->address) {
            $full_address  =    $this->address->address_1 . ', ';
            $full_address  .=   $this->address->address_2 . ', ';
            $full_address  .=   $this->address->postcode . ', ';
            $full_address  .=   $this->address->city->name . ', ';
            $full_address  .=   $this->address->city->country_state_name . ', ';
            $full_address  .=   $this->address->city->country_name;
        }

        return $full_address ?? null;
    }

    public function getRoleNameAttribute()
    {
        return $this->getRoleNames()->first();
    }

    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getProfileImageAttribute()
    {
        return $this->media()->profile_image()->first();
    }

    public function getLogoAttribute()
    {
        return $this->media()->logo()->first();
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
        return $this->categories()->first();
    }

    public function getCartItemCountAttribute()
    {
        return optional(optional($this->cart)->cartItems())->count() ?? 0;
    }

    public function getFormattedPhoneNumberAttribute()
    {
        $format = chunk_split($this->phone, 4, '-');

        return '+' . rtrim($format, '-');
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }
}
