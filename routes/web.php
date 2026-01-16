<?php

use Illuminate\Support\Facades\Route;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\EditorController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\UnclaimedDepositController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\PincodeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ViewPageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auctions', [ViewPageController::class, 'auctions']);
Route::get('/unclaimed-deposit', [ViewPageController::class, 'unclaimedDeposit']);
// Route::post('/auctions/filter', [ViewPageController::class, 'filterAuctions']);



Route::get('/publish-module-assets/{module}', function ($moduleName) {
    try {
        $module = Module::find($moduleName);

        if (!$module) {
            return response()->json([
                'status' => 'error',
                'message' => "Module '{$moduleName}' not found."
            ], 404);
        }

        Artisan::call('module:publish', [
            'module' => $moduleName
        ]);

        $output = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => "Module '{$moduleName}' assets published successfully!",
            'output' => $output
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});

Route::get('/clear-all', function () {
    $secret = request()->query('secret');
    if ($secret !== env('CACHE_CLEAR_SECRET')) {
        abort(403, 'Unauthorized access.');
    }

    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');

    return response()->json([
        'status' => 'success',
        'message' => 'Config, cache, and route cleared successfully!',
    ]);
});


Route::post('/editor/upload', [EditorController::class, 'upload'])->name('editor-upload');

Route::get('/cms-admin', function () {
    if (auth()->check()) {
        return redirect('/cms-admin/dashboard');
    }

    return app(AuthenticatedSessionController::class)->create(request());
})->name('login');


Route::prefix('cms-admin')->group(function () {

    // -----------------------
    // Register
    // -----------------------
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware('guest')
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest');

    // -----------------------
    // Login
    // -----------------------

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest');

    // -----------------------
    // Forgot Password
    // -----------------------
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    // -----------------------
    // Reset Password
    // -----------------------
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.update');

    // -----------------------
    // Email Verification
    // -----------------------
    Route::get('/email/verify', [VerifyEmailController::class, 'show'])
        ->middleware('auth')
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['auth', 'signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    // -----------------------
    // Logout
    // -----------------------
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

});

Route::prefix('cms-admin')->middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('cms.dashboard');
    });

    Route::get('/profile', function () {
        return view('cms.profile',[
            'user' => auth()->user()
        ]);
    });

    Route::patch('/update-profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/update-password', [ProfileController::class, 'updatePassword'])
    ->name('cmsadmin.update.password');

    Route::delete('cities/bulk-delete-items', [CityController::class, 'bulkDelete'])->name('cityBulk');
    Route::delete('states/bulk-delete-items', [StateController::class, 'bulkDelete'])->name('stateBulk');
    Route::delete('auctions/bulk-delete-items', [AuctionController::class, 'bulkDelete'])->name('auctionBulk');
    Route::resource('pincodes', PincodeController::class)->names(names: 'pincodes');
    Route::resource('towns', TownController::class)->names(names: 'towns');
    Route::resource('cities', CityController::class)->names('cities');
    Route::resource('states', StateController::class)->names('states');
    Route::get('/location/children', [LocationController::class, 'children']);
    Route::resource('auctions', AuctionController::class)->names('auctions');
    Route::resource('unclaimed-deposit', UnclaimedDepositController::class)->names(names: 'unclaimed-deposit');

});
