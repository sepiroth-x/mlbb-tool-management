<?php

namespace Modules\MLBBToolManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroStatistic extends Model
{
    use HasFactory;

    protected $table = 'mlbb_hero_statistics';

    protected $fillable = [
        'hero_slug',
        'hero_name',
        'hero_role',
        'times_picked',
        'times_banned',
        'times_won',
        'times_lost',
        'win_rate',
        'pick_rate',
        'ban_rate',
        'avg_matchup_impact',
        'avg_early_game_impact',
        'avg_mid_game_impact',
        'avg_late_game_impact',
        'frequently_picked_with',
        'frequently_countered_by',
        'last_picked_at',
    ];

    protected $casts = [
        'times_picked' => 'integer',
        'times_banned' => 'integer',
        'times_won' => 'integer',
        'times_lost' => 'integer',
        'win_rate' => 'decimal:2',
        'pick_rate' => 'decimal:2',
        'ban_rate' => 'decimal:2',
        'avg_matchup_impact' => 'decimal:2',
        'avg_early_game_impact' => 'decimal:2',
        'avg_mid_game_impact' => 'decimal:2',
        'avg_late_game_impact' => 'decimal:2',
        'frequently_picked_with' => 'array',
        'frequently_countered_by' => 'array',
        'last_picked_at' => 'datetime',
    ];

    /**
     * Find or create hero statistic record
     */
    public static function findOrCreateHero(string $slug, string $name, string $role): self
    {
        return self::firstOrCreate(
            ['hero_slug' => $slug],
            [
                'hero_name' => $name,
                'hero_role' => $role,
                'times_picked' => 0,
            ]
        );
    }

    /**
     * Record a pick for this hero
     */
    public function recordPick(array $teammates = [], array $opponents = [], bool $won = null): void
    {
        $this->increment('times_picked');
        
        if ($won === true) {
            $this->increment('times_won');
        } elseif ($won === false) {
            $this->increment('times_lost');
        }
        
        // Calculate win rate
        $total = $this->times_won + $this->times_lost;
        if ($total > 0) {
            $this->win_rate = ($this->times_won / $total) * 100;
        }
        
        // Track teammates
        if (!empty($teammates)) {
            $pickedWith = $this->frequently_picked_with ?? [];
            
            foreach ($teammates as $teammate) {
                if ($teammate !== $this->hero_slug) {
                    if (isset($pickedWith[$teammate])) {
                        $pickedWith[$teammate]++;
                    } else {
                        $pickedWith[$teammate] = 1;
                    }
                }
            }
            
            // Keep only top 10 most frequent
            arsort($pickedWith);
            $this->frequently_picked_with = array_slice($pickedWith, 0, 10, true);
        }
        
        // Track opponents/counters
        if (!empty($opponents)) {
            $counteredBy = $this->frequently_countered_by ?? [];
            
            foreach ($opponents as $opponent) {
                if (isset($counteredBy[$opponent])) {
                    $counteredBy[$opponent]++;
                } else {
                    $counteredBy[$opponent] = 1;
                }
            }
            
            // Keep only top 10 most frequent
            arsort($counteredBy);
            $this->frequently_countered_by = array_slice($counteredBy, 0, 10, true);
        }
        
        $this->last_picked_at = now();
        $this->save();
    }

    /**
     * Record a ban for this hero
     */
    public function recordBan(): void
    {
        $this->increment('times_banned');
        $this->save();
    }

    /**
     * Update performance metrics from matchup analysis
     */
    public function updatePerformanceMetrics(array $metrics): void
    {
        $n = $this->times_picked;
        
        if ($n > 0) {
            if (isset($metrics['matchup_impact'])) {
                $this->avg_matchup_impact = (($this->avg_matchup_impact * ($n - 1)) + $metrics['matchup_impact']) / $n;
            }
            
            if (isset($metrics['early_game'])) {
                $this->avg_early_game_impact = (($this->avg_early_game_impact * ($n - 1)) + $metrics['early_game']) / $n;
            }
            
            if (isset($metrics['mid_game'])) {
                $this->avg_mid_game_impact = (($this->avg_mid_game_impact * ($n - 1)) + $metrics['mid_game']) / $n;
            }
            
            if (isset($metrics['late_game'])) {
                $this->avg_late_game_impact = (($this->avg_late_game_impact * ($n - 1)) + $metrics['late_game']) / $n;
            }
            
            $this->save();
        }
    }

    /**
     * Get top picked heroes
     */
    public static function getTopPicked(int $limit = 10)
    {
        return self::orderBy('times_picked', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get highest win rate heroes (with minimum picks)
     */
    public static function getHighestWinRate(int $limit = 10, int $minPicks = 10)
    {
        return self::where('times_picked', '>=', $minPicks)
            ->orderBy('win_rate', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get most banned heroes
     */
    public static function getMostBanned(int $limit = 10)
    {
        return self::orderBy('times_banned', 'desc')
            ->limit($limit)
            ->get();
    }
}
