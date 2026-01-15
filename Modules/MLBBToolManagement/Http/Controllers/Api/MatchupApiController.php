<?php

namespace Modules\MLBBToolManagement\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MLBBToolManagement\Services\HeroDataService;
use Modules\MLBBToolManagement\Services\MatchupAnalyzerService;
use Modules\MLBBToolManagement\Services\OpenAIService;

/**
 * Matchup API Controller
 * 
 * Handles API endpoints for matchup analysis
 */
class MatchupApiController extends Controller
{
    protected HeroDataService $heroDataService;
    protected MatchupAnalyzerService $matchupAnalyzerService;
    protected OpenAIService $openAIService;

    public function __construct(
        HeroDataService $heroDataService,
        MatchupAnalyzerService $matchupAnalyzerService,
        OpenAIService $openAIService
    ) {
        $this->heroDataService = $heroDataService;
        $this->matchupAnalyzerService = $matchupAnalyzerService;
        $this->openAIService = $openAIService;
    }

    /**
     * Get all heroes
     */
    public function getHeroes(Request $request)
    {
        $role = $request->query('role');

        if ($role) {
            $heroes = $this->heroDataService->getHeroesByRole($role);
        } else {
            $heroes = $this->heroDataService->getAllHeroes();
        }

        return response()->json([
            'success' => true,
            'data' => array_values($heroes),
        ]);
    }

    /**
     * Analyze team matchup
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'team_a' => 'required|array|size:5',
            'team_a.*' => 'required|string',
            'team_b' => 'required|array|size:5',
            'team_b.*' => 'required|string',
        ]);

        try {
            $analysis = $this->matchupAnalyzerService->analyzeMatchup(
                $request->input('team_a'),
                $request->input('team_b')
            );

            return response()->json([
                'success' => true,
                'data' => $analysis,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during analysis',
            ], 500);
        }
    }

    /**
     * Handle chat messages about matchup analysis
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'context' => 'required|array',
            'context.teamA' => 'required|array',
            'context.teamB' => 'required|array',
            'history' => 'array',
        ]);

        try {
            $response = $this->openAIService->handleMatchupChat(
                $request->input('message'),
                $request->input('context'),
                $request->input('history', [])
            );

            return response()->json([
                'success' => true,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            \Log::error('Matchup chat error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'response' => 'I apologize, but I\'m having trouble responding right now. Please try again.',
            ], 500);
        }
    }
}
