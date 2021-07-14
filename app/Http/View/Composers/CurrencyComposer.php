<?php

namespace App\Http\View\Composers;

use App\Models\Currency;
use Illuminate\View\View;

class CurrencyComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  CurrencyRepository $currencies
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
        $view->with('currencies', Currency::orderBy('name', 'asc')->get());
        $view->with('default_currency', Currency::defaultCountryCurrency()->first());
    }
}
