<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class RegistrationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'registrationFacade';
    }
}
