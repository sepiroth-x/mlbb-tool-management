<?php

use Illuminate\Support\Facades\Route;
use App\Models\Theme;
use App\Services\CMS\ThemeManager;

Route::get('/', function (ThemeManager $themeManager) {
    try {
        // Check if database is configured and accessible
        $pdo = \DB::connection()->getPdo();
        
        // Check if themes table exists (installation complete)
        $tablesExist = \DB::select("SHOW TABLES LIKE 'themes'");
        
        if (empty($tablesExist)) {
            // Installation not complete, redirect to installer
            if (file_exists(base_path('install.php'))) {
                return redirect('/install.php');
            }
            // Fallback to welcome page if installer deleted
            return view('welcome');
        }
        
        // Check if there's an active theme
        $activeTheme = Theme::where('is_active', true)->first();
        
        if ($activeTheme) {
            // Load the theme to register view namespaces
            $themeManager->loadTheme($activeTheme->slug);
            
            // Try to load theme's home page
            $themePath = base_path('themes/' . $activeTheme->slug);
            $homeViewPath = $themePath . '/pages/home.blade.php';
            
            if (file_exists($homeViewPath)) {
                // Theme has a home page, use it
                return view('theme.pages::home');
            }
        }
        
        // Fallback to default welcome page
        return view('welcome');
    } catch (\Exception $e) {
        // Database not configured yet, redirect to installer
        if (file_exists(base_path('install.php'))) {
            return redirect('/install.php');
        }
        // Fallback to welcome page if installer deleted
        return view('welcome');
    }
})->name('home');

// Theme-based static pages
Route::get('/features', function (ThemeManager $themeManager) {
    try {
        $activeTheme = Theme::where('is_active', true)->first();
        
        if ($activeTheme) {
            $themeManager->loadTheme($activeTheme->slug);
            $themePath = base_path('themes/' . $activeTheme->slug);
            $featuresViewPath = $themePath . '/pages/features.blade.php';
            
            if (file_exists($featuresViewPath)) {
                return view('theme.pages::features');
            }
        }
        
        return view('welcome')->with('message', 'Features page not available');
    } catch (\Exception $e) {
        return redirect('/');
    }
})->name('features');

Route::get('/about', function (ThemeManager $themeManager) {
    try {
        $activeTheme = Theme::where('is_active', true)->first();
        
        if ($activeTheme) {
            $themeManager->loadTheme($activeTheme->slug);
            $themePath = base_path('themes/' . $activeTheme->slug);
            $aboutViewPath = $themePath . '/pages/about.blade.php';
            
            if (file_exists($aboutViewPath)) {
                return view('theme.pages::about');
            }
        }
        
        return view('welcome')->with('message', 'About page not available');
    } catch (\Exception $e) {
        return redirect('/');
    }
})->name('about');

Route::get('/old-landing', function () {
    return view('landing');
})->name('old.landing');

// MLBB Module Routes - Added directly for shared hosting compatibility
Route::prefix('mlbb')->name('mlbb.')->group(function() {
    // Team Matchup Probability Tool Routes
    Route::prefix('matchup')->name('matchup.')->group(function() {
        Route::get('/', [\Modules\MLBBToolManagement\Http\Controllers\MatchupController::class, 'index'])->name('index');
        Route::post('/analyze', [\Modules\MLBBToolManagement\Http\Controllers\MatchupController::class, 'analyze'])->name('analyze');
        Route::get('/heroes', [\Modules\MLBBToolManagement\Http\Controllers\MatchupController::class, 'getHeroes'])->name('heroes');
    });
    
    // Live Pick/Ban Overlay Routes
    Route::prefix('overlay')->name('overlay.')->group(function() {
        // Admin Panel Routes (protected by auth middleware)
        Route::middleware(['auth'])->group(function() {
            Route::get('/admin', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'admin'])->name('admin');
            Route::post('/match/create', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'createMatch'])->name('match.create');
            Route::post('/match/{matchId}/select', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'selectMatch'])->name('match.select');
            Route::post('/match/{matchId}/pick', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'pickHero'])->name('match.pick');
            Route::post('/match/{matchId}/ban', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'banHero'])->name('match.ban');
            Route::post('/match/{matchId}/undo', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'undoAction'])->name('match.undo');
            Route::post('/match/{matchId}/reset', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'resetMatch'])->name('match.reset');
            Route::post('/match/{matchId}/phase', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'setPhase'])->name('match.phase');
            Route::get('/matches', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'getMatches'])->name('matches');
        });
        
        // Public Overlay Display (no auth required - for OBS)
        Route::get('/display/{matchId}', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'display'])->name('display');
        
        // API endpoint for real-time updates
        Route::get('/match/{matchId}/state', [\Modules\MLBBToolManagement\Http\Controllers\OverlayController::class, 'getMatchState'])->name('match.state');
    });
});

// Additional routes will be registered by modules

Route::get('/livewire-test', function() { return view('livewire-test'); });

// MLBB Authentication Routes (must be before catch-all)
Route::get('/login', [\Modules\MLBBToolManagement\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [\Modules\MLBBToolManagement\Http\Controllers\AuthController::class, 'showRegister'])->name('register');

// MLBB Module Routes (must be before catch-all) 
Route::prefix('mlbb')->name('mlbb.')->group(function() {
    Route::prefix('matchup')->name('matchup.')->group(function() {
        Route::get('/statistics', [\Modules\MLBBToolManagement\Http\Controllers\MatchupController::class, 'showStatistics'])->name('statistics');
    });
});

// Theme Customizer (Full-page, WordPress-style)
Route::middleware(['auth'])->group(function () {
    Route::get('/theme-customizer/{id}', [App\Http\Controllers\ThemeCustomizerController::class, 'show'])
        ->name('theme-customizer.show');
    Route::post('/theme-customizer/{id}/save', [App\Http\Controllers\ThemeCustomizerController::class, 'save'])
        ->name('theme-customizer.save');
    Route::post('/theme-customizer/{id}/reset', [App\Http\Controllers\ThemeCustomizerController::class, 'reset'])
        ->name('theme-customizer.reset');
    Route::post('/theme-customizer/{id}/activate', [App\Http\Controllers\ThemeCustomizerController::class, 'activate'])
        ->name('theme-customizer.activate');
    Route::get('/theme-customizer/{id}/elements', [App\Http\Controllers\ThemeCustomizerController::class, 'getElements'])
        ->name('theme-customizer.elements');
    Route::get('/theme-customizer/{id}/pages', [App\Http\Controllers\ThemeCustomizerController::class, 'getPages'])
        ->name('theme-customizer.pages');
    Route::post('/theme-customizer/{id}/save-layout-template', [App\Http\Controllers\ThemeCustomizerController::class, 'saveLayoutTemplate'])
        ->name('theme-customizer.save-layout-template');
    Route::get('/theme-customizer/{id}/layout-templates', [App\Http\Controllers\ThemeCustomizerController::class, 'getLayoutTemplates'])
        ->name('theme-customizer.layout-templates');
});

// Catch-all route for dynamic pages (must be last)
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('slug', '.*')
    ->name('page.show');
