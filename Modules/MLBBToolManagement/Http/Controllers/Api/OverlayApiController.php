<?php

namespace Modules\MLBBToolManagement\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MLBBToolManagement\Services\OverlaySyncService;
use Modules\MLBBToolManagement\Models\Match;

/**
 * Overlay API Controller
 * 
 * Handles API endpoints for overlay system
 */
class OverlayApiController extends Controller
{
    protected OverlaySyncService $overlaySyncService;

    public function __construct(OverlaySyncService $overlaySyncService)
    {
        $this->overlaySyncService = $overlaySyncService;
    }

    /**
     * Get match data
     */
    public function getMatch($matchId)
    {
        try {
            $matchState = $this->overlaySyncService->getMatchStateWithDetails($matchId);

            return response()->json([
                'success' => true,
                'data' => $matchState,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found',
            ], 404);
        }
    }

    /**
     * Sync match state (for external apps)
     */
    public function syncMatch(Request $request, $matchId)
    {
        try {
            $match = Match::findOrFail($matchId);
            $matchState = $this->overlaySyncService->getMatchStateWithDetails($matchId);

            return response()->json([
                'success' => true,
                'data' => $matchState,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync match',
            ], 500);
        }
    }
}
