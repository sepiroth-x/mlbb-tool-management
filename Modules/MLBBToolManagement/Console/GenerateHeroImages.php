<?php

namespace Modules\MLBBToolManagement\Console;

use Illuminate\Console\Command;
use Modules\MLBBToolManagement\Services\HeroImageService;

class GenerateHeroImages extends Command
{
    protected $signature = 'mlbb:generate-images 
                            {--force : Force regenerate all images}
                            {--hero= : Generate image for specific hero slug}';
    
    protected $description = 'Generate or fetch hero images from external sources';
    
    protected $imageService;
    
    public function __construct(HeroImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }
    
    public function handle()
    {
        $this->info('═══════════════════════════════════════════════════');
        $this->info('    MLBB Hero Image Generator');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();
        
        // Load heroes from JSON
        $heroesPath = module_path('MLBBToolManagement', 'Data/heroes.json');
        
        if (!file_exists($heroesPath)) {
            $this->error('Heroes data file not found!');
            return 1;
        }
        
        $heroesData = json_decode(file_get_contents($heroesPath), true);
        $heroes = $heroesData['heroes'] ?? [];
        
        if (empty($heroes)) {
            $this->error('No heroes found in data file!');
            return 1;
        }
        
        // If specific hero requested
        if ($heroSlug = $this->option('hero')) {
            return $this->generateSingleHero($heroes, $heroSlug);
        }
        
        // Generate all heroes
        return $this->generateAllHeroes($heroes);
    }
    
    protected function generateSingleHero(array $heroes, string $slug): int
    {
        $hero = collect($heroes)->firstWhere('slug', $slug);
        
        if (!$hero) {
            $this->error("Hero with slug '{$slug}' not found!");
            return 1;
        }
        
        $this->info("Generating image for: {$hero['name']}");
        $this->newLine();
        
        $imagePath = $this->imageService->fetchHeroImage($hero['name'], $hero['slug']);
        
        if ($imagePath) {
            $this->info("✓ Successfully generated: {$imagePath}");
            return 0;
        } else {
            $this->error("✗ Failed to generate image for {$hero['name']}");
            return 1;
        }
    }
    
    protected function generateAllHeroes(array $heroes): int
    {
        $total = count($heroes);
        $force = $this->option('force');
        
        $this->info("Found {$total} heroes to process");
        
        if (!$force) {
            $this->warn('Existing images will be skipped. Use --force to regenerate all.');
        }
        
        $this->newLine();
        
        if (!$this->confirm('Start generating images?', true)) {
            $this->info('Operation cancelled.');
            return 0;
        }
        
        $this->newLine();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->setFormat('very_verbose');
        
        $currentHero = '';
        
        $results = $this->imageService->generateAllImages(
            $heroes,
            function ($current, $total, $heroName, $status) use ($progressBar, &$currentHero) {
                $currentHero = $heroName;
                $progressBar->advance();
            }
        );
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Display results
        $this->info('═══════════════════════════════════════════════════');
        $this->info('    Generation Complete!');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();
        
        $this->line("✓ Success: <fg=green>{$results['success']}</>");
        $this->line("⊝ Skipped: <fg=yellow>{$results['skipped']}</>");
        $this->line("✗ Failed:  <fg=red>{$results['failed']}</>");
        $this->newLine();
        
        // Show failed heroes if any
        if ($results['failed'] > 0) {
            $this->warn('Failed Heroes:');
            foreach ($results['details'] as $detail) {
                if ($detail['status'] === 'failed') {
                    $message = $detail['message'] ?? 'Unknown error';
                    $this->line("  • {$detail['hero']}: {$message}");
                }
            }
            $this->newLine();
        }
        
        $this->info('Images saved to: public/modules/mlbb-tool-management/images/heroes/');
        
        return 0;
    }
}
