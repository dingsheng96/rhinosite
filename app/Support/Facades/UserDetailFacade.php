<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class UserDetailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'userDetailFacade';
    }
}
