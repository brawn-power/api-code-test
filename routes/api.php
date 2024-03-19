<?php

use App\Http\Controllers\Api\WorkoutProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// I have put all these into a route group.

Route::middleware(['auth:api'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/workout-sessions', [\App\Http\Controllers\Api\WorkoutSessionController::class, 'store']);

    Route::resource('/workout-progress', WorkoutProgressController::class)->only('index', 'show');

});
