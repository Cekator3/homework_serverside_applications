<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\EnsureUserHasPermission;

Route::controller(UsersController::class)
    ->prefix('/users')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::middleware(EnsureUserHasPermission::class . ':get-list-user')
            ->get('/', 'getUsers');

        Route::middleware(EnsureUserHasPermission::class . ':read-user')
            ->get('/{id}/roles', 'getUserRoles');
        Route::middleware(EnsureUserHasPermission::class . ':update-user')
            ->post('/{id}/roles/{role_id}', 'addUserRole');
        Route::middleware(EnsureUserHasPermission::class . ':update-user')
            ->delete('/{id}/roles/{role_id}', 'hardDeleteUserRole');
        Route::middleware(EnsureUserHasPermission::class . ':update-user')
            ->delete('/{id}/roles/{role_id}/soft', 'softDeleteUserRole');
        Route::middleware(EnsureUserHasPermission::class . ':update-user')
            ->post('/{id}/roles/{role_id}/restore', 'restoreSoftDeletedUserRole');

        Route::middleware(EnsureUserHasPermission::class . ':get-story-user')
            ->get('/{id}/story', 'getUserChangeLogs');

        Route::middleware(EnsureUserHasPermission::class . ':export-user')
            ->get('/export', 'export');

        Route::middleware(EnsureUserHasPermission::class . ':import-user')
            ->post('/import', 'import');
    });
