<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserPhotosController;

Route::controller(UserPhotosController::class)
    ->prefix('/users/{userId}/photos')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'list');
        Route::get('/archive', 'downloadArchive');
        Route::get('/{id}', 'download');
        Route::post('/{id}/set-as-avatar', 'setAsAvatar');
        Route::post('/', 'upload');
        Route::delete('/{id}', 'remove');
    });
