<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\UserDetails;

class VerificationComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  VerificationRespository $verifications
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
        $view->with('verifications_count', UserDetails::pendingVerifications()->count());
    }
}
