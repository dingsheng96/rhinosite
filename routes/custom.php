<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::prefix('command')->group(function () {

    Route::get('cache-clear', function () {

        Artisan::call('optimize:clear');
        return 'Done clear cache';
    });
});
