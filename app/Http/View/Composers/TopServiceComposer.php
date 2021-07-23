<?php

namespace App\Http\View\Composers;

use App\Models\User;
use App\Models\Service;
use Illuminate\View\View;
use App\Models\UserDetail;

class TopServiceComposer
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
        $top_services = Service::with(['projects.user'])
            ->whereHas('projects.user', function ($query) {
                // $query->sortMerchantByRating();
                $query->merchant()->active()->inRandomOrder();
            })->limit(6)->get();

        $view->with('top_services', $top_services);
    }
}
