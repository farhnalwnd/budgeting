<?php

use App\Http\Controllers\LockScreenController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('locked', [LockScreenController::class, 'show'])
        ->name('locked');

    Route::post('locked', [LockScreenController::class, 'store']);
});
