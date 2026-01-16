<?php
/**
 * Final Route and View Test Script
 * Tests all critical routes and views for MLBB module
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a mock request
$request = Illuminate\Http\Request::create('/', 'GET');
$app->instance('request', $request);

echo "=== MLBB Route and View Test ===" . PHP_EOL . PHP_EOL;

// Get all registered routes
$routes = app('router')->getRoutes();

$requiredRoutes = [
    '/login' => 'GET',
    '/register' => 'GET',
    '/mlbb/matchup/statistics' => 'GET',
];

$passed = 0;
$failed = 0;

echo "=== Route Registration Test ===" . PHP_EOL . PHP_EOL;

foreach ($requiredRoutes as $uri => $method) {
    echo "Checking route: {$method} {$uri}" . PHP_EOL;
    
    $found = false;
    foreach ($routes as $route) {
        if ($route->uri() === ltrim($uri, '/') && in_array($method, $route->methods())) {
            $action = $route->getActionName();
            echo "✓ Route found: {$action}" . PHP_EOL;
            $found = true;
            $passed++;
            break;
        }
    }
    
    if (!$found) {
        echo "✗ Route NOT found" . PHP_EOL;
        $failed++;
    }
    
    echo PHP_EOL;
}

// Test view namespaces
echo "=== View Namespace Tests ===" . PHP_EOL . PHP_EOL;

$viewTests = [
    'mlbb-tool-management::matchup.statistics',
    'mlbb-tool-management-theme::pages.login',
    'mlbb-tool-management-theme::pages.register',
    'mlbb-tool-management-theme::layouts.app',
];

foreach ($viewTests as $view) {
    echo "Testing view: {$view}" . PHP_EOL;
    
    try {
        if (view()->exists($view)) {
            echo "✓ View exists" . PHP_EOL;
            $passed++;
        } else {
            echo "✗ View not found" . PHP_EOL;
            $failed++;
        }
    } catch (Exception $e) {
        echo "✗ Error checking view: " . $e->getMessage() . PHP_EOL;
        $failed++;
    }
    
    echo PHP_EOL;
}

// Summary
echo "=== Test Summary ===" . PHP_EOL;
echo "Passed: {$passed}" . PHP_EOL;
echo "Failed: {$failed}" . PHP_EOL;
echo "Total: " . ($passed + $failed) . PHP_EOL;

if ($failed === 0) {
    echo PHP_EOL . "✓ All tests passed! Routes and views are properly configured." . PHP_EOL;
} else {
    echo PHP_EOL . "✗ Some tests failed. Please review the errors above." . PHP_EOL;
}
