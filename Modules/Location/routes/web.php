<?php

use Illuminate\Support\Facades\Route;
use Modules\Location\Http\Controllers\LocationController;
use Modules\Location\Http\Controllers\StateController;
use Modules\Location\Http\Controllers\CityController;
use Modules\Location\Http\Controllers\TownController;
use Modules\Location\Http\Controllers\PincodeController;

Route::middleware(['auth', 'permission:location.access'])->group(function () {
    Route::prefix('cms-admin')->group(function () {
        Route::delete('cities/bulk-delete-items', [CityController::class, 'bulkDelete'])->name('cityBulk');
        Route::delete('states/bulk-delete-items', [StateController::class, 'bulkDelete'])->name('stateBulk');
        Route::resource('pincodes', PincodeController::class)->names(names: 'pincodes');
        Route::resource('towns', TownController::class)->names(names: 'towns');
        Route::resource('cities', CityController::class)->names('cities');
        Route::resource('states', StateController::class)->names('states');
        Route::get('/location/children', [LocationController::class, 'children']);
        });
});
