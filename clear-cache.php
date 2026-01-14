<?php
/**
 * Cache Clearer for Shared Hosting
 * Access this file via browser: yourdomain.com/clear-cache.php
 * Then DELETE this file for security!
 */

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<!DOCTYPE html>";
echo "<html><head><title>Cache Clearer</title>";
echo "<style>body{font-family:Arial;padding:40px;background:#0a0e1a;color:#fff;} .success{color:#00ff00;} .error{color:#ff0000;} pre{background:#1a1f2e;padding:20px;border-radius:10px;}</style>";
echo "</head><body>";
echo "<h1>🧹 MLBB Cache Clearer</h1>";

try {
    echo "<pre>";
    
    // Clear route cache
    echo "Clearing route cache...\n";
    if (file_exists($routeCachePath = base_path('bootstrap/cache/routes-v7.php'))) {
        unlink($routeCachePath);
        echo "✅ Route cache cleared\n";
    } else {
        echo "ℹ️  No route cache found\n";
    }
    
    // Clear config cache
    echo "\nClearing config cache...\n";
    if (file_exists($configCachePath = base_path('bootstrap/cache/config.php'))) {
        unlink($configCachePath);
        echo "✅ Config cache cleared\n";
    } else {
        echo "ℹ️  No config cache found\n";
    }
    
    // Clear compiled views
    echo "\nClearing compiled views...\n";
    $viewPath = storage_path('framework/views');
    if (is_dir($viewPath)) {
        $files = glob($viewPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✅ Compiled views cleared\n";
    }
    
    // Clear application cache
    echo "\nClearing application cache...\n";
    $cachePath = storage_path('framework/cache/data');
    if (is_dir($cachePath)) {
        $files = glob($cachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✅ Application cache cleared\n";
    }
    
    echo "\n<span class='success'>✅ ALL CACHES CLEARED SUCCESSFULLY!</span>\n";
    
    // Check heroes.json file
    echo "\n📋 CHECKING HEROES.JSON FILE:\n";
    $heroesPath = base_path('Modules/MLBBToolManagement/Data/heroes.json');
    if (file_exists($heroesPath)) {
        echo "✅ File exists: {$heroesPath}\n";
        echo "📦 File size: " . number_format(filesize($heroesPath)) . " bytes\n";
        
        $content = file_get_contents($heroesPath);
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✅ JSON is valid\n";
            echo "👥 Heroes count: " . count($decoded['heroes'] ?? []) . "\n";
        } else {
            echo "<span class='error'>❌ JSON ERROR: " . json_last_error_msg() . "</span>\n";
            echo "First 500 chars:\n";
            echo htmlspecialchars(substr($content, 0, 500)) . "\n";
        }
    } else {
        echo "<span class='error'>❌ FILE NOT FOUND: {$heroesPath}</span>\n";
    }
    
    echo "\n🔗 Now test your site:\n";
    echo "   • Homepage: " . url('/') . "\n";
    echo "   • Matchup Tool: " . url('/mlbb/matchup') . "\n";
    echo "   • Overlay Admin: " . url('/mlbb/overlay/admin') . "\n";
    
    echo "\n⚠️  IMPORTANT: DELETE THIS FILE NOW FOR SECURITY!\n";
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<pre class='error'>";
    echo "❌ Error: " . $e->getMessage();
    echo "</pre>";
}

echo "</body></html>";
