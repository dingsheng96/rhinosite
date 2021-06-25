<?php

namespace App\Support\Services;

use App\Models\Rating;

class RatingService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Rating::class);
    }

    public function calcAverage()
    {
        //
    }
}
