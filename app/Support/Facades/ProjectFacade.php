<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'projectFacade';
    }
}
