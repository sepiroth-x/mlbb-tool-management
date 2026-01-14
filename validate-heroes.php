<?php
echo "═══════════════════════════════════════════════════════\n";
echo "  MLBB Heroes Data Validation\n";
echo "═══════════════════════════════════════════════════════\n\n";

$jsonPath = 'Modules/MLBBToolManagement/Data/heroes.json';
$data = json_decode(file_get_contents($jsonPath), true);

if (!$data) {
    die("❌ JSON validation failed: " . json_last_error_msg() . "\n");
}

echo "✓ JSON is valid\n";
echo "✓ Total heroes: " . count($data['heroes']) . "\n\n";

echo "First 5 heroes:\n";
foreach (array_slice($data['heroes'], 0, 5) as $hero) {
    echo "  {$hero['id']}. {$hero['name']} ({$hero['role']}) - Image: {$hero['image']}\n";
}

echo "\nLast 5 heroes:\n";
foreach (array_slice($data['heroes'], -5) as $hero) {
    echo "  {$hero['id']}. {$hero['name']} ({$hero['role']}) - Image: {$hero['image']}\n";
}

echo "\n\nRole distribution:\n";
$roles = [];
foreach ($data['heroes'] as $hero) {
    $role = $hero['role'];
    $roles[$role] = ($roles[$role] ?? 0) + 1;
}
arsort($roles);
foreach ($roles as $role => $count) {
    echo "  $role: $count heroes\n";
}

echo "\n\nImage files check:\n";
$imageDir = 'public/modules/mlbb-tool-management/images/heroes/';
$imageFiles = glob($imageDir . '*.png');
echo "  Total image files: " . count($imageFiles) . "\n";

$missing = [];
foreach ($data['heroes'] as $hero) {
    $imagePath = $imageDir . $hero['image'];
    if (!file_exists($imagePath)) {
        $missing[] = $hero['name'];
    }
}

if (empty($missing)) {
    echo "  ✓ All hero images exist!\n";
} else {
    echo "  ⚠ Missing images for: " . implode(', ', $missing) . "\n";
}

echo "\n✅ Validation complete! Ready to seed to database.\n";
echo "   Run: php artisan db:seed --class=Modules\\MLBBToolManagement\\Database\\Seeders\\HeroSeeder\n";
