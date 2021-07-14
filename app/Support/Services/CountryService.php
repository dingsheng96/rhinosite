<?php

namespace App\Support\Services;

use App\Models\Country;
use App\Support\Services\BaseService;

class CountryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Country::class);
    }
}
