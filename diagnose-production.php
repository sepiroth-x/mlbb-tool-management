<?php
/**
 * Production Diagnostic Script
 * Upload this file to your production server and run it via browser or CLI
 * Access via: https://mlbb.vantapress.com/diagnose-production.php
 */

// Security: Remove this file after debugging or add password protection
define('DIAGNOSTIC_PASSWORD', 'mlbb2026'); // Change this!

if (php_sapi_name() !== 'cli') {
    $password = $_GET['password'] ?? '';
    if ($password !== DIAGNOSTIC_PASSWORD) {
        die('Unauthorized. Add ?password=mlbb2026 to URL');
    }
}

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>";
echo "=== MLBB PRODUCTION DIAGNOSTIC REPORT ===" . PHP_EOL;
echo "Generated: " . date('Y-m-d H:i:s') . PHP_EOL;
echo "Environment: " . app()->environment() . PHP_EOL . PHP_EOL;

// 1. Check Route Registration
echo "=== REGISTERED ROUTES ===" . PHP_EOL . PHP_EOL;

$routes = app('router')->getRoutes();
$criticalRoutes = [
    'login',
    'register', 
    'mlbb/matchup/statistics',
    'mlbb/auth/login',
    'mlbb/auth/register',
];

foreach ($criticalRoutes as $uri) {
    echo "Checking: /{$uri}" . PHP_EOL;
    $found = false;
    
    foreach ($routes as $route) {
        if ($route->uri() === $uri || $route->uri() === ltrim($uri, '/')) {
            $methods = implode('|', $route->methods());
            $action = $route->getActionName();
            $name = $route->getName() ?? 'unnamed';
            
            echo "  ✓ Found: [{$methods}] → {$action}" . PHP_EOL;
            echo "    Name: {$name}" . PHP_EOL;
            echo "    Middleware: " . implode(', ', $route->middleware()) . PHP_EOL;
            $found = true;
        }
    }
    
    if (!$found) {
        echo "  ✗ NOT FOUND" . PHP_EOL;
    }
    echo PHP_EOL;
}

// 2. Check View Namespaces
echo "=== VIEW NAMESPACES ===" . PHP_EOL . PHP_EOL;

$viewFinder = app('view')->getFinder();
$hints = $viewFinder->getHints();

$criticalNamespaces = [
    'mlbb-tool-management',
    'mlbb-tool-management-theme',
    'theme',
];

foreach ($criticalNamespaces as $namespace) {
    echo "Namespace: {$namespace}" . PHP_EOL;
    
    if (isset($hints[$namespace])) {
        foreach ($hints[$namespace] as $path) {
            $exists = is_dir($path) ? '✓' : '✗';
            echo "  {$exists} {$path}" . PHP_EOL;
            
            if (is_dir($path)) {
                $readable = is_readable($path) ? '✓ readable' : '✗ not readable';
                echo "     {$readable}" . PHP_EOL;
            }
        }
    } else {
        echo "  ✗ Namespace not registered" . PHP_EOL;
    }
    echo PHP_EOL;
}

// 3. Check Critical Files
echo "=== FILE CHECKS ===" . PHP_EOL . PHP_EOL;

$criticalFiles = [
    'routes/web.php',
    'Modules/MLBBToolManagement/Routes/web.php',
    'Modules/MLBBToolManagement/Http/Controllers/MatchupController.php',
    'Modules/MLBBToolManagement/Http/Controllers/AuthController.php',
    'Modules/MLBBToolManagement/Resources/views/matchup/statistics.blade.php',
    'themes/mlbb-tool-management-theme/pages/login.blade.php',
    'themes/mlbb-tool-management-theme/pages/register.blade.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/config.php',
];

foreach ($criticalFiles as $file) {
    $fullPath = base_path($file);
    $exists = file_exists($fullPath);
    $symbol = $exists ? '✓' : '✗';
    
    echo "{$symbol} {$file}" . PHP_EOL;
    
    if ($exists) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        $size = filesize($fullPath);
        $modified = date('Y-m-d H:i:s', filemtime($fullPath));
        
        echo "   Permissions: {$perms}" . PHP_EOL;
        echo "   Size: {$size} bytes" . PHP_EOL;
        echo "   Modified: {$modified}" . PHP_EOL;
        
        // Check if it's a cache file
        if (strpos($file, 'bootstrap/cache/') === 0) {
            echo "   ⚠ CACHE FILE EXISTS - May need clearing!" . PHP_EOL;
        }
    }
    echo PHP_EOL;
}

// 4. Check Controllers
echo "=== CONTROLLER METHOD CHECKS ===" . PHP_EOL . PHP_EOL;

$controllers = [
    'Modules\MLBBToolManagement\Http\Controllers\AuthController' => ['showLogin', 'showRegister'],
    'Modules\MLBBToolManagement\Http\Controllers\MatchupController' => ['showStatistics', 'index'],
];

foreach ($controllers as $class => $methods) {
    echo "Controller: {$class}" . PHP_EOL;
    
    if (class_exists($class)) {
        echo "  ✓ Class exists" . PHP_EOL;
        
        foreach ($methods as $method) {
            if (method_exists($class, $method)) {
                echo "  ✓ Method: {$method}()" . PHP_EOL;
            } else {
                echo "  ✗ Method missing: {$method}()" . PHP_EOL;
            }
        }
    } else {
        echo "  ✗ Class not found" . PHP_EOL;
    }
    echo PHP_EOL;
}

// 5. Check Middleware
echo "=== MIDDLEWARE ===" . PHP_EOL . PHP_EOL;

$middlewareGroups = app('router')->getMiddlewareGroups();
echo "Web Middleware Group:" . PHP_EOL;
foreach ($middlewareGroups['web'] ?? [] as $middleware) {
    $name = is_string($middleware) ? $middleware : get_class($middleware);
    echo "  - {$name}" . PHP_EOL;
}
echo PHP_EOL;

// 6. Check .env Configuration
echo "=== ENVIRONMENT CONFIGURATION ===" . PHP_EOL . PHP_EOL;

$envVars = [
    'APP_ENV',
    'APP_DEBUG',
    'APP_URL',
    'CACHE_DRIVER',
    'SESSION_DRIVER',
];

foreach ($envVars as $var) {
    $value = env($var, 'NOT SET');
    echo "{$var}: {$value}" . PHP_EOL;
}
echo PHP_EOL;

// 7. Test View Resolution
echo "=== VIEW RESOLUTION TEST ===" . PHP_EOL . PHP_EOL;

$testViews = [
    'mlbb-tool-management::matchup.statistics',
    'mlbb-tool-management-theme::pages.login',
    'mlbb-tool-management-theme::pages.register',
];

foreach ($testViews as $view) {
    echo "View: {$view}" . PHP_EOL;
    
    try {
        if (view()->exists($view)) {
            $viewPath = view()->getFinder()->find($view);
            echo "  ✓ Exists at: {$viewPath}" . PHP_EOL;
        } else {
            echo "  ✗ Not found" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . PHP_EOL;
    }
    echo PHP_EOL;
}

// 8. Check Module Status
echo "=== MODULE STATUS ===" . PHP_EOL . PHP_EOL;

$modulePath = base_path('Modules/MLBBToolManagement');
if (is_dir($modulePath)) {
    echo "✓ MLBBToolManagement module directory exists" . PHP_EOL;
    
    $moduleJsonPath = $modulePath . '/module.json';
    if (file_exists($moduleJsonPath)) {
        $moduleConfig = json_decode(file_get_contents($moduleJsonPath), true);
        echo "  Name: " . ($moduleConfig['name'] ?? 'N/A') . PHP_EOL;
        echo "  Active: " . ($moduleConfig['active'] ?? false ? 'Yes' : 'No') . PHP_EOL;
        echo "  Priority: " . ($moduleConfig['priority'] ?? 'N/A') . PHP_EOL;
    } else {
        echo "  ✗ module.json not found" . PHP_EOL;
    }
} else {
    echo "✗ MLBBToolManagement module directory NOT found" . PHP_EOL;
}
echo PHP_EOL;

// 9. Recommendations
echo "=== RECOMMENDATIONS ===" . PHP_EOL . PHP_EOL;

$recommendations = [];

// Check for route cache
if (file_exists(base_path('bootstrap/cache/routes-v7.php'))) {
    $recommendations[] = "⚠ Route cache exists. Clear it by deleting: bootstrap/cache/routes-v7.php";
}

if (file_exists(base_path('bootstrap/cache/config.php'))) {
    $recommendations[] = "⚠ Config cache exists. Clear it by deleting: bootstrap/cache/config.php";
}

// Check if views are accessible
$statisticsView = base_path('Modules/MLBBToolManagement/Resources/views/matchup/statistics.blade.php');
if (!is_readable($statisticsView)) {
    $recommendations[] = "⚠ Statistics view file is not readable. Check file permissions.";
}

// Check .htaccess
$htaccessPath = base_path('public/.htaccess');
if (!file_exists($htaccessPath)) {
    $recommendations[] = "⚠ .htaccess file missing in public/ directory. URL rewriting may not work.";
}

if (empty($recommendations)) {
    echo "✓ No critical issues detected" . PHP_EOL;
} else {
    foreach ($recommendations as $rec) {
        echo $rec . PHP_EOL;
    }
}

echo PHP_EOL;
echo "=== END OF DIAGNOSTIC REPORT ===" . PHP_EOL;
echo PHP_EOL;
echo "⚠ SECURITY WARNING: Delete this file after debugging!" . PHP_EOL;
echo "</pre>";
