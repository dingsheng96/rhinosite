<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ProductFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'productFacade';
    }
}
