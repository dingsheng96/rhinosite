<?php

namespace App\Http\View\Composers;

use App\Models\Media;
use Illuminate\View\View;

class DefaultPreviewComposer
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
        $view->with('default_preview', (new Media())->default_preview_image);
    }
}
