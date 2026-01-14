<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\MLBBToolManagement\Http\Controllers\Api\MatchupApiController;
use Modules\MLBBToolManagement\Http\Controllers\Api\OverlayApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

Route::prefix('mlbb')->name('api.mlbb.')->group(function() {
    
    // Matchup Analysis API
    Route::prefix('matchup')->group(function() {
        Route::get('/heroes', [MatchupApiController::class, 'getHeroes']);
        Route::post('/analyze', [MatchupApiController::class, 'analyze']);
    });
    
    // Overlay API
    Route::prefix('overlay')->group(function() {
        Route::get('/match/{matchId}', [OverlayApiController::class, 'getMatch']);
        Route::post('/match/{matchId}/sync', [OverlayApiController::class, 'syncMatch']);
    });
});
