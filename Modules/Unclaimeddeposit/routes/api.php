<?php

use Illuminate\Support\Facades\Route;
use Modules\UnclaimedDeposit\Http\Controllers\UnclaimedDepositController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('unclaimeddeposits', UnclaimedDepositController::class)->names('unclaimeddeposit');
});
