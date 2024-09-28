<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureNotAuthenticated;

Route::controller(AuthController::class)
    ->prefix('/auth')
    ->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/change-password', 'changePassword');
            Route::post('/logout', 'logout');
            Route::get('/me', 'getUserInfo');
            Route::get('/tokens', 'getUserTokens');
            Route::post('/expire-all-tokens', 'expireAllUserTokens');
        });
        Route::middleware(EnsureNotAuthenticated::class)->group(function () {
            Route::post('/login', 'login')->name("login");
            Route::post('/register', 'register');
        });
    });
