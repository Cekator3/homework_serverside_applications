<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsGradesParserController;

Route::controller(StudentsGradesParserController::class)
    ->prefix('/utils/students/parse-grades')
    ->group(function () {
        Route::post('/', 'parse');
    });
