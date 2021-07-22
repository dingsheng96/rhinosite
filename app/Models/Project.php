<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\User;
use App\Models\Media;
use App\Models\Price;
use App\Models\Rating;
use App\Helpers\Status;
use App\Models\Address;
use App\Models\Service;
use App\Models\Language;
use App\Models\AdsBooster;
use App\Models\Translation;
use App\Models\ProjectService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    const STORE_PATH        =   '/projects';
    const STATUS_PUBLISHED  =   'published';
    const STATUS_DRAFT      =   'draft';
    const MAX_IMAGES        =   5;

    protected $table = 'projects';

    protected $fillable = [
        'title', 'description', 'user_id', 'services', 'materials',
        'unit_id', 'unit_value', 'published'
    ];

    // Relationships
    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'sourceable');
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function adsBoosters()
    {
        return $this->morphMany(AdsBooster::class, 'boostable');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, ProjectService::class, 'project_id', 'service_id', 'id', 'id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    // Attributes
    public function getPriceWithUnitAttribute()
    {
        $default_price = $this->prices()->defaultPrice()->first();

        $currency   =   $default_price->currency->code;
        $price      =   $default_price->unit_price;
        $unit       =   $this->unit->display;
        $unit_value =   $this->unit_value;

        return $currency . $price . ' / ' . $unit_value . $unit;
    }

    public function getLocationAttribute()
    {
        $city           =   $this->address->city->name ?? '';
        $country_state  =   $this->address->countryState->name ?? '';

        return $city . ', ' . $country_state;
    }

    public function getEnglishTitleAttribute()
    {
        return $this->translations()
            ->whereHas('language', function ($query) {
                $query->where('code', Language::CODE_EN);
            })->first()
            ->value;
    }

    public function getChineseTitleAttribute()
    {
        return $this->translations()
            ->whereHas('language', function ($query) {
                $query->where('code', Language::CODE_CN);
            })->first()
            ->value;
    }

    public function getStatusLabelAttribute()
    {
        $label =  Status::instance()->statusLabel($this->status);

        return '<span class="' . $label['class'] . ' px-3">' . $label['text'] . '</span>';
    }

    public function getThumbnailAttribute()
    {
        return $this->media()
            ->where('type', Media::TYPE_THUMBNAIL)
            ->first();
    }

    public function getUnitValueAttribute($value)
    {
        return (float) $value;
    }

    public function getBoostingAttribute()
    {
        return $this->adsBoosters()->boosting()->count() > 0;
    }

    public function getHasActiveHighlightAttribute()
    {
        return $this->adsBoosters()->boosting()->categoryHighlight()->exists();
    }
}
