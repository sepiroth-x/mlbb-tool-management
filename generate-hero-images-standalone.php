<?php
/**
 * Standalone Hero Image Generator
 * 
 * This script generates placeholder images for all MLBB heroes
 * Run: php generate-hero-images-standalone.php
 */

// Configuration
$heroesJsonPath = __DIR__ . '/Modules/MLBBToolManagement/Data/heroes.json';
$outputDir = __DIR__ . '/public/modules/mlbb-tool-management/images/heroes/';

// Color palette for hero roles
$roleColors = [
    'Tank' => ['30', '144', '255'],      // Blue
    'Fighter' => ['255', '69', '0'],      // Red-Orange
    'Assassin' => ['138', '43', '226'],   // Purple
    'Mage' => ['0', '191', '255'],        // Sky Blue
    'Marksman' => ['255', '215', '0'],    // Gold
    'Support' => ['50', '205', '50'],     // Green
];

// Ensure output directory exists
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
    echo "Created directory: $outputDir\n";
}

// Load heroes JSON
if (!file_exists($heroesJsonPath)) {
    die("Error: Heroes JSON file not found at $heroesJsonPath\n");
}

$jsonContent = file_get_contents($heroesJsonPath);
$data = json_decode($jsonContent, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Invalid JSON - " . json_last_error_msg() . "\n");
}

$heroes = $data['heroes'] ?? [];
$total = count($heroes);

if ($total === 0) {
    die("Error: No heroes found in JSON file\n");
}

echo "════════════════════════════════════════════════════════\n";
echo " MLBB Hero Image Generator\n";
echo "════════════════════════════════════════════════════════\n";
echo "Found $total heroes to process\n\n";

$generated = 0;
$skipped = 0;
$errors = 0;

foreach ($heroes as $hero) {
    $name = $hero['name'] ?? 'Unknown';
    $slug = $hero['slug'] ?? strtolower(str_replace(' ', '-', $name));
    $role = $hero['role'] ?? 'Fighter';
    $filename = "$slug.png";
    $filepath = $outputDir . $filename;
    
    // Skip if already exists
    if (file_exists($filepath)) {
        echo "⊝ Skipped: $name (image exists)\n";
        $skipped++;
        continue;
    }
    
    try {
        // Get color for role
        $rgb = $roleColors[$role] ?? $roleColors['Fighter'];
        
        // Create 256x256 image
        $image = imagecreatetruecolor(256, 256);
        
        // Allocate colors
        $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $darkBorder = imagecolorallocate($image, $rgb[0] * 0.7, $rgb[1] * 0.7, $rgb[2] * 0.7);
        
        // Fill background with gradient effect
        for ($y = 0; $y < 256; $y++) {
            $factor = 1 - ($y / 512); // Gradient from top to bottom
            $r = max(0, min(255, $rgb[0] * (1 + $factor * 0.3)));
            $g = max(0, min(255, $rgb[1] * (1 + $factor * 0.3)));
            $b = max(0, min(255, $rgb[2] * (1 + $factor * 0.3)));
            $lineColor = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, 256, $y, $lineColor);
        }
        
        // Add border
        imagerectangle($image, 0, 0, 255, 255, $darkBorder);
        imagerectangle($image, 1, 1, 254, 254, $darkBorder);
        
        // Get initials (first letters of first two words, or first 2 chars)
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $initials = strtoupper(substr($name, 0, 2));
        }
        
        // Add hero initials (larger font)
        $fontSize = 5; // Largest built-in font
        $textWidth = imagefontwidth($fontSize) * strlen($initials);
        $textHeight = imagefontheight($fontSize);
        $x = (256 - $textWidth) / 2;
        $y = (256 - $textHeight) / 2 - 20;
        
        // Add shadow for text
        imagestring($image, $fontSize, $x + 2, $y + 2, $initials, $darkBorder);
        imagestring($image, $fontSize, $x, $y, $initials, $textColor);
        
        // Add role text (smaller)
        $roleText = strtoupper($role);
        $roleFontSize = 3;
        $roleWidth = imagefontwidth($roleFontSize) * strlen($roleText);
        $roleX = (256 - $roleWidth) / 2;
        $roleY = $y + $textHeight + 10;
        
        imagestring($image, $roleFontSize, $roleX + 1, $roleY + 1, $roleText, $darkBorder);
        imagestring($image, $roleFontSize, $roleX, $roleY, $roleText, $textColor);
        
        // Save image
        imagepng($image, $filepath, 9);
        imagedestroy($image);
        
        echo "✓ Generated: $name ($role)\n";
        $generated++;
        
    } catch (Exception $e) {
        echo "✗ Error: $name - " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n════════════════════════════════════════════════════════\n";
echo " Generation Complete!\n";
echo "════════════════════════════════════════════════════════\n";
echo "✓ Generated: $generated\n";
echo "⊝ Skipped:   $skipped\n";
echo "✗ Errors:    $errors\n";
echo "\nImages saved to: $outputDir\n";

// Show sample heroes
if ($generated > 0) {
    echo "\nSample generated heroes:\n";
    $sample = array_slice($heroes, 0, 5);
    foreach ($sample as $hero) {
        echo "  • {$hero['name']} ({$hero['role']})\n";
    }
    if ($total > 5) {
        echo "  ... and " . ($total - 5) . " more\n";
    }
}

echo "\n✅ All done! You can now seed the heroes to the database.\n";
echo "   Run: php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder\n";
