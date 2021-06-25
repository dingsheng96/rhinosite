<?php

namespace App\Observers;

use App\Models\CountryState;

class CountryStateObserver
{
    /**
     * Handle the country state "created" event.
     *
     * @param  \App\Models\CountryState  $countryState
     * @return void
     */
    public function created(CountryState $countryState)
    {
        //
    }

    /**
     * Handle the country state "updated" event.
     *
     * @param  \App\Models\CountryState  $countryState
     * @return void
     */
    public function updated(CountryState $countryState)
    {
        //
    }

    /**
     * Handle the country state "deleted" event.
     *
     * @param  \App\Models\CountryState  $countryState
     * @return void
     */
    public function deleted(CountryState $countryState)
    {
        $countryState->cities()->delete();
    }

    /**
     * Handle the country state "restored" event.
     *
     * @param  \App\Models\CountryState  $countryState
     * @return void
     */
    public function restored(CountryState $countryState)
    {
        //
    }

    /**
     * Handle the country state "force deleted" event.
     *
     * @param  \App\Models\CountryState  $countryState
     * @return void
     */
    public function forceDeleted(CountryState $countryState)
    {
        //
    }
}
