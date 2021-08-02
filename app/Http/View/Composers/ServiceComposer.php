<?php

namespace App\Http\View\Composers;

use App\Models\Service;
use Illuminate\View\View;

class ServiceComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  TopServiceRepository $top_service
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
        $services = Service::withCount(['projects'])
            ->whereHas('projects')
            ->inRandomOrder()
            ->get();

        $view->with('services', $services);
    }
}
