<?php

namespace Modules\MLBBToolManagement\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class HeroImageService
{
    /**
     * Fetch hero image from MLBB Wiki API
     */
    public function fetchHeroImage(string $heroName, string $slug): ?string
    {
        try {
            // Try multiple sources for hero images
            $imagePath = $this->tryMultipleSources($heroName, $slug);
            
            if ($imagePath) {
                return $imagePath;
            }
            
            // If all sources fail, generate placeholder
            return $this->generatePlaceholder($heroName, $slug);
            
        } catch (\Exception $e) {
            \Log::warning("Failed to fetch image for {$heroName}: " . $e->getMessage());
            return $this->generatePlaceholder($heroName, $slug);
        }
    }
    
    /**
     * Try multiple image sources
     */
    protected function tryMultipleSources(string $heroName, string $slug): ?string
    {
        $sources = [
            $this->tryMLBBFandomWiki($heroName, $slug),
            $this->tryUIAvatarGenerator($heroName, $slug),
        ];
        
        foreach ($sources as $source) {
            if ($source) {
                return $source;
            }
        }
        
        return null;
    }
    
    /**
     * Try fetching from MLBB Fandom Wiki
     */
    protected function tryMLBBFandomWiki(string $heroName, string $slug): ?string
    {
        try {
            // MLBB Fandom Wiki API endpoint
            $wikiUrl = "https://mobile-legends.fandom.com/wiki/{$heroName}";
            
            $response = Http::timeout(10)->get($wikiUrl);
            
            if ($response->successful()) {
                $html = $response->body();
                
                // Extract hero portrait image from wiki page
                if (preg_match('/<img[^>]+src="([^">]+\/Hero\d+-icon[^">]+)"/', $html, $matches)) {
                    $imageUrl = $matches[1];
                    return $this->downloadAndSaveImage($imageUrl, $slug);
                }
                
                // Try alternate pattern
                if (preg_match('/<img[^>]+data-src="([^">]+\/Hero\d+-icon[^">]+)"/', $html, $matches)) {
                    $imageUrl = $matches[1];
                    return $this->downloadAndSaveImage($imageUrl, $slug);
                }
            }
        } catch (\Exception $e) {
            \Log::debug("MLBB Wiki fetch failed for {$heroName}: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Use UI Avatars as fallback (generates avatar from name)
     */
    protected function tryUIAvatarGenerator(string $heroName, string $slug): ?string
    {
        try {
            $initials = $this->getInitials($heroName);
            $backgroundColor = $this->getColorForHero($heroName);
            
            $url = "https://ui-avatars.com/api/?" . http_build_query([
                'name' => $heroName,
                'size' => 256,
                'background' => $backgroundColor,
                'color' => 'ffffff',
                'bold' => true,
                'format' => 'png',
            ]);
            
            return $this->downloadAndSaveImage($url, $slug);
            
        } catch (\Exception $e) {
            \Log::debug("UI Avatar generation failed for {$heroName}: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Download and save image locally
     */
    protected function downloadAndSaveImage(string $url, string $slug): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);
            
            if ($response->successful()) {
                $filename = "{$slug}.png";
                $path = public_path("modules/mlbb-tool-management/images/heroes/{$filename}");
                
                // Ensure directory exists
                $dir = dirname($path);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                // Save image
                file_put_contents($path, $response->body());
                
                // Resize to 256x256 if Intervention Image is available
                if (class_exists('\Intervention\Image\Facades\Image')) {
                    try {
                        $img = Image::make($path);
                        $img->fit(256, 256);
                        $img->save($path);
                    } catch (\Exception $e) {
                        // If resize fails, keep original
                        \Log::debug("Image resize failed for {$slug}: " . $e->getMessage());
                    }
                }
                
                return $filename;
            }
        } catch (\Exception $e) {
            \Log::debug("Image download failed: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Generate placeholder image using GD
     */
    protected function generatePlaceholder(string $heroName, string $slug): string
    {
        $filename = "{$slug}.png";
        $path = public_path("modules/mlbb-tool-management/images/heroes/{$filename}");
        
        // Ensure directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Create 256x256 image
        $image = imagecreatetruecolor(256, 256);
        
        // Get color for hero
        $colorHex = $this->getColorForHero($heroName);
        $rgb = $this->hexToRgb($colorHex);
        
        $backgroundColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefill($image, 0, 0, $backgroundColor);
        
        // Add hero initials
        $initials = $this->getInitials($heroName);
        
        // Use larger built-in font (5 is largest)
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($initials);
        $textHeight = imagefontheight($fontSize);
        
        $x = (256 - $textWidth) / 2;
        $y = (256 - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $initials, $textColor);
        
        // Save image
        imagepng($image, $path);
        imagedestroy($image);
        
        return $filename;
    }
    
    /**
     * Get initials from hero name
     */
    protected function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2));
    }
    
    /**
     * Get consistent color for hero based on name
     */
    protected function getColorForHero(string $name): string
    {
        $colors = [
            '3498db', // Blue
            '9b59b6', // Purple
            'e74c3c', // Red
            '1abc9c', // Turquoise
            'f39c12', // Orange
            '2ecc71', // Green
            'e67e22', // Carrot
            '34495e', // Dark gray
            'c0392b', // Dark red
            '8e44ad', // Dark purple
            '16a085', // Dark turquoise
            '27ae60', // Dark green
        ];
        
        $index = ord($name[0]) % count($colors);
        return $colors[$index];
    }
    
    /**
     * Convert hex color to RGB
     */
    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
    
    /**
     * Check if hero image exists
     */
    public function imageExists(string $slug): bool
    {
        $path = public_path("modules/mlbb-tool-management/images/heroes/{$slug}.png");
        return file_exists($path);
    }
    
    /**
     * Batch generate images for all heroes
     */
    public function generateAllImages(array $heroes, $progressCallback = null): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'skipped' => 0,
            'details' => [],
        ];
        
        $total = count($heroes);
        $current = 0;
        
        foreach ($heroes as $hero) {
            $current++;
            
            $heroName = $hero['name'];
            $slug = $hero['slug'];
            
            if ($this->imageExists($slug)) {
                $results['skipped']++;
                $results['details'][] = [
                    'hero' => $heroName,
                    'status' => 'skipped',
                    'message' => 'Image already exists',
                ];
                
                if ($progressCallback) {
                    $progressCallback($current, $total, $heroName, 'skipped');
                }
                
                continue;
            }
            
            try {
                $imagePath = $this->fetchHeroImage($heroName, $slug);
                
                if ($imagePath) {
                    $results['success']++;
                    $results['details'][] = [
                        'hero' => $heroName,
                        'status' => 'success',
                        'path' => $imagePath,
                    ];
                } else {
                    $results['failed']++;
                    $results['details'][] = [
                        'hero' => $heroName,
                        'status' => 'failed',
                        'message' => 'Could not generate image',
                    ];
                }
                
                if ($progressCallback) {
                    $progressCallback($current, $total, $heroName, $imagePath ? 'success' : 'failed');
                }
                
                // Small delay to avoid rate limiting
                usleep(500000); // 0.5 seconds
                
            } catch (\Exception $e) {
                $results['failed']++;
                $results['details'][] = [
                    'hero' => $heroName,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ];
                
                if ($progressCallback) {
                    $progressCallback($current, $total, $heroName, 'failed');
                }
            }
        }
        
        return $results;
    }
}
