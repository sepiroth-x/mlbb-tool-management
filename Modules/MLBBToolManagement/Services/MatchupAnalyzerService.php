<?php

namespace Modules\MLBBToolManagement\Services;

/**
 * Matchup Analyzer Service
 * 
 * Analyzes team compositions and calculates winning probabilities
 * based on hero synergies, counters, and game phase strengths
 * Enhanced with AI-powered analysis using OpenAI ChatGPT API
 */
class MatchupAnalyzerService
{
    protected HeroDataService $heroDataService;
    protected OpenAIService $openAIService;

    public function __construct(
        HeroDataService $heroDataService,
        OpenAIService $openAIService
    ) {
        $this->heroDataService = $heroDataService;
        $this->openAIService = $openAIService;
    }

    /**
     * Analyze matchup between two teams
     * 
     * @param array $teamASlugs Array of 5 hero slugs for Team A
     * @param array $teamBSlugs Array of 5 hero slugs for Team B
     * @return array Analysis results
     */
    public function analyzeMatchup(array $teamASlugs, array $teamBSlugs): array
    {
        // Validate input
        if (count($teamASlugs) !== 5 || count($teamBSlugs) !== 5) {
            throw new \InvalidArgumentException("Each team must have exactly 5 heroes");
        }

        // Get hero data
        $teamAHeroes = $this->heroDataService->getHeroesBySlugs($teamASlugs);
        $teamBHeroes = $this->heroDataService->getHeroesBySlugs($teamBSlugs);

        if (count($teamAHeroes) !== 5 || count($teamBHeroes) !== 5) {
            throw new \InvalidArgumentException("Invalid hero slugs provided");
        }

        // Calculate team statistics
        $teamAStats = $this->calculateTeamStats($teamAHeroes);
        $teamBStats = $this->calculateTeamStats($teamBHeroes);

        // Analyze counters and synergies
        $teamACounters = $this->analyzeCounters($teamAHeroes, $teamBHeroes);
        $teamBCounters = $this->analyzeCounters($teamBHeroes, $teamAHeroes);

        // Calculate win probabilities
        $winProbabilities = $this->calculateWinProbabilities(
            $teamAStats, 
            $teamBStats, 
            $teamACounters, 
            $teamBCounters
        );

        // Generate team analysis
        $teamAAnalysis = $this->generateTeamAnalysis($teamAHeroes, $teamAStats, $teamACounters);
        $teamBAnalysis = $this->generateTeamAnalysis($teamBHeroes, $teamBStats, $teamBCounters);

        // Generate winning strategies
        $teamAStrategy = $this->generateWinningStrategy($teamAHeroes, $teamBHeroes, $teamAStats, $teamBStats);
        $teamBStrategy = $this->generateWinningStrategy($teamBHeroes, $teamAHeroes, $teamBStats, $teamAStats);

        // Build basic analysis result
        $basicAnalysis = [
            'team_a' => [
                'heroes' => $teamAHeroes,
                'stats' => $teamAStats,
                'analysis' => $teamAAnalysis,
                'win_probability' => $winProbabilities['team_a'],
                'strategy' => $teamAStrategy,
            ],
            'team_b' => [
                'heroes' => $teamBHeroes,
                'stats' => $teamBStats,
                'analysis' => $teamBAnalysis,
                'win_probability' => $winProbabilities['team_b'],
                'strategy' => $teamBStrategy,
            ],
            'phase_analysis' => $this->analyzeGamePhases($teamAStats, $teamBStats),
        ];

        // Enhanced AI analysis (if configured)
        $aiAnalysis = $this->openAIService->generateMatchupAnalysis(
            $teamAHeroes, 
            $teamBHeroes, 
            $basicAnalysis
        );

        // Merge AI insights if available
        if ($aiAnalysis) {
            $basicAnalysis['ai_insights'] = $aiAnalysis;
            $basicAnalysis['ai_powered'] = true;
        } else {
            $basicAnalysis['ai_powered'] = false;
        }

        return $basicAnalysis;
    }

    /**
     * Calculate aggregate team statistics
     */
    protected function calculateTeamStats(array $heroes): array
    {
        $stats = [
            'durability' => 0,
            'offense' => 0,
            'control' => 0,
            'early_game' => 0,
            'mid_game' => 0,
            'late_game' => 0,
        ];

        $roles = [];
        $specialties = [];

        foreach ($heroes as $hero) {
            $stats['durability'] += $hero['durability'];
            $stats['offense'] += $hero['offense'];
            $stats['control'] += $hero['control'];
            $stats['early_game'] += $hero['early_game'];
            $stats['mid_game'] += $hero['mid_game'];
            $stats['late_game'] += $hero['late_game'];

            $roles[] = $hero['role'];
            if (isset($hero['specialties'])) {
                $specialties = array_merge($specialties, $hero['specialties']);
            }
        }

        // Average the stats
        foreach ($stats as $key => $value) {
            $stats[$key] = round($value / 5, 1);
        }

        $stats['role_distribution'] = array_count_values($roles);
        $stats['specialties'] = array_unique($specialties);
        $stats['team_power'] = round(($stats['durability'] + $stats['offense'] + $stats['control']) / 3, 1);

        return $stats;
    }

    /**
     * Analyze counter relationships between teams
     */
    protected function analyzeCounters(array $myTeam, array $enemyTeam): array
    {
        $counters = [
            'advantages' => 0,
            'disadvantages' => 0,
            'synergy_score' => 0,
        ];

        // Check each hero against enemy team
        foreach ($myTeam as $myHero) {
            foreach ($enemyTeam as $enemyHero) {
                // Check if my hero counters enemy
                if (isset($myHero['strong_against'])) {
                    if (in_array($enemyHero['role'], $myHero['strong_against']) || 
                        in_array($enemyHero['slug'], $myHero['strong_against'])) {
                        $counters['advantages']++;
                    }
                    
                    // Check specialty counters
                    if (isset($enemyHero['specialties'])) {
                        foreach ($enemyHero['specialties'] as $specialty) {
                            if (in_array(strtolower($specialty), array_map('strtolower', $myHero['strong_against']))) {
                                $counters['advantages'] += 0.5;
                            }
                        }
                    }
                }

                // Check if enemy counters me
                if (isset($myHero['weak_against'])) {
                    if (in_array($enemyHero['role'], $myHero['weak_against']) || 
                        in_array($enemyHero['slug'], $myHero['weak_against'])) {
                        $counters['disadvantages']++;
                    }
                    
                    // Check specialty weaknesses
                    if (isset($enemyHero['specialties'])) {
                        foreach ($enemyHero['specialties'] as $specialty) {
                            if (in_array(strtolower($specialty), array_map('strtolower', $myHero['weak_against']))) {
                                $counters['disadvantages'] += 0.5;
                            }
                        }
                    }
                }
            }
        }

        // Calculate internal team synergy
        foreach ($myTeam as $hero1) {
            foreach ($myTeam as $hero2) {
                if ($hero1['slug'] === $hero2['slug']) continue;

                if (isset($hero1['synergy_with']) && isset($hero2['role'])) {
                    if (in_array($hero2['role'], $hero1['synergy_with']) || 
                        in_array($hero2['slug'], $hero1['synergy_with'])) {
                        $counters['synergy_score']++;
                    }
                }
            }
        }

        $counters['synergy_score'] = round($counters['synergy_score'] / 2, 1); // Avoid double counting

        return $counters;
    }

    /**
     * Calculate win probabilities for both teams
     */
    protected function calculateWinProbabilities(
        array $teamAStats, 
        array $teamBStats, 
        array $teamACounters, 
        array $teamBCounters
    ): array {
        // Base score from stats
        $teamAScore = $teamAStats['team_power'];
        $teamBScore = $teamBStats['team_power'];

        // Counter advantage/disadvantage weight
        $teamAScore += ($teamACounters['advantages'] * 0.5);
        $teamAScore -= ($teamACounters['disadvantages'] * 0.5);
        $teamBScore += ($teamBCounters['advantages'] * 0.5);
        $teamBScore -= ($teamBCounters['disadvantages'] * 0.5);

        // Synergy bonus
        $teamAScore += ($teamACounters['synergy_score'] * 0.3);
        $teamBScore += ($teamBCounters['synergy_score'] * 0.3);

        // Role composition bonus (balanced team gets bonus)
        $teamARoleBalance = $this->calculateRoleBalance($teamAStats['role_distribution']);
        $teamBRoleBalance = $this->calculateRoleBalance($teamBStats['role_distribution']);
        $teamAScore += $teamARoleBalance;
        $teamBScore += $teamBRoleBalance;

        // Normalize to percentage (0-100)
        $totalScore = $teamAScore + $teamBScore;
        $teamAWinRate = ($teamAScore / $totalScore) * 100;
        $teamBWinRate = ($teamBScore / $totalScore) * 100;

        return [
            'team_a' => round($teamAWinRate, 1),
            'team_b' => round($teamBWinRate, 1),
        ];
    }

    /**
     * Calculate role balance score
     */
    protected function calculateRoleBalance(array $roleDistribution): float
    {
        $idealRoles = ['Tank', 'Fighter', 'Assassin', 'Mage', 'Marksman', 'Support'];
        $uniqueRoles = count($roleDistribution);

        // Bonus for having diverse roles (3-5 different roles is ideal)
        if ($uniqueRoles >= 4) {
            return 1.0;
        } elseif ($uniqueRoles === 3) {
            return 0.5;
        } else {
            return -0.5; // Penalty for too similar composition
        }
    }

    /**
     * Generate team analysis (strengths and weaknesses)
     */
    protected function generateTeamAnalysis(array $heroes, array $stats, array $counters): array
    {
        $strengths = [];
        $weaknesses = [];

        // Analyze stats
        if ($stats['durability'] >= 7) {
            $strengths[] = "High durability - can sustain long team fights";
        } elseif ($stats['durability'] <= 4) {
            $weaknesses[] = "Low durability - vulnerable to burst damage";
        }

        if ($stats['offense'] >= 7) {
            $strengths[] = "High offensive power - strong damage output";
        } elseif ($stats['offense'] <= 4) {
            $weaknesses[] = "Low offensive power - may struggle to secure kills";
        }

        if ($stats['control'] >= 7) {
            $strengths[] = "Excellent crowd control - can lock down enemies";
        } elseif ($stats['control'] <= 4) {
            $weaknesses[] = "Limited crowd control - hard to setup kills";
        }

        // Counter analysis
        if ($counters['advantages'] > $counters['disadvantages']) {
            $strengths[] = "Favorable hero matchups - natural counters to enemy team";
        } elseif ($counters['disadvantages'] > $counters['advantages']) {
            $weaknesses[] = "Unfavorable hero matchups - enemy has counter picks";
        }

        if ($counters['synergy_score'] >= 5) {
            $strengths[] = "Strong internal synergy - heroes complement each other well";
        } elseif ($counters['synergy_score'] <= 2) {
            $weaknesses[] = "Limited synergy - heroes don't complement each other";
        }

        // Role composition
        $uniqueRoles = count($stats['role_distribution']);
        if ($uniqueRoles >= 4) {
            $strengths[] = "Balanced role composition - flexible in team fights";
        } elseif ($uniqueRoles <= 2) {
            $weaknesses[] = "Unbalanced composition - may lack flexibility";
        }

        return [
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
    }

    /**
     * Generate winning strategy for a team
     */
    protected function generateWinningStrategy(
        array $myTeam, 
        array $enemyTeam, 
        array $myStats, 
        array $enemyStats
    ): array {
        $strategies = [];

        // Phase-based strategy
        if ($myStats['early_game'] > $enemyStats['early_game']) {
            $strategies[] = [
                'phase' => 'Early Game',
                'strategy' => 'Apply early pressure and secure objectives. Your team is stronger in the early game.',
                'priority' => 'high',
            ];
        }

        if ($myStats['mid_game'] > $enemyStats['mid_game']) {
            $strategies[] = [
                'phase' => 'Mid Game',
                'strategy' => 'Force team fights and contest objectives. Your mid-game power spike is superior.',
                'priority' => 'high',
            ];
        }

        if ($myStats['late_game'] > $enemyStats['late_game']) {
            $strategies[] = [
                'phase' => 'Late Game',
                'strategy' => 'Farm safely and reach late game. Your team scales better.',
                'priority' => 'high',
            ];
        } else {
            $strategies[] = [
                'phase' => 'Late Game',
                'strategy' => 'End the game before late game. Enemy team scales better than yours.',
                'priority' => 'critical',
            ];
        }

        // Composition-based strategy
        if ($myStats['control'] > $enemyStats['control']) {
            $strategies[] = [
                'phase' => 'Team Fights',
                'strategy' => 'Initiate team fights with your superior crowd control abilities.',
                'priority' => 'medium',
            ];
        }

        if ($myStats['offense'] > $enemyStats['durability']) {
            $strategies[] = [
                'phase' => 'Team Fights',
                'strategy' => 'Burst down enemy carries quickly - they lack durability.',
                'priority' => 'medium',
            ];
        }

        // Role-specific strategies
        $tankCount = $myStats['role_distribution']['Tank'] ?? 0;
        $marksmanCount = $myStats['role_distribution']['Marksman'] ?? 0;

        if ($marksmanCount >= 2) {
            $strategies[] = [
                'phase' => 'Positioning',
                'strategy' => 'Protect your marksmen - they are your main damage source.',
                'priority' => 'high',
            ];
        }

        if ($tankCount === 0) {
            $strategies[] = [
                'phase' => 'Positioning',
                'strategy' => 'Play carefully without a tank. Avoid face-to-face confrontations.',
                'priority' => 'critical',
            ];
        }

        return $strategies;
    }

    /**
     * Analyze game phases (early, mid, late)
     */
    protected function analyzeGamePhases(array $teamAStats, array $teamBStats): array
    {
        return [
            'early_game' => [
                'team_a' => $teamAStats['early_game'],
                'team_b' => $teamBStats['early_game'],
                'advantage' => $teamAStats['early_game'] > $teamBStats['early_game'] ? 'team_a' : 'team_b',
            ],
            'mid_game' => [
                'team_a' => $teamAStats['mid_game'],
                'team_b' => $teamBStats['mid_game'],
                'advantage' => $teamAStats['mid_game'] > $teamBStats['mid_game'] ? 'team_a' : 'team_b',
            ],
            'late_game' => [
                'team_a' => $teamAStats['late_game'],
                'team_b' => $teamBStats['late_game'],
                'advantage' => $teamAStats['late_game'] > $teamBStats['late_game'] ? 'team_a' : 'team_b',
            ],
        ];
    }
}
