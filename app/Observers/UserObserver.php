<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->userDetail()->delete();
        $user->address()->delete();
        $user->media()->delete();
        $user->projects()->delete();
        $user->wishlists()->delete();
        $user->carts()->delete();
        $user->userSubscriptions()->delete();
        $user->ratings()->delete();
        $user->orders()->delete();
        // $user->userAdsQuotas()->delete();
        // $user->userAdsQuotaHistories()->delete();
        $user->ratedBy()->delete();
        $user->comparisons()->detach();
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
