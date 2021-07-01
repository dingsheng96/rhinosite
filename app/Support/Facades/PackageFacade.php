<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class PackageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'packageFacade';
    }
}
