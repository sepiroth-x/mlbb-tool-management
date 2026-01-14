<?php

$jsonPath = 'Modules/MLBBToolManagement/Data/heroes.json';
$content = file_get_contents($jsonPath);
$data = json_decode($content, true);

echo "JSON Error: " . json_last_error_msg() . PHP_EOL;

if ($data && isset($data['heroes'])) {
    echo "Heroes found: " . count($data['heroes']) . PHP_EOL;
} else {
    echo "Failed to parse JSON" . PHP_EOL;
    // Try to find the error location
    $lines = explode("\n", $content);
    echo "Total lines: " . count($lines) . PHP_EOL;
    echo "First few lines:" . PHP_EOL;
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo "Line " . ($i+1) . ": " . substr($lines[$i], 0, 100) . PHP_EOL;
    }
}
