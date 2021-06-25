<?php

namespace App\Models;

use App\Helpers\Misc;
use App\Helpers\Status;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    const STORE_PATH    = '/projects';
    const IMAGES_LIMIT  = 5;

    protected $table = 'projects';

    protected $fillable = [
        'title', 'description', 'user_id', 'services', 'materials',
        'currency_id', 'unit_price', 'unit_id', 'unit_value', 'published'
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

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'sourceable');
    }

    // Scopes
    public function scopePublished($query, bool $status = true)
    {
        return $query->where('published', $status);
    }

    // Attributes
    public function getPriceWithLabelAttribute()
    {
        $currency   =   $this->currency->code;
        $price      =   $this->price;
        $unit       =   $this->unit->display;
        $unit_value =   $this->unit_value;

        return $currency . $price . ' / ' . $unit_value . $unit;
    }

    public function getPriceAttribute()
    {
        return Misc::instance()->getPriceFromIntToFloat($this->unit_price ?? 0);
    }

    public function getLocationAttribute()
    {
        $city           =   $this->address->city->name ?? '';
        $country_state  =   $this->address->city->countryState->name ?? '';
        $country        =   $this->address->city->country->name ?? '';

        return $city . ', ' . $country_state . ', ' . $country;
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

    public function getPublishStatusLabelAttribute()
    {
        $published = $this->published;
        return $this->published;
        if (!$published) {
            return null;
        }

        return Status::instance()->statusLabel('publish')['published'];
    }

    public function getThumbnailAttribute()
    {
        return $this->media()
            ->where('type', Media::TYPE_THUMBNAIL)
            ->first();
    }
}
