<?php

namespace Modules\MLBBToolManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LineupStatistic extends Model
{
    use HasFactory;

    protected $table = 'mlbb_lineup_statistics';

    protected $fillable = [
        'lineup_heroes',
        'lineup_hash',
        'role_composition',
        'times_analyzed',
        'times_won',
        'times_lost',
        'win_rate',
        'avg_matchup_score',
        'avg_early_game',
        'avg_mid_game',
        'avg_late_game',
        'synergy_score',
        'durability_avg',
        'offense_avg',
        'control_avg',
        'common_opponents',
        'last_analyzed_at',
    ];

    protected $casts = [
        'lineup_heroes' => 'array',
        'common_opponents' => 'array',
        'times_analyzed' => 'integer',
        'times_won' => 'integer',
        'times_lost' => 'integer',
        'win_rate' => 'decimal:2',
        'avg_matchup_score' => 'decimal:2',
        'avg_early_game' => 'decimal:2',
        'avg_mid_game' => 'decimal:2',
        'avg_late_game' => 'decimal:2',
        'synergy_score' => 'decimal:2',
        'durability_avg' => 'decimal:2',
        'offense_avg' => 'decimal:2',
        'control_avg' => 'decimal:2',
        'last_analyzed_at' => 'datetime',
    ];

    /**
     * Generate a unique hash for a lineup
     */
    public static function generateLineupHash(array $heroSlugs): string
    {
        sort($heroSlugs); // Sort to ensure consistency
        return md5(implode('-', $heroSlugs));
    }

    /**
     * Find or create a lineup statistic record
     */
    public static function findOrCreateLineup(array $heroSlugs): self
    {
        $hash = self::generateLineupHash($heroSlugs);
        sort($heroSlugs);
        
        return self::firstOrCreate(
            ['lineup_hash' => $hash],
            [
                'lineup_heroes' => $heroSlugs,
                'role_composition' => '', // Will be set by caller
                'times_analyzed' => 0,
            ]
        );
    }

    /**
     * Update statistics after an analysis
     */
    public function recordAnalysis(array $analysisData): void
    {
        $this->increment('times_analyzed');
        
        // Update win/loss based on matchup score
        if (isset($analysisData['matchup_advantage'])) {
            if ($analysisData['matchup_advantage'] > 0) {
                $this->increment('times_won');
            } elseif ($analysisData['matchup_advantage'] < 0) {
                $this->increment('times_lost');
            }
        }
        
        // Calculate win rate
        $total = $this->times_won + $this->times_lost;
        if ($total > 0) {
            $this->win_rate = ($this->times_won / $total) * 100;
        }
        
        // Update averages using running average formula
        $n = $this->times_analyzed;
        
        if (isset($analysisData['matchup_advantage'])) {
            $this->avg_matchup_score = (($this->avg_matchup_score * ($n - 1)) + $analysisData['matchup_advantage']) / $n;
        }
        
        if (isset($analysisData['early_game'])) {
            $this->avg_early_game = (($this->avg_early_game * ($n - 1)) + $analysisData['early_game']) / $n;
        }
        
        if (isset($analysisData['mid_game'])) {
            $this->avg_mid_game = (($this->avg_mid_game * ($n - 1)) + $analysisData['mid_game']) / $n;
        }
        
        if (isset($analysisData['late_game'])) {
            $this->avg_late_game = (($this->avg_late_game * ($n - 1)) + $analysisData['late_game']) / $n;
        }
        
        if (isset($analysisData['synergy_score'])) {
            $this->synergy_score = (($this->synergy_score * ($n - 1)) + $analysisData['synergy_score']) / $n;
        }
        
        // Track opponent lineup
        if (isset($analysisData['opponent_hash'])) {
            $opponents = $this->common_opponents ?? [];
            $opponentHash = $analysisData['opponent_hash'];
            
            if (isset($opponents[$opponentHash])) {
                $opponents[$opponentHash]++;
            } else {
                $opponents[$opponentHash] = 1;
            }
            
            $this->common_opponents = $opponents;
        }
        
        $this->last_analyzed_at = now();
        $this->save();
    }

    /**
     * Get top performing lineups
     */
    public static function getTopLineups(int $limit = 10)
    {
        return self::where('times_analyzed', '>=', 5) // Minimum analyses for statistical significance
            ->orderBy('win_rate', 'desc')
            ->orderBy('times_analyzed', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get trending lineups (recently analyzed and performing well)
     */
    public static function getTrendingLineups(int $limit = 10)
    {
        return self::where('last_analyzed_at', '>=', now()->subDays(7))
            ->where('times_analyzed', '>=', 3)
            ->orderBy('win_rate', 'desc')
            ->orderBy('last_analyzed_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
