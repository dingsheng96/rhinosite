<?php

namespace App\Http\View\Composers;

use App\Models\User;
use Illuminate\View\View;

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
        $view->with(
            'verifications_count',
            User::doesntHave('userDetail')
                ->with('userDetail')
                ->orWhereHas('userDetail', function ($query) {
                    $query->pendingDetails()
                        ->rejectedDetails();
                })->count()
        );
    }
}
