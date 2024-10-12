<?php

use App\Http\Middleware\LogApiRequest;
use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ClearOldRequestsLogs;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: "",
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            LogApiRequest::class
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(ClearOldRequestsLogs::class)->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
