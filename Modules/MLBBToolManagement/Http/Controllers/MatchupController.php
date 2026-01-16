<?php

namespace Modules\MLBBToolManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MLBBToolManagement\Services\HeroDataService;
use Modules\MLBBToolManagement\Services\MatchupAnalyzerService;
use Modules\MLBBToolManagement\Services\StatisticsTrackingService;

/**
 * Matchup Controller
 * 
 * Handles team matchup probability analysis tool
 */
class MatchupController extends Controller
{
    protected HeroDataService $heroDataService;
    protected MatchupAnalyzerService $matchupAnalyzerService;
    protected StatisticsTrackingService $statisticsService;

    public function __construct(
        HeroDataService $heroDataService,
        MatchupAnalyzerService $matchupAnalyzerService,
        StatisticsTrackingService $statisticsService
    ) {
        $this->heroDataService = $heroDataService;
        $this->matchupAnalyzerService = $matchupAnalyzerService;
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display the matchup tool page
     */
    public function index()
    {
        $heroes = $this->heroDataService->getAllHeroes();
        $roles = $this->heroDataService->getAllRoles();

        return view('mlbb-tool-management::matchup.index', [
            'heroes' => $heroes,
            'roles' => $roles,
        ]);
    }

    /**
     * Analyze matchup between two teams
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

            // Track statistics in background
            try {
                $this->statisticsService->trackMatchupAnalysis(
                    $analysis['team_a']['heroes'],
                    $analysis['team_b']['heroes'],
                    $analysis
                );
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::warning('Failed to track matchup statistics', [
                    'error' => $e->getMessage()
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $analysis,
                ]);
            }

            return view('mlbb-tool-management::matchup.results', [
                'analysis' => $analysis,
            ]);

        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Matchup analysis error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = config('app.debug') 
                ? $e->getMessage() 
                : 'An error occurred during analysis';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ], 500);
            }

            return back()->withErrors(['error' => $errorMessage]);
        }
    }

    /**
     * Get all heroes (AJAX endpoint)
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
     * Chat with AI about matchup analysis
     */
    public function chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'analysis' => 'required|array',
            ]);

            $openaiService = app(\Modules\MLBBToolManagement\Services\OpenAIService::class);
            $response = $openaiService->handleMatchupChat(
                $request->input('message'),
                $request->input('analysis'),
                [] // conversation history
            );

            return response()->json([
                'success' => true,
                'message' => $response,
            ]);
        } catch (\Exception $e) {
            \Log::error('AI Chat error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
    
    /**
     * Get top performing lineups
     */
    public function getTopLineups(Request $request)
    {
        $limit = $request->query('limit', 10);
        $lineups = $this->statisticsService->getTopLineups($limit);
        
        return response()->json([
            'success' => true,
            'data' => $lineups,
        ]);
    }
    
    /**
     * Get detailed lineup statistics
     */
    public function getLineupDetails(Request $request)
    {
        $heroes = $request->query('heroes'); // Comma-separated hero slugs
        
        if (!$heroes) {
            return response()->json([
                'success' => false,
                'message' => 'Heroes parameter is required',
            ], 400);
        }
        
        $heroSlugs = explode(',', $heroes);
        
        if (count($heroSlugs) !== 5) {
            return response()->json([
                'success' => false,
                'message' => 'Exactly 5 heroes are required',
            ], 400);
        }
        
        $lineup = \Modules\MLBBToolManagement\Models\LineupStatistic::where(
            'lineup_hash',
            \Modules\MLBBToolManagement\Models\LineupStatistic::generateLineupHash($heroSlugs)
        )->first();
        
        if (!$lineup) {
            return response()->json([
                'success' => false,
                'message' => 'No statistics found for this lineup',
            ], 404);
        }
        
        // Get hero details
        $heroesData = $this->heroDataService->getHeroesBySlugs($heroSlugs);
        
        return response()->json([
            'success' => true,
            'data' => [
                'lineup' => $lineup,
                'heroes' => $heroesData,
            ],
        ]);
    }
    
    /**
     * Get hero statistics
     */
    public function getHeroStatistics(string $slug)
    {
        $stats = $this->statisticsService->getHeroStatistics($slug);
        
        if (!$stats) {
            return response()->json([
                'success' => false,
                'message' => 'No statistics found for this hero',
            ], 404);
        }
        
        // Get hero details
        $heroData = $this->heroDataService->getHeroBySlug($slug);
        
        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'hero' => $heroData,
            ],
        ]);
    }
    
    /**
     * Get statistics dashboard data
     */
    public function getStatisticsDashboard()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'top_lineups' => $this->statisticsService->getTopLineups(10),
                'trending_lineups' => $this->statisticsService->getTrendingLineups(10),
                'top_picked_heroes' => $this->statisticsService->getTopPickedHeroes(10),
                'highest_winrate_heroes' => $this->statisticsService->getHighestWinRateHeroes(10),
                'most_banned_heroes' => $this->statisticsService->getMostBannedHeroes(10),
            ],
        ]);
    }
}
