<?php

use App\Http\Controllers\Api\WorkoutSessionController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['prefix' => 'workout-sessions'], function () {
        Route::get('/', [WorkoutSessionController::class, 'index']);
        Route::post('/', [WorkoutSessionController::class, 'store']);
    });
});
