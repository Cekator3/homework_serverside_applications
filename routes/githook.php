<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHooksController;

Route::post('/hooks/git', [GitHooksController::class, 'updateProjectSourceCode']);
