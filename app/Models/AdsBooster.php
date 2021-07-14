<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsBooster extends Model
{
    use SoftDeletes;

    protected $table = 'ads_boosters';

    protected $fillable = [
        'product_attribute_id', 'boostable_type', 'boostable_id', 'boosted_at'
    ];

    protected $casts = [
        'boosted_at' => 'datetime'
    ];

    // Relationships
    public function boostable()
    {
        return $this->morphTo();
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id', 'id');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $current_boosting_date = $this->boosted_at->toDateString();
        $today = today()->toDateString();

        if ($current_boosting_date == $today) {
            $index = 'boosting';
        } elseif ($current_boosting_date < $today) {
            $index = 'expired';
        } else {
            $index = 'incoming';
        }

        $status = Status::instance()->statusLabel($index);

        return '<span class="px-3 ' . $status['class'] . '">' . $status['text'] . '</span>';
    }
}
