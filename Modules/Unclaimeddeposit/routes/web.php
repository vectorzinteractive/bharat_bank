<?php

use Illuminate\Support\Facades\Route;
use Modules\UnclaimedDeposit\Http\Controllers\UnclaimedDepositController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('cms-admin')->group(function () {
        Route::delete('unclaimed-deposit/bulk-delete-items', [UnclaimedDepositController::class, 'bulkDelete'])->name('BulkDelete');
        Route::resource('unclaimed-deposit', UnclaimedDepositController::class)->names(names: 'unclaimed-deposit');
    });

});
