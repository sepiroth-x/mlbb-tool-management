<?php

namespace Modules\MLBBToolManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hero extends Model
{
    use HasFactory;

    protected $table = 'mlbb_heroes';

    protected $fillable = [
        'name',
        'slug',
        'role',
        'image_path',
        'durability',
        'offense',
        'control',
        'difficulty',
        'early_game',
        'mid_game',
        'late_game',
        'specialties',
        'strong_against',
        'weak_against',
        'synergy_with',
        'description',
        'is_active',
    ];

    protected $casts = [
        'specialties' => 'array',
        'strong_against' => 'array',
        'weak_against' => 'array',
        'synergy_with' => 'array',
        'is_active' => 'boolean',
        'durability' => 'integer',
        'offense' => 'integer',
        'control' => 'integer',
        'difficulty' => 'integer',
        'early_game' => 'integer',
        'mid_game' => 'integer',
        'late_game' => 'integer',
    ];

    /**
     * Scope to get only active heroes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get overall power rating
     */
    public function getPowerRatingAttribute()
    {
        return round(
            ($this->durability + $this->offense + $this->control) / 3,
            1
        );
    }

    /**
     * Get game phase strengths as array
     */
    public function getPhaseStrengthsAttribute()
    {
        return [
            'early' => $this->early_game,
            'mid' => $this->mid_game,
            'late' => $this->late_game,
        ];
    }

    /**
     * Check if hero is strong against specific tags
     */
    public function isStrongAgainst(array $tags): bool
    {
        if (empty($this->strong_against)) {
            return false;
        }

        return count(array_intersect($this->strong_against, $tags)) > 0;
    }

    /**
     * Check if hero is weak against specific tags
     */
    public function isWeakAgainst(array $tags): bool
    {
        if (empty($this->weak_against)) {
            return false;
        }

        return count(array_intersect($this->weak_against, $tags)) > 0;
    }

    /**
     * Check if hero has synergy with specific tags
     */
    public function hasSynergyWith(array $tags): bool
    {
        if (empty($this->synergy_with)) {
            return false;
        }

        return count(array_intersect($this->synergy_with, $tags)) > 0;
    }

    /**
     * Get hero image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('modules/mlbb-tool-management/images/heroes/' . $this->image_path);
        }
        
        return asset('modules/mlbb-tool-management/images/heroes/default.png');
    }
}
