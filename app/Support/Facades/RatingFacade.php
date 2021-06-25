<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class RatingFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ratingFacade';
    }
}
