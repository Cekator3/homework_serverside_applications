<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Middleware\EnsureUserHasPermission;

Route::controller(RolesController::class)
    ->prefix('/policy/roles')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::middleware(EnsureUserHasPermission::class . ':get-list-role')
            ->get('/', 'getRoles');

        Route::middleware(EnsureUserHasPermission::class . ':read-role')
            ->get('/{id}', 'getRole');

        Route::middleware(EnsureUserHasPermission::class . ':create-role')
            ->post('/', 'createRole');

        Route::middleware(EnsureUserHasPermission::class . ':update-role')
            ->put('/{id}', 'updateRole');

        Route::middleware(EnsureUserHasPermission::class . ':delete-role')
            ->delete('/{id}', 'hardDeleteRole');

        Route::middleware(EnsureUserHasPermission::class . ':delete-role')
            ->delete('/{id}/soft', 'softDeleteRole');

        Route::middleware(EnsureUserHasPermission::class . ':restore-role')
            ->post('/{id}/restore', 'restoreSoftDeletedRole');

        Route::middleware(EnsureUserHasPermission::class . ':update-role')
            ->get('/{id}/permissions', 'getRolePermissions');
        Route::middleware(EnsureUserHasPermission::class . ':update-role')
            ->post('/{id}/permissions/{permission_id}', 'addRolePermission');
        Route::middleware(EnsureUserHasPermission::class . ':update-role')
            ->delete('/{id}/permissions/{permission_id}', 'hardDeleteRolePermission');
        Route::middleware(EnsureUserHasPermission::class . ':update-role')
            ->delete('/{id}/permissions/{permission_id}/soft', 'softDeleteRolePermission');
        Route::middleware(EnsureUserHasPermission::class . ':update-role')
            ->post('/{id}/permissions/{permission_id}/restore', 'restoreSoftDeletedRolePermission');

        Route::middleware(EnsureUserHasPermission::class . ':get-story-role')
            ->get('/{id}/story', 'getRoleChangeLogs');
    });
