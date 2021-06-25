<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Country;

class CountryComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  CountryRepository $countries
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('countries', Country::orderBy('name', 'asc')->get());
    }
}
