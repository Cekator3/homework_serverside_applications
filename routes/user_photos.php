<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserPhotosController;
use App\Http\Middleware\EnsureUserHasPermission;

Route::controller(UserPhotosController::class)
    ->prefix('/users/{userId}/photos')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'list');
        Route::get('/archive', 'downloadArchive');
        Route::middleware(EnsureUserHasPermission::class . ':download-user-photos-archive')
             ->get('/archive-all', 'downloadAllArchive');
        Route::get('/{id}', 'download');
        Route::post('/{id}/set-as-avatar', 'setAsAvatar');
        Route::post('/', 'upload');
        Route::delete('/{id}', 'remove');
    });
