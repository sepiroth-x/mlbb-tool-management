<?php

use Illuminate\Support\Facades\Route;
use Modules\MLBBToolManagement\Http\Controllers\MatchupController;
use Modules\MLBBToolManagement\Http\Controllers\OverlayController;
use Modules\MLBBToolManagement\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Global login route alias (required by Laravel auth middleware)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Authentication Routes (for MLBB Tournament Theme)
Route::prefix('mlbb')->name('mlbb.')->group(function() {
    
    // User Dashboard (protected)
    Route::middleware(['auth'])->group(function() {
        Route::get('/dashboard', function() {
            return view('mlbb-tool-management-theme::pages.dashboard');
        })->name('dashboard');
    });
    
    Route::prefix('auth')->name('auth.')->group(function() {
        Route::middleware(['guest'])->group(function() {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
            Route::post('/login', [AuthController::class, 'login']);
            Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
            Route::post('/register', [AuthController::class, 'register']);
        });
        
        Route::middleware(['auth'])->group(function() {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });
});

Route::prefix('mlbb')->name('mlbb.')->group(function() {
    
    // Team Matchup Probability Tool Routes
    Route::prefix('matchup')->name('matchup.')->group(function() {
        Route::get('/', [MatchupController::class, 'index'])->name('index');
        Route::post('/analyze', [MatchupController::class, 'analyze'])->name('analyze');
        Route::get('/heroes', [MatchupController::class, 'getHeroes'])->name('heroes');
        Route::post('/chat', [MatchupController::class, 'chat'])->name('chat');
        
        // Statistics page
        Route::get('/statistics', function() {
            return view('mlbb-tool-management::matchup.statistics');
        })->name('statistics');
        
        // Statistics API endpoints
        Route::get('/statistics/dashboard', [MatchupController::class, 'getStatisticsDashboard'])->name('statistics.dashboard');
        Route::get('/statistics/top-lineups', [MatchupController::class, 'getTopLineups'])->name('statistics.top-lineups');
        Route::get('/statistics/lineup-details', [MatchupController::class, 'getLineupDetails'])->name('statistics.lineup-details');
        Route::get('/statistics/hero/{slug}', [MatchupController::class, 'getHeroStatistics'])->name('statistics.hero');
    });
    
    // Live Pick/Ban Overlay Routes
    Route::prefix('overlay')->name('overlay.')->group(function() {
        
        // Redirect base overlay route to user dashboard or registration
        Route::get('/', function() {
            if (auth()->check()) {
                return redirect()->route('mlbb.dashboard');
            }
            return redirect()->route('mlbb.auth.register');
        });
        
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
