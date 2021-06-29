<?php

namespace App\Http\View\Composers;

use App\Models\Unit;
use Illuminate\View\View;

class UnitComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  UnitRepository $units
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
        $view->with('units', Unit::orderBy('display', 'asc')->get());
    }
}
