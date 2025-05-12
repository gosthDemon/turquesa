<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, "login"])->name('auth.login');
        Route::post('/logout', [AuthController::class, "logout"])->name('auth.logout');
        Route::post('/refresh', [AuthController::class, "refresh"])->name('auth.refresh');
    });
});