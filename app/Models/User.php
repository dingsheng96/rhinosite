<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Role;
use App\Helpers\Misc;
use App\Models\Media;
use App\Models\Order;
use App\Helpers\Status;
use App\Models\Address;
use App\Models\Project;
use App\Models\Service;
use App\Models\Comparable;
use App\Models\Favourable;
use App\Models\UserDetail;
use App\Models\UserAdsQuota;
use App\Models\UserSubscription;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use App\Models\UserAdsQuotaHistory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name', 'type', 'phone', 'email', 'password',
        'remember_token', 'status', 'last_login_at', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime'
    ];

    protected $guard_name = 'web';

    // Constants
    const STATUS_ACTIVE     =   'active';
    const STATUS_INACTIVE   =   'inactive';
    const STORE_PATH        =   '/users';

    const TYPE_ADMIN    = 'admin';
    const TYPE_MERCHANT = 'merchant';
    const TYPE_MEMBER   = 'member';

    // Relationships
    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    public function service()
    {
        return $this->hasOneThrough(Service::class, UserDetail::class, 'user_id', 'id', 'id', 'service_id');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function favouriteProjects()
    {
        return $this->morphedByMany(Project::class, 'favourable', Favourable::class)->withPivot('created_at');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function userAdsQuotas()
    {
        return $this->hasMany(UserAdsQuota::class, 'user_id', 'id');
    }

    public function userAdsQuotaHistories()
    {
        return $this->hasManyThrough(UserAdsQuotaHistory::class, UserAdsQuota::class, 'user_id', 'user_ads_quota_id', 'id', 'id');
    }

    public function ratings()
    {
        return $this->morphToMany(self::class, 'rateable', Rateable::class)->withPivot('scale')->withTimestamps();
    }

    public function ratedBy()
    {
        return $this->morphedByMany(self::class, 'rateable', Rateable::class)->withPivot('scale')->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    public function comparisons()
    {
        return $this->morphedByMany(Project::class, 'comparable', Comparable::class);
    }

    // Functions
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    public function scopeMerchant($query)
    {
        return $query->where('type', self::TYPE_MERCHANT);
    }

    public function scopeMember($query)
    {
        return $query->where('type', self::TYPE_MEMBER);
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.status', self::STATUS_ACTIVE);
    }

    public function scopeHasAdsQuota($query)
    {
        return $query->whereHas('userAdsQuotas', function ($query) {
            $query->where('quantity', '!=', 0);
        });
    }

    public function scopeFilterMerchantByRating($query, $value)
    {
        $tbl_user   =   $this->getTable();
        $tbl_rating =   app(Rateable::class)->getTable();

        return $query->select($tbl_user . '.id', DB::raw('AVG(' . $tbl_rating . '.scale) AS ratings'))
            ->join($tbl_rating, $tbl_user . '.id', '=', $tbl_rating . '.rateable_id')
            ->where($tbl_rating . '.rateable_type', self::class)
            ->groupBy($tbl_user . '.id')
            ->having('ratings', 'like', "{$value}%");
    }

    public function scopeSortMerchantByRating($query)
    {
        $tbl_users      =   $this->getTable();
        $tbl_ratings    =   app(Rateable::class)->getTable();

        return $query->select($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.status', DB::raw('AVG(' . $tbl_ratings . '.scale) AS ratings'))
            ->join($tbl_ratings, $tbl_users . '.id', '=', $tbl_ratings . '.rateable_id')
            ->where($tbl_ratings . '.rateable_type', self::class)
            ->active()
            ->merchant()
            ->groupBy($tbl_users . '.id', $tbl_users . '.name', $tbl_users . '.status')
            ->having('ratings', '>', 0)
            ->orderByDesc('ratings');
    }

    public function scopeWithActiveSubscription($query)
    {
        return $query->whereHas('userSubscriptions', function ($query) {
            $query->active();
        });
    }

    public function scopeWithApprovedDetails($query)
    {
        return $query->whereHas('userDetail', function ($query) {
            $query->approvedDetails();
        });
    }

    // Attributes
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = Misc::instance()->stripTagsAndAddCountryCodeToPhone($value);
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

    public function getLogoAttribute()
    {
        return $this->media()->logo()->first();
    }

    public function getFolderNameAttribute()
    {
        $folders = [
            Role::ROLE_SUPER_ADMIN => 'admin',
            Role::ROLE_MERCHANT => 'merchant',
            Role::ROLE_MEMBER => 'member'
        ];

        return $folders[$this->roles()->first()->name];
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->roles->first()->name == Role::ROLE_SUPER_ADMIN;
    }

    public function getIsAdminAttribute()
    {
        return $this->type == self::TYPE_ADMIN;
    }

    public function getIsMerchantAttribute()
    {
        return $this->type == self::TYPE_MERCHANT;
    }

    public function getIsMemberAttribute()
    {
        return $this->type == self::TYPE_MEMBER;
    }

    public function getCartItemsCountAttribute()
    {
        return $this->carts->count() ?? 0;
    }

    public function getFormattedPhoneNumberAttribute()
    {
        if (empty($this->phone)) {
            return null;
        }

        return Misc::instance()->addTagsToPhone($this->phone);
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getRatingAttribute(): int
    {
        return round($this->ratings()->avg('scale'));
    }

    public function getJoinedDateAttribute()
    {
        return $this->created_at->format('jS M Y');
    }

    public function getRatingStarsAttribute()
    {
        $total_stars    =   null;
        $max_stars      =   5;
        $rating         =   floor($this->rating);

        for ($i = 0; $i < $rating; $i++) {
            $total_stars .= '<i class="fas fa-star star"></i>';
        }

        for ($y = 0; $y < $max_stars - $rating; $y++) {
            $total_stars .= '<i class="far fa-star star"></i>';
        }

        return $total_stars;
    }

    public function getProjectServicesAttribute()
    {
        $services = Service::whereHas('projects', function ($query) {
            $query->where('user_id', $this->id);
        })->get();

        return $services;
    }

    public function getMinProjectPriceAttribute()
    {
        if ($this->projects->count() > 0) {
            $project = $this->projects
                ->sortBy(function ($item, $key) {
                    return $item->prices->first();
                })->first();

            return $project->prices->first()->currency->code . ' ' . $project->prices->first()->selling_price;
        }

        return;
    }

    public function getLocationWithCityStateAttribute()
    {
        if (!$this->address) {
            return null;
        }

        return $this->address->city->name . ', ' . $this->address->countryState->name;
    }

    public function getHasApprovedDetailsAttribute()
    {
        if ($this->userDetail()->approvedDetails()->exists()) {
            return true;
        }

        return false;
    }

    public function getHasPendingDetailsAttribute()
    {
        if ($this->userDetail()->pendingDetails()->exists()) {
            return true;
        }

        return false;
    }

    public function getProfileStatusLabelAttribute()
    {
        if ($this->has_approved_details) {
            return '<span class="badge badge-primary badge-pill px-3">' . __('labels.verified') . '</span>';
        }

        return '<span class="badge badge-danger badge-pill px-3">' . __('labels.unverified') . '</span>';
    }

    public function getActiveSubscriptionAttribute()
    {
        return $this->userSubscriptions()->active()->first();
    }

    public function getActiveSubscriptionLatestLogAttribute()
    {
        return $this->active_subscription
            ->userSubscriptionLogs()
            ->orderByDesc('renewed_at')
            ->first();
    }
}
