<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class CountryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'countryFacade';
    }
}
