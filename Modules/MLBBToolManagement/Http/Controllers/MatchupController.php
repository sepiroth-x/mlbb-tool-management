<?php

namespace Modules\MLBBToolManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MLBBToolManagement\Services\HeroDataService;
use Modules\MLBBToolManagement\Services\MatchupAnalyzerService;

/**
 * Matchup Controller
 * 
 * Handles team matchup probability analysis tool
 */
class MatchupController extends Controller
{
    protected HeroDataService $heroDataService;
    protected MatchupAnalyzerService $matchupAnalyzerService;

    public function __construct(
        HeroDataService $heroDataService,
        MatchupAnalyzerService $matchupAnalyzerService
    ) {
        $this->heroDataService = $heroDataService;
        $this->matchupAnalyzerService = $matchupAnalyzerService;
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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred during analysis',
                ], 500);
            }

            return back()->withErrors(['error' => 'An error occurred during analysis']);
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
}
