<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogsRequestsController;
use App\Http\Middleware\EnsureUserHasPermission;

Route::controller(LogsRequestsController::class)
    ->prefix('/ref/log/request')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::middleware(EnsureUserHasPermission::class . ':get-list-log-request')
            ->get('/', 'getLogsRequests');

        Route::middleware(EnsureUserHasPermission::class . ':read-log-request')
            ->get('/{id}', 'getLogRequest');

        Route::middleware(EnsureUserHasPermission::class . ':delete-log-request')
            ->delete('/{id}', 'hardDeleteLogRequest');
    });
