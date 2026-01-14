<?php

namespace Modules\MLBBToolManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MLBBToolManagement\Services\HeroDataService;
use Modules\MLBBToolManagement\Services\OverlaySyncService;
use Modules\MLBBToolManagement\Models\Match as MatchModel;

/**
 * Overlay Controller
 * 
 * Handles live pick/ban overlay system for tournament streaming
 */
class OverlayController extends Controller
{
    protected HeroDataService $heroDataService;
    protected OverlaySyncService $overlaySyncService;

    public function __construct(
        HeroDataService $heroDataService,
        OverlaySyncService $overlaySyncService
    ) {
        $this->heroDataService = $heroDataService;
        $this->overlaySyncService = $overlaySyncService;
    }

    /**
     * Display admin panel for managing overlay
     */
    public function admin()
    {
        $matches = MatchModel::orderBy('created_at', 'desc')->take(20)->get();
        $heroes = $this->heroDataService->getAllHeroes();

        return view('mlbb-tool-management::overlay.admin', [
            'matches' => $matches,
            'heroes' => $heroes,
        ]);
    }

    /**
     * Display overlay for streaming (public, no auth)
     */
    public function display($matchId)
    {
        try {
            $matchState = $this->overlaySyncService->getMatchStateWithDetails($matchId);

            return view('mlbb-tool-management::overlay.display', [
                'matchState' => $matchState,
                'matchId' => $matchId,
            ]);
        } catch (\Exception $e) {
            return response()->view('mlbb-tool-management::overlay.error', [
                'message' => 'Match not found or error loading overlay',
            ], 404);
        }
    }

    /**
     * Get match state (AJAX endpoint for polling)
     */
    public function getMatchState($matchId)
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
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Create new match
     */
    public function createMatch(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_a_name' => 'nullable|string|max:100',
            'team_b_name' => 'nullable|string|max:100',
        ]);

        try {
            $match = $this->overlaySyncService->createMatch($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Match created successfully',
                'data' => $match,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create match: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Select active match for admin panel
     */
    public function selectMatch($matchId)
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
     * Pick hero for a team
     */
    public function pickHero(Request $request, $matchId)
    {
        $request->validate([
            'team' => 'required|in:a,b',
            'hero' => 'required|string',
        ]);

        try {
            $matchState = $this->overlaySyncService->executePick(
                $matchId,
                $request->input('team'),
                $request->input('hero')
            );

            return response()->json([
                'success' => true,
                'message' => 'Hero picked successfully',
                'data' => $matchState,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Ban hero for a team
     */
    public function banHero(Request $request, $matchId)
    {
        $request->validate([
            'team' => 'required|in:a,b',
            'hero' => 'required|string',
        ]);

        try {
            $matchState = $this->overlaySyncService->executeBan(
                $matchId,
                $request->input('team'),
                $request->input('hero')
            );

            return response()->json([
                'success' => true,
                'message' => 'Hero banned successfully',
                'data' => $matchState,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Undo last action
     */
    public function undoAction($matchId)
    {
        try {
            $matchState = $this->overlaySyncService->undoLastAction($matchId);

            return response()->json([
                'success' => true,
                'message' => 'Action undone successfully',
                'data' => $matchState,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reset match to initial state
     */
    public function resetMatch($matchId)
    {
        try {
            $matchState = $this->overlaySyncService->resetMatch($matchId);

            return response()->json([
                'success' => true,
                'message' => 'Match reset successfully',
                'data' => $matchState,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set match phase (ban/pick/locked)
     */
    public function setPhase(Request $request, $matchId)
    {
        $request->validate([
            'phase' => 'required|in:ban,pick,locked',
        ]);

        try {
            $matchState = $this->overlaySyncService->setPhase(
                $matchId,
                $request->input('phase')
            );

            return response()->json([
                'success' => true,
                'message' => 'Phase updated successfully',
                'data' => $matchState,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get all matches
     */
    public function getMatches(Request $request)
    {
        $status = $request->query('status');
        
        $query = MatchModel::orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }

        $matches = $query->take(50)->get();

        return response()->json([
            'success' => true,
            'data' => $matches,
        ]);
    }
}
