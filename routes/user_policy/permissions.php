<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionsController;
use App\Http\Middleware\EnsureUserHasPermission;

Route::controller(PermissionsController::class)
    ->prefix('/policy/permissions')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::middleware(EnsureUserHasPermission::class . ':get-list-permission')
            ->get('/', 'getPermissions');

        Route::middleware(EnsureUserHasPermission::class . ':read-permission')
            ->get('/{id}', 'getPermission');

        Route::middleware(EnsureUserHasPermission::class . ':create-permission')
            ->post('/', 'createPermission');

        Route::middleware(EnsureUserHasPermission::class . ':update-permission')
            ->put('/{id}', 'updatePermission');

        Route::middleware(EnsureUserHasPermission::class . ':delete-permission')
            ->delete('/{id}', 'hardDeletePermission');

        Route::middleware(EnsureUserHasPermission::class . ':delete-permission')
            ->delete('/{id}/soft', 'softDeletePermission');

        Route::middleware(EnsureUserHasPermission::class . ':restore-permission')
            ->post('/{id}/restore', 'restoreSoftDeletedPermission');
    });
