<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class PriceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'priceFacade';
    }
}
