<?php

use Illuminate\Support\Facades\Route;
use Modules\Auction\Http\Controllers\AuctionController;

Route::middleware(['auth', 'permission:auction.access'])->group(function () {
    Route::prefix('cms-admin')->group(function () {
         Route::delete('auctions/bulk-delete-items', [AuctionController::class, 'bulkDelete'])->name('auctionBulk');
        Route::resource('auctions', AuctionController::class)->names('auction');
    });
});
