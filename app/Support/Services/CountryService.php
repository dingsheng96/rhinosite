<?php

namespace App\Support\Services;

use App\Models\Country;

class CountryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Country::class);
    }

    public function importCountryState($file)
    {
        //
    }
}
