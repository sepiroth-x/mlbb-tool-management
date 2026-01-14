<?php

namespace Modules\MLBBToolManagement\Services;

use Illuminate\Support\Facades\Cache;
use Modules\MLBBToolManagement\Models\Match;

/**
 * Overlay Sync Service
 * 
 * Handles real-time synchronization of match state for overlay display
 */
class OverlaySyncService
{
    protected HeroDataService $heroDataService;

    public function __construct(HeroDataService $heroDataService)
    {
        $this->heroDataService = $heroDataService;
    }

    /**
     * Get match state with hero details
     */
    public function getMatchStateWithDetails(int $matchId): array
    {
        $cacheKey = "mlbb_match_state_{$matchId}";
        $cacheTtl = 5; // 5 seconds

        return Cache::remember($cacheKey, $cacheTtl, function () use ($matchId) {
            $match = Match::findOrFail($matchId);
            $state = $match->getMatchState();

            // Enrich with hero details
            $state['team_a']['picks_details'] = $this->enrichHeroDetails($state['team_a']['picks']);
            $state['team_a']['bans_details'] = $this->enrichHeroDetails($state['team_a']['bans']);
            $state['team_b']['picks_details'] = $this->enrichHeroDetails($state['team_b']['picks']);
            $state['team_b']['bans_details'] = $this->enrichHeroDetails($state['team_b']['bans']);

            return $state;
        });
    }

    /**
     * Enrich hero slugs with full hero data
     */
    protected function enrichHeroDetails(array $heroSlugs): array
    {
        if (empty($heroSlugs)) {
            return [];
        }

        $heroes = $this->heroDataService->getHeroesBySlugs($heroSlugs);
        $enriched = [];

        foreach ($heroSlugs as $slug) {
            foreach ($heroes as $hero) {
                if ($hero['slug'] === $slug) {
                    $enriched[] = [
                        'slug' => $hero['slug'],
                        'name' => $hero['name'],
                        'role' => $hero['role'],
                        'image' => $this->heroDataService->getHeroImageUrl($slug),
                    ];
                    break;
                }
            }
        }

        return $enriched;
    }

    /**
     * Clear match state cache
     */
    public function clearMatchCache(int $matchId): void
    {
        Cache::forget("mlbb_match_state_{$matchId}");
    }

    /**
     * Broadcast match update (placeholder for WebSocket implementation)
     */
    public function broadcastMatchUpdate(int $matchId, string $action, array $data): void
    {
        // Clear cache first
        $this->clearMatchCache($matchId);

        // TODO: Implement WebSocket broadcast if using Pusher/Laravel Echo
        // event(new MatchUpdated($matchId, $action, $data));
        
        // For now, polling will handle updates by checking the database
    }

    /**
     * Get available heroes for selection (not picked or banned)
     */
    public function getAvailableHeroes(int $matchId): array
    {
        $match = Match::findOrFail($matchId);
        $allHeroes = $this->heroDataService->getAllHeroes();

        $unavailable = array_merge(
            $match->team_a_picks ?? [],
            $match->team_b_picks ?? [],
            $match->team_a_bans ?? [],
            $match->team_b_bans ?? []
        );

        return array_filter($allHeroes, function ($hero) use ($unavailable) {
            return !in_array($hero['slug'], $unavailable);
        });
    }

    /**
     * Validate pick/ban action
     */
    public function validateAction(Match $match, string $action, string $team): bool
    {
        if ($action === 'pick') {
            $field = $team === 'a' ? 'team_a_picks' : 'team_b_picks';
            $current = $match->$field ?? [];
            return count($current) < 5;
        }

        if ($action === 'ban') {
            $field = $team === 'a' ? 'team_a_bans' : 'team_b_bans';
            $current = $match->$field ?? [];
            return count($current) < 3;
        }

        return false;
    }

    /**
     * Execute pick action
     */
    public function executePick(int $matchId, string $team, string $heroSlug): array
    {
        $match = Match::findOrFail($matchId);

        if (!$this->validateAction($match, 'pick', $team)) {
            throw new \Exception("Cannot add more picks for this team");
        }

        if (!$match->isHeroAvailable($heroSlug)) {
            throw new \Exception("Hero is not available for selection");
        }

        $match->addPick($team, $heroSlug);
        $match->save();

        $this->broadcastMatchUpdate($matchId, 'pick', [
            'team' => $team,
            'hero' => $heroSlug,
        ]);

        return $this->getMatchStateWithDetails($matchId);
    }

    /**
     * Execute ban action
     */
    public function executeBan(int $matchId, string $team, string $heroSlug): array
    {
        $match = Match::findOrFail($matchId);

        if (!$this->validateAction($match, 'ban', $team)) {
            throw new \Exception("Cannot add more bans for this team");
        }

        if (!$match->isHeroAvailable($heroSlug)) {
            throw new \Exception("Hero is not available for selection");
        }

        $match->addBan($team, $heroSlug);
        $match->save();

        $this->broadcastMatchUpdate($matchId, 'ban', [
            'team' => $team,
            'hero' => $heroSlug,
        ]);

        return $this->getMatchStateWithDetails($matchId);
    }

    /**
     * Undo last action
     */
    public function undoLastAction(int $matchId): array
    {
        $match = Match::findOrFail($matchId);
        
        if (!$match->undoLastAction()) {
            throw new \Exception("No actions to undo");
        }

        $match->save();

        $this->broadcastMatchUpdate($matchId, 'undo', []);

        return $this->getMatchStateWithDetails($matchId);
    }

    /**
     * Reset match
     */
    public function resetMatch(int $matchId): array
    {
        $match = Match::findOrFail($matchId);
        $match->resetMatch();
        $match->save();

        $this->broadcastMatchUpdate($matchId, 'reset', []);

        return $this->getMatchStateWithDetails($matchId);
    }

    /**
     * Set match phase
     */
    public function setPhase(int $matchId, string $phase): array
    {
        $match = Match::findOrFail($matchId);
        
        $validPhases = ['ban', 'pick', 'locked'];
        if (!in_array($phase, $validPhases)) {
            throw new \Exception("Invalid phase. Must be one of: " . implode(', ', $validPhases));
        }

        $match->current_phase = $phase;
        $match->save();

        $this->broadcastMatchUpdate($matchId, 'phase', ['phase' => $phase]);

        return $this->getMatchStateWithDetails($matchId);
    }

    /**
     * Create new match
     */
    public function createMatch(array $data): Match
    {
        $match = Match::create([
            'name' => $data['name'],
            'team_a_name' => $data['team_a_name'] ?? 'Team A',
            'team_b_name' => $data['team_b_name'] ?? 'Team B',
            'status' => 'active',
            'current_phase' => 'ban',
            'created_by' => auth()->id(),
            'started_at' => now(),
        ]);

        return $match;
    }
}
