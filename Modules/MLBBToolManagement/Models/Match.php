<?php

namespace Modules\MLBBToolManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Match extends Model
{
    use HasFactory;

    protected $table = 'mlbb_matches';

    protected $fillable = [
        'name',
        'team_a_name',
        'team_b_name',
        'status',
        'current_phase',
        'team_a_picks',
        'team_b_picks',
        'team_a_bans',
        'team_b_bans',
        'action_history',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'team_a_picks' => 'array',
        'team_b_picks' => 'array',
        'team_a_bans' => 'array',
        'team_b_bans' => 'array',
        'action_history' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationship with user who created the match
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active matches
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for pending matches
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Initialize empty arrays for a new match
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($match) {
            $match->team_a_picks = $match->team_a_picks ?? [];
            $match->team_b_picks = $match->team_b_picks ?? [];
            $match->team_a_bans = $match->team_a_bans ?? [];
            $match->team_b_bans = $match->team_b_bans ?? [];
            $match->action_history = $match->action_history ?? [];
        });
    }

    /**
     * Add a pick action
     */
    public function addPick(string $team, string $heroSlug)
    {
        $field = $team === 'a' ? 'team_a_picks' : 'team_b_picks';
        $picks = $this->$field ?? [];
        
        if (count($picks) >= 5) {
            throw new \Exception("Team already has 5 picks");
        }

        $picks[] = $heroSlug;
        $this->$field = $picks;
        
        $this->addToHistory('pick', $team, $heroSlug);
    }

    /**
     * Add a ban action
     */
    public function addBan(string $team, string $heroSlug)
    {
        $field = $team === 'a' ? 'team_a_bans' : 'team_b_bans';
        $bans = $this->$field ?? [];
        
        if (count($bans) >= 3) {
            throw new \Exception("Team already has 3 bans");
        }

        $bans[] = $heroSlug;
        $this->$field = $bans;
        
        $this->addToHistory('ban', $team, $heroSlug);
    }

    /**
     * Add action to history
     */
    protected function addToHistory(string $action, string $team, string $heroSlug)
    {
        $history = $this->action_history ?? [];
        $history[] = [
            'action' => $action,
            'team' => $team,
            'hero' => $heroSlug,
            'timestamp' => now()->toIso8601String(),
        ];
        $this->action_history = $history;
    }

    /**
     * Undo last action
     */
    public function undoLastAction()
    {
        $history = $this->action_history ?? [];
        
        if (empty($history)) {
            return false;
        }

        $lastAction = array_pop($history);
        $this->action_history = $history;

        $field = $lastAction['team'] === 'a' 
            ? 'team_' . $lastAction['team'] . '_' . $lastAction['action'] . 's'
            : 'team_' . $lastAction['team'] . '_' . $lastAction['action'] . 's';

        $items = $this->$field ?? [];
        $key = array_search($lastAction['hero'], $items);
        
        if ($key !== false) {
            unset($items[$key]);
            $this->$field = array_values($items);
        }

        return true;
    }

    /**
     * Reset match to initial state
     */
    public function resetMatch()
    {
        $this->team_a_picks = [];
        $this->team_b_picks = [];
        $this->team_a_bans = [];
        $this->team_b_bans = [];
        $this->action_history = [];
        $this->current_phase = 'ban';
        $this->status = 'active';
    }

    /**
     * Get all banned heroes (both teams)
     */
    public function getBannedHeroesAttribute()
    {
        return array_merge(
            $this->team_a_bans ?? [],
            $this->team_b_bans ?? []
        );
    }

    /**
     * Get all picked heroes (both teams)
     */
    public function getPickedHeroesAttribute()
    {
        return array_merge(
            $this->team_a_picks ?? [],
            $this->team_b_picks ?? []
        );
    }

    /**
     * Check if hero is available for selection
     */
    public function isHeroAvailable(string $heroSlug): bool
    {
        return !in_array($heroSlug, $this->banned_heroes) 
            && !in_array($heroSlug, $this->picked_heroes);
    }

    /**
     * Get match state for overlay display
     */
    public function getMatchState(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'team_a' => [
                'name' => $this->team_a_name,
                'picks' => $this->team_a_picks ?? [],
                'bans' => $this->team_a_bans ?? [],
            ],
            'team_b' => [
                'name' => $this->team_b_name,
                'picks' => $this->team_b_picks ?? [],
                'bans' => $this->team_b_bans ?? [],
            ],
            'status' => $this->status,
            'phase' => $this->current_phase,
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
