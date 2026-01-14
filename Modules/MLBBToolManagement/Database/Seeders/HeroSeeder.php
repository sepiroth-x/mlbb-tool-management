<?php

namespace Modules\MLBBToolManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\MLBBToolManagement\Models\Hero;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Load heroes from JSON file
        $jsonPath = module_path('MLBBToolManagement', 'Data/heroes.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('Heroes JSON file not found!');
            return;
        }

        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Invalid JSON file: ' . json_last_error_msg());
            return;
        }

        $heroes = $data['heroes'] ?? [];

        $this->command->info('Seeding ' . count($heroes) . ' heroes...');

        foreach ($heroes as $heroData) {
            Hero::updateOrCreate(
                ['slug' => $heroData['slug']],
                [
                    'name' => $heroData['name'],
                    'role' => $heroData['role'],
                    'image_path' => $heroData['image'],
                    'durability' => $heroData['durability'],
                    'offense' => $heroData['offense'],
                    'control' => $heroData['control'],
                    'difficulty' => $heroData['difficulty'],
                    'early_game' => $heroData['early_game'],
                    'mid_game' => $heroData['mid_game'],
                    'late_game' => $heroData['late_game'],
                    'specialties' => $heroData['specialties'] ?? [],
                    'strong_against' => $heroData['strong_against'] ?? [],
                    'weak_against' => $heroData['weak_against'] ?? [],
                    'synergy_with' => $heroData['synergy_with'] ?? [],
                    'description' => $heroData['description'] ?? '',
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Heroes seeded successfully!');
    }
}
