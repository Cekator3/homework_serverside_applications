<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangeLogsController;
use App\Http\Middleware\EnsureUserHasPermission;

Route::controller(ChangeLogsController::class)
    ->prefix('/change-logs')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::middleware(EnsureUserHasPermission::class . ':import-change-log')
            ->post('/import', 'import');

        Route::middleware(EnsureUserHasPermission::class . ':export-change-log')
            ->get('/export', 'export');
    });
