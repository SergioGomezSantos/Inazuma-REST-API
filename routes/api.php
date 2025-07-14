<?php

use App\Http\Controllers\CoachController;
use App\Http\Controllers\EmblemController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TechniqueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers'
], function () {

    // Public Routes (GET)
    Route::get('coaches', [CoachController::class, 'index']);
    Route::get('coaches/{coach}', [CoachController::class, 'show']);
    
    Route::get('emblems', [EmblemController::class, 'index']);
    Route::get('emblems/{emblem}', [EmblemController::class, 'show']);
    
    Route::get('formations', [FormationController::class, 'index']);
    Route::get('formations/{formation}', [FormationController::class, 'show']);
    
    Route::get('players', [PlayerController::class, 'index']);
    Route::get('players/{player}', [PlayerController::class, 'show']);
    
    Route::get('stats', [StatController::class, 'index']);
    Route::get('stats/{stat}', [StatController::class, 'show']);
    
    Route::get('teams', [TeamController::class, 'index']);
    Route::get('teams/{team}', [TeamController::class, 'show']);
    
    Route::get('techniques', [TechniqueController::class, 'index']);
    Route::get('techniques/{technique}', [TechniqueController::class, 'show']);

    // Protected Routes
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::apiResources([
            'coaches' => CoachController::class,
            'emblems' => EmblemController::class,
            'formations' => FormationController::class,
            'players' => PlayerController::class,
            'stats' => StatController::class,
            'teams' => TeamController::class,
            'techniques' => TechniqueController::class,
        ], ['except' => ['index', 'show']]);
    });
});
