<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Rateable extends MorphPivot
{
    const MAX_RATING_SCALE = 5;

    protected $table = 'rateables';

    protected $fillable = [
        'user_id', 'rateable_type', 'rateable_id', 'scale', 'review', 'created_at'
    ];

    public function getRatingStarsAttribute()
    {
        $total_stars    =   null;
        $max_stars      =   self::MAX_RATING_SCALE;
        $rating         =   floor($this->scale);

        for ($i = 0; $i < $rating; $i++) {
            $total_stars .= '<i class="fas fa-star star"></i>';
        }

        for ($y = 0; $y < $max_stars - $rating; $y++) {
            $total_stars .= '<i class="far fa-star star"></i>';
        }

        return $total_stars;
    }
}
