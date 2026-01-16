<?php

namespace Modules\MLBBToolManagement\Services;

use Modules\MLBBToolManagement\Models\LineupStatistic;
use Modules\MLBBToolManagement\Models\HeroStatistic;

/**
 * Statistics Tracking Service
 * 
 * Handles tracking and recording of lineup and hero statistics
 */
class StatisticsTrackingService
{
    /**
     * Track a matchup analysis
     * 
     * @param array $teamAHeroes Array of Team A hero data
     * @param array $teamBHeroes Array of Team B hero data
     * @param array $analysisResult The complete analysis result
     * @return void
     */
    public function trackMatchupAnalysis(array $teamAHeroes, array $teamBHeroes, array $analysisResult): void
    {
        // Extract hero slugs
        $teamASlugs = array_column($teamAHeroes, 'slug');
        $teamBSlugs = array_column($teamBHeroes, 'slug');
        
        // Track lineup statistics
        $this->trackLineupStatistics($teamASlugs, $teamBSlugs, $analysisResult);
        
        // Track individual hero statistics
        $this->trackHeroStatistics($teamAHeroes, $teamBHeroes, $analysisResult);
    }
    
    /**
     * Track lineup statistics for both teams
     */
    protected function trackLineupStatistics(array $teamASlugs, array $teamBSlugs, array $analysisResult): void
    {
        // Generate hashes for opponent tracking
        $teamAHash = LineupStatistic::generateLineupHash($teamASlugs);
        $teamBHash = LineupStatistic::generateLineupHash($teamBSlugs);
        
        // Track Team A lineup
        $teamALineup = LineupStatistic::findOrCreateLineup($teamASlugs);
        $teamALineup->role_composition = $this->getRoleComposition($analysisResult['team_a']['heroes']);
        
        $teamAData = [
            'matchup_advantage' => $analysisResult['team_a']['stats']['team_power'] - $analysisResult['team_b']['stats']['team_power'],
            'early_game' => $analysisResult['team_a']['stats']['early_game'],
            'mid_game' => $analysisResult['team_a']['stats']['mid_game'],
            'late_game' => $analysisResult['team_a']['stats']['late_game'],
            'synergy_score' => $this->calculateSynergyScore($analysisResult['team_a']),
            'opponent_hash' => $teamBHash,
        ];
        
        $teamALineup->recordAnalysis($teamAData);
        $teamALineup->durability_avg = $analysisResult['team_a']['stats']['durability'];
        $teamALineup->offense_avg = $analysisResult['team_a']['stats']['offense'];
        $teamALineup->control_avg = $analysisResult['team_a']['stats']['control'];
        $teamALineup->save();
        
        // Track Team B lineup
        $teamBLineup = LineupStatistic::findOrCreateLineup($teamBSlugs);
        $teamBLineup->role_composition = $this->getRoleComposition($analysisResult['team_b']['heroes']);
        
        $teamBData = [
            'matchup_advantage' => $analysisResult['team_b']['stats']['team_power'] - $analysisResult['team_a']['stats']['team_power'],
            'early_game' => $analysisResult['team_b']['stats']['early_game'],
            'mid_game' => $analysisResult['team_b']['stats']['mid_game'],
            'late_game' => $analysisResult['team_b']['stats']['late_game'],
            'synergy_score' => $this->calculateSynergyScore($analysisResult['team_b']),
            'opponent_hash' => $teamAHash,
        ];
        
        $teamBLineup->recordAnalysis($teamBData);
        $teamBLineup->durability_avg = $analysisResult['team_b']['stats']['durability'];
        $teamBLineup->offense_avg = $analysisResult['team_b']['stats']['offense'];
        $teamBLineup->control_avg = $analysisResult['team_b']['stats']['control'];
        $teamBLineup->save();
    }
    
    /**
     * Track individual hero statistics
     */
    protected function trackHeroStatistics(array $teamAHeroes, array $teamBHeroes, array $analysisResult): void
    {
        $teamASlugs = array_column($teamAHeroes, 'slug');
        $teamBSlugs = array_column($teamBHeroes, 'slug');
        
        // Determine which team "won" based on win probability
        $teamAWon = $analysisResult['team_a']['win_probability'] > $analysisResult['team_b']['win_probability'] ? true : null;
        $teamBWon = $analysisResult['team_b']['win_probability'] > $analysisResult['team_a']['win_probability'] ? true : null;
        
        // Track Team A heroes
        foreach ($teamAHeroes as $hero) {
            $heroStat = HeroStatistic::findOrCreateHero($hero['slug'], $hero['name'], $hero['role']);
            
            // Get teammates (excluding self)
            $teammates = array_filter($teamASlugs, fn($s) => $s !== $hero['slug']);
            
            $heroStat->recordPick($teammates, $teamBSlugs, $teamAWon);
            
            // Update performance metrics
            $heroStat->updatePerformanceMetrics([
                'matchup_impact' => $hero['offense'] + $hero['control'],
                'early_game' => $hero['early_game'],
                'mid_game' => $hero['mid_game'],
                'late_game' => $hero['late_game'],
            ]);
        }
        
        // Track Team B heroes
        foreach ($teamBHeroes as $hero) {
            $heroStat = HeroStatistic::findOrCreateHero($hero['slug'], $hero['name'], $hero['role']);
            
            // Get teammates (excluding self)
            $teammates = array_filter($teamBSlugs, fn($s) => $s !== $hero['slug']);
            
            $heroStat->recordPick($teammates, $teamASlugs, $teamBWon);
            
            // Update performance metrics
            $heroStat->updatePerformanceMetrics([
                'matchup_impact' => $hero['offense'] + $hero['control'],
                'early_game' => $hero['early_game'],
                'mid_game' => $hero['mid_game'],
                'late_game' => $hero['late_game'],
            ]);
        }
    }
    
    /**
     * Get role composition string from heroes
     */
    protected function getRoleComposition(array $heroes): string
    {
        $roles = array_column($heroes, 'role');
        sort($roles);
        return implode('-', $roles);
    }
    
    /**
     * Calculate synergy score from team analysis
     */
    protected function calculateSynergyScore(array $teamAnalysis): float
    {
        // Base synergy on team power and role distribution
        $score = $teamAnalysis['stats']['team_power'];
        
        // Bonus for balanced role distribution
        if (isset($teamAnalysis['stats']['role_distribution'])) {
            $roleCount = count($teamAnalysis['stats']['role_distribution']);
            if ($roleCount >= 4) {
                $score += 1; // Diverse team bonus
            }
        }
        
        return min(10, $score); // Cap at 10
    }
    
    /**
     * Track ban statistics for heroes
     */
    public function trackHeroBans(array $bannedHeroSlugs): void
    {
        foreach ($bannedHeroSlugs as $slug) {
            // We need to find hero info first
            $heroData = app(HeroDataService::class)->getHeroBySlug($slug);
            
            if ($heroData) {
                $heroStat = HeroStatistic::findOrCreateHero(
                    $heroData['slug'],
                    $heroData['name'],
                    $heroData['role']
                );
                
                $heroStat->recordBan();
            }
        }
    }
    
    /**
     * Get top performing lineups
     */
    public function getTopLineups(int $limit = 10): array
    {
        return LineupStatistic::getTopLineups($limit)->toArray();
    }
    
    /**
     * Get trending lineups
     */
    public function getTrendingLineups(int $limit = 10): array
    {
        return LineupStatistic::getTrendingLineups($limit)->toArray();
    }
    
    /**
     * Get hero statistics
     */
    public function getHeroStatistics(string $slug): ?array
    {
        $stat = HeroStatistic::where('hero_slug', $slug)->first();
        return $stat ? $stat->toArray() : null;
    }
    
    /**
     * Get top picked heroes
     */
    public function getTopPickedHeroes(int $limit = 10): array
    {
        return HeroStatistic::getTopPicked($limit)->toArray();
    }
    
    /**
     * Get highest win rate heroes
     */
    public function getHighestWinRateHeroes(int $limit = 10, int $minPicks = 10): array
    {
        return HeroStatistic::getHighestWinRate($limit, $minPicks)->toArray();
    }
    
    /**
     * Get most banned heroes
     */
    public function getMostBannedHeroes(int $limit = 10): array
    {
        return HeroStatistic::getMostBanned($limit)->toArray();
    }
}
