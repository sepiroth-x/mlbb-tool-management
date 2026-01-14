<?php

use Illuminate\Support\Facades\Route;
use Modules\MLBBToolManagement\Http\Controllers\MatchupController;
use Modules\MLBBToolManagement\Http\Controllers\OverlayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::prefix('mlbb')->name('mlbb.')->group(function() {
    
    // Team Matchup Probability Tool Routes
    Route::prefix('matchup')->name('matchup.')->group(function() {
        Route::get('/', [MatchupController::class, 'index'])->name('index');
        Route::post('/analyze', [MatchupController::class, 'analyze'])->name('analyze');
        Route::get('/heroes', [MatchupController::class, 'getHeroes'])->name('heroes');
    });
    
    // Live Pick/Ban Overlay Routes
    Route::prefix('overlay')->name('overlay.')->group(function() {
        
        // Admin Panel Routes (protected by auth middleware)
        Route::middleware(['auth'])->group(function() {
            Route::get('/admin', [OverlayController::class, 'admin'])->name('admin');
            Route::post('/match/create', [OverlayController::class, 'createMatch'])->name('match.create');
            Route::post('/match/{matchId}/select', [OverlayController::class, 'selectMatch'])->name('match.select');
            Route::post('/match/{matchId}/pick', [OverlayController::class, 'pickHero'])->name('match.pick');
            Route::post('/match/{matchId}/ban', [OverlayController::class, 'banHero'])->name('match.ban');
            Route::post('/match/{matchId}/undo', [OverlayController::class, 'undoAction'])->name('match.undo');
            Route::post('/match/{matchId}/reset', [OverlayController::class, 'resetMatch'])->name('match.reset');
            Route::post('/match/{matchId}/phase', [OverlayController::class, 'setPhase'])->name('match.phase');
            Route::get('/matches', [OverlayController::class, 'getMatches'])->name('matches');
        });
        
        // Public Overlay Display (no auth required - for OBS)
        Route::get('/display/{matchId}', [OverlayController::class, 'display'])->name('display');
        
        // API endpoint for real-time updates
        Route::get('/match/{matchId}/state', [OverlayController::class, 'getMatchState'])->name('match.state');
    });
});
