<?php

namespace App\Http\View\Composers;

use App\Models\Currency;
use Illuminate\View\View;

class CurrencyComposer
{
    private $currencies, $default_currency;

    /**
     * Create a new categories composer.
     *
     * @param  CurrencyRepository $currencies
     * @return void
     */
    public function __construct()
    {
        $this->currencies       =   Currency::orderBy('name', 'asc')->get();
        $this->default_currency =   Currency::defaultCountryCurrency()->first();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currencies', $this->currencies);
        $view->with('default_currency', $this->default_currency);
    }
}
