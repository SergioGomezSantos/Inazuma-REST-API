<?php

use App\Http\Controllers\CoachController;
use App\Http\Controllers\EmblemController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TechniqueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function() {
    Route::apiResource('coaches', CoachController::class);
    Route::apiResource('emblems', EmblemController::class);
    Route::apiResource('formations', FormationController::class);
    Route::apiResource('players', PlayerController::class);
    Route::apiResource('stats', StatController::class);
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('techniques', TechniqueController::class);
});