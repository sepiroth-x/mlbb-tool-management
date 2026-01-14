<?php

namespace Modules\MLBBToolManagement\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\MLBBToolManagement\Models\Hero;

/**
 * Hero Data Service
 * 
 * Handles loading and caching of hero data from JSON or database
 */
class HeroDataService
{
    protected $cacheKey = 'mlbb_heroes_data';
    protected $cacheTtl = 3600; // 1 hour

    /**
     * Get all heroes
     */
    public function getAllHeroes(): array
    {
        $source = config('mlbb-tool-management.hero_data_source', 'json');

        if ($source === 'database') {
            return $this->getHeroesFromDatabase();
        }

        return $this->getHeroesFromJson();
    }

    /**
     * Get heroes from database
     */
    protected function getHeroesFromDatabase(): array
    {
        return Cache::remember($this->cacheKey . '_db', $this->cacheTtl, function () {
            return Hero::active()->get()->toArray();
        });
    }

    /**
     * Get heroes from JSON file
     */
    protected function getHeroesFromJson(): array
    {
        // Temporarily bypass cache to fix corrupted cache issue
        // return Cache::remember($this->cacheKey . '_json', $this->cacheTtl, function () {
            $jsonPath = module_path('MLBBToolManagement', 'Data/heroes.json');
            
            if (!File::exists($jsonPath)) {
                throw new \Exception("Heroes data file not found at: " . $jsonPath);
            }

            $jsonContent = File::get($jsonPath);
            
            // Remove BOM (Byte Order Mark) if present
            $jsonContent = preg_replace('/^\xEF\xBB\xBF/', '', $jsonContent);
            
            // Show more details if JSON is invalid
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $error = json_last_error_msg();
                $preview = substr($jsonContent, 0, 200);
                throw new \Exception("Invalid heroes data file: {$error}. File preview: {$preview}");
            }

            if (!isset($data['heroes']) || !is_array($data['heroes'])) {
                throw new \Exception("Heroes data file missing 'heroes' array");
            }

            return $data['heroes'];
        // });
    }

    /**
     * Get hero by slug
     */
    public function getHeroBySlug(string $slug): ?array
    {
        $heroes = $this->getAllHeroes();
        
        foreach ($heroes as $hero) {
            if ($hero['slug'] === $slug) {
                return $hero;
            }
        }

        return null;
    }

    /**
     * Get multiple heroes by slugs
     */
    public function getHeroesBySlugs(array $slugs): array
    {
        $heroes = $this->getAllHeroes();
        $result = [];

        foreach ($heroes as $hero) {
            if (in_array($hero['slug'], $slugs)) {
                $result[] = $hero;
            }
        }

        return $result;
    }

    /**
     * Get heroes by role
     */
    public function getHeroesByRole(string $role): array
    {
        $heroes = $this->getAllHeroes();
        
        return array_filter($heroes, function ($hero) use ($role) {
            return $hero['role'] === $role;
        });
    }

    /**
     * Clear heroes cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey . '_db');
        Cache::forget($this->cacheKey . '_json');
    }

    /**
     * Get hero image path
     */
    public function getHeroImageUrl(string $slug): string
    {
        $hero = $this->getHeroBySlug($slug);
        
        if ($hero && isset($hero['image'])) {
            return asset('modules/mlbb-tool-management/images/heroes/' . $hero['image']);
        }

        return asset('modules/mlbb-tool-management/images/heroes/default.png');
    }

    /**
     * Get all roles
     */
    public function getAllRoles(): array
    {
        return ['Tank', 'Fighter', 'Assassin', 'Mage', 'Marksman', 'Support'];
    }
}
