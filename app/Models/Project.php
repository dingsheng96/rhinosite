<?php

namespace App\Models;

use App\Models\City;
use App\Models\Unit;
use App\Models\User;
use App\Models\Media;
use App\Models\Price;
use App\Helpers\Status;
use App\Models\Address;
use App\Models\Service;
use App\Models\Language;
use App\Models\AdsBooster;
use App\Models\Comparable;
use App\Models\Favourable;
use App\Models\Translation;
use App\Models\CountryState;
use App\Models\ProjectService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    const STORE_PATH        =   '/projects';
    const STATUS_PUBLISHED  =   'published';
    const STATUS_DRAFT      =   'draft';

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
        return $this->morphToMany(User::class, 'rateable', Rateable::class)->withPivot('scale')->withTimestamps();
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

    public function userComparisons()
    {
        return $this->morphToMany(User::class, 'comparable', Comparable::class);
    }

    public function wishlistedBy()
    {
        return $this->morphToMany(User::class, 'favourable', Favourable::class)->withPivot('created_at');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where($this->getTable() . '.status', self::STATUS_PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeSearchable($query, $keyword)
    {
        $keyword = str_replace('+', ' ', $keyword);

        return $query->when(!empty($keyword), function ($query) use ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->orWhereHas('translations', function ($query) use ($keyword) {
                    $query->where('value', 'like', "%{$keyword}%");
                })->orWhereHas('services', function ($query) use ($keyword) {
                    $query->where(app(Service::class)->getTable() . '.name', 'like', "%{$keyword}%");
                })->orWhereHas('user', function ($query) use ($keyword) {
                    $query->where(app(User::class)->getTable() . '.name', 'like', "%{$keyword}%");
                })->orWhereHas('address', function ($query) use ($keyword) {
                    $query->where(function ($query) use ($keyword) {
                        $query->orWhereHas('city', function ($query) use ($keyword) {
                            $query->where(app(City::class)->getTable() . '.name', 'like', "%{$keyword}%");
                        })->orWhereHas('countryState', function ($query) use ($keyword) {
                            $query->where(app(CountryState::class)->getTable() . '.name', 'like', "%{$keyword}%");
                        });
                    });
                });
            });
        });
    }

    public function scopeFilterable($query, $request)
    {
        $price      =   $request->get('price');
        $location   =   $request->get('location');
        $rating     =   $request->get('rating');

        return $query->when(!empty($price), function ($query) use ($price) {

            $range = explode(',', $price);

            $query->whereHas('prices', function ($query) use ($range) {
                $query->defaultPrice()->priceRange($range[0], $range[1]);
            });
        })->when(!empty($location), function ($query) use ($location) {
            $query->whereHas('address', function ($query) use ($location) {
                $query->whereHas('countryState', function ($query) use ($location) {

                    $location = explode(',', $location);

                    $query->whereIn(app(CountryState::class)->getTable() . '.id', $location);
                });
            });
        })->when(!empty($rating), function ($query) use ($rating) {
            $query->whereHas('user', function ($query) use ($rating) {
                $query->filterMerchantByRating($rating);
            });
        });
    }

    public function scopeSortByCategoryBump($query)
    {
        return $query->orderByDesc(
            AdsBooster::select('product_id')
                ->whereColumn('boostable_id', $this->getTable() . '.id')
                ->where('boostable_type', self::class)
                ->categoryBump()
                ->boosting()
                ->limit(1)
        );
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

    public function getPriceWithoutUnitAttribute()
    {
        $default_price = $this->prices()->defaultPrice()->first();

        $currency   =   $default_price->currency->code;
        $price      =   $default_price->unit_price;

        return $currency . ' ' . $price;
    }

    public function getLocationAttribute()
    {
        $country_state  =   $this->address->countryState->name ?? '';

        return $country_state;
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
        return $this->media()->thumbnail()->first();
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

    public function getHasActiveBumpAttribute()
    {
        return $this->adsBoosters()->boosting()->categoryBump()->exists();
    }

    public function getBoostingStatusAttribute()
    {
        if ($this->adsBoosters()->boosting()->exists()) {
            $index = 'boosting';
        } elseif ($this->adsBoosters()->upcoming()->exists()) {
            $index = 'upcoming';
        } else {
            $index = 'expired';
        }

        $status = Status::instance()->statusLabel($index);

        return '<span class="px-3 ' . $status['class'] . '">' . $status['text'] . '</span>';
    }
}
