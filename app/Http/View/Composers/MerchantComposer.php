<?php

namespace App\Http\View\Composers;

use App\Models\User;
use Illuminate\View\View;

class MerchantComposer
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
        $view->with('merchants', User::merchant()->orderBy('name', 'asc')->get());
    }
}
