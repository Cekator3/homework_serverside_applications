<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserHasPermission;
use App\Http\Controllers\Reports\ApplicationUsageReportController;


Route::controller(ApplicationUsageReportController::class)
    ->prefix('/application-usage-reports')
    ->middleware([
        EnsureUserHasPermission::class . ':create-application-usage-report',
        'auth:sanctum'
    ])
    ->group(function () {
        Route::get('/', 'generateApplicationUsageReport');
    });
