<?php

namespace App\Observers;

use App\Models\Package;

class PackageObserver
{
    /**
     * Handle the package "created" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function created(Package $package)
    {
        //
    }

    /**
     * Handle the package "updated" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function updated(Package $package)
    {
        //
    }

    /**
     * Handle the package "deleted" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function deleted(Package $package)
    {
        $package->prices()->delete();
        $package->carts()->delete();
    }

    /**
     * Handle the package "restored" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function restored(Package $package)
    {
        //
    }

    /**
     * Handle the package "force deleted" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function forceDeleted(Package $package)
    {
        //
    }
}
