<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ProductAttributeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'productAttributeFacade';
    }
}
