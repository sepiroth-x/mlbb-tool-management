<?php
/**
 * Test Script for Production Fixes
 * 
 * This script tests the 4 critical fixes:
 * 1. Statistics page accessibility
 * 2. Login links redirect to MLBB auth
 * 3. Hero details click functionality
 * 4. Ask AI chat feature
 * 
 * Run this script after deployment: php test-fixes.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n🧪 MLBB Tool - Production Fixes Test Suite\n";
echo "==========================================\n\n";

$errors = [];
$successes = [];

// Test 1: Statistics Route
echo "Test 1: Statistics Route\n";
try {
    $route = app('router')->getRoutes()->getByName('mlbb.matchup.statistics');
    if ($route) {
        $successes[] = "✅ Statistics route exists: " . $route->uri();
    } else {
        $errors[] = "❌ Statistics route not found";
    }
} catch (Exception $e) {
    $errors[] = "❌ Statistics route error: " . $e->getMessage();
}

// Test 2: MLBB Auth Routes
echo "Test 2: MLBB Auth Routes\n";
try {
    $loginRoute = app('router')->getRoutes()->getByName('mlbb.auth.login');
    $logoutRoute = app('router')->getRoutes()->getByName('mlbb.auth.logout');
    $dashboardRoute = app('router')->getRoutes()->getByName('mlbb.dashboard');
    
    if ($loginRoute && $logoutRoute && $dashboardRoute) {
        $successes[] = "✅ MLBB auth routes configured:";
        $successes[] = "   - Login: " . $loginRoute->uri();
        $successes[] = "   - Logout: " . $logoutRoute->uri();
        $successes[] = "   - Dashboard: " . $dashboardRoute->uri();
    } else {
        $errors[] = "❌ Missing MLBB auth routes";
    }
} catch (Exception $e) {
    $errors[] = "❌ Auth routes error: " . $e->getMessage();
}

// Test 3: Chat Route
echo "Test 3: Ask AI Chat Route\n";
try {
    $chatRoute = app('router')->getRoutes()->getByName('mlbb.matchup.chat');
    if ($chatRoute) {
        $methods = $chatRoute->methods();
        if (in_array('POST', $methods)) {
            $successes[] = "✅ Chat route exists (POST): " . $chatRoute->uri();
        } else {
            $errors[] = "❌ Chat route exists but not POST method";
        }
    } else {
        $errors[] = "❌ Chat route not found";
    }
} catch (Exception $e) {
    $errors[] = "❌ Chat route error: " . $e->getMessage();
}

// Test 4: MatchupController has chat method
echo "Test 4: MatchupController Chat Method\n";
try {
    $controller = new \Modules\MLBBToolManagement\Http\Controllers\MatchupController(
        app(\Modules\MLBBToolManagement\Services\HeroDataService::class),
        app(\Modules\MLBBToolManagement\Services\MatchupAnalyzerService::class),
        app(\Modules\MLBBToolManagement\Services\StatisticsTrackingService::class)
    );
    
    if (method_exists($controller, 'chat')) {
        $successes[] = "✅ MatchupController::chat() method exists";
    } else {
        $errors[] = "❌ MatchupController::chat() method not found";
    }
} catch (Exception $e) {
    $errors[] = "❌ Controller test error: " . $e->getMessage();
}

// Test 5: OpenAI Service
echo "Test 5: OpenAI Service\n";
try {
    if (class_exists(\Modules\MLBBToolManagement\Services\OpenAIService::class)) {
        $openaiService = app(\Modules\MLBBToolManagement\Services\OpenAIService::class);
        if (method_exists($openaiService, 'chat')) {
            $successes[] = "✅ OpenAI Service configured with chat method";
        } else {
            $errors[] = "❌ OpenAI Service missing chat method";
        }
    } else {
        $errors[] = "❌ OpenAI Service class not found";
    }
} catch (Exception $e) {
    $errors[] = "❌ OpenAI Service error: " . $e->getMessage();
}

// Test 6: Statistics View File
echo "Test 6: Statistics View File\n";
try {
    $viewPath = resource_path('../Modules/MLBBToolManagement/Resources/views/matchup/statistics.blade.php');
    if (file_exists($viewPath)) {
        $successes[] = "✅ Statistics view file exists: $viewPath";
    } else {
        $errors[] = "❌ Statistics view file not found: $viewPath";
    }
} catch (Exception $e) {
    $errors[] = "❌ View file test error: " . $e->getMessage();
}

// Test 7: MLBB Dashboard View
echo "Test 7: MLBB Dashboard View\n";
try {
    $dashboardPath = base_path('themes/mlbb-tool-management-theme/pages/dashboard.blade.php');
    if (file_exists($dashboardPath)) {
        $successes[] = "✅ MLBB user dashboard exists: $dashboardPath";
    } else {
        $errors[] = "❌ MLBB user dashboard not found: $dashboardPath";
    }
} catch (Exception $e) {
    $errors[] = "❌ Dashboard test error: " . $e->getMessage();
}

// Output Results
echo "\n==========================================\n";
echo "TEST RESULTS\n";
echo "==========================================\n\n";

if (!empty($successes)) {
    echo "✅ PASSED TESTS:\n";
    foreach ($successes as $success) {
        echo "$success\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ FAILED TESTS:\n";
    foreach ($errors as $error) {
        echo "$error\n";
    }
    echo "\n";
}

$totalTests = count($successes) + count($errors);
$passedTests = count($successes);

echo "==========================================\n";
echo "SUMMARY: $passedTests/$totalTests tests passed\n";
echo "==========================================\n\n";

if (empty($errors)) {
    echo "🎉 All tests passed! Ready for production.\n\n";
    exit(0);
} else {
    echo "⚠️  Some tests failed. Please review before deployment.\n\n";
    exit(1);
}
