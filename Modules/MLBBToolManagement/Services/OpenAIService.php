<?php

namespace Modules\MLBBToolManagement\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AI Service
 * 
 * Handles communication with AI providers (OpenAI or GitHub Models)
 * Supports multiple AI backends for matchup analysis
 */
class OpenAIService
{
    protected string $provider;
    protected string $apiKey;
    protected string $model;
    protected int $maxTokens;
    protected float $temperature;
    protected string $apiUrl;

    public function __construct()
    {
        // Use env() directly to avoid container issues
        $this->provider = env('AI_PROVIDER', 'github');
        
        if ($this->provider === 'github') {
            $this->apiKey = env('GITHUB_TOKEN');
            $this->model = env('GITHUB_MODEL', 'gpt-4o-mini');
            $this->maxTokens = (int) env('GITHUB_MAX_TOKENS', 500);
            $this->temperature = (float) env('GITHUB_TEMPERATURE', 0.7);
            $this->apiUrl = 'https://models.inference.ai.azure.com/chat/completions';
        } else {
            $this->apiKey = env('OPENAI_API_KEY');
            $this->model = env('OPENAI_MODEL', 'gpt-3.5-turbo');
            $this->maxTokens = (int) env('OPENAI_MAX_TOKENS', 500);
            $this->temperature = (float) env('OPENAI_TEMPERATURE', 0.7);
            $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
        }
    }

    /**
     * Check if AI API is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Generate AI analysis for matchup
     * 
     * @param array $teamAHeroes Team A hero data
     * @param array $teamBHeroes Team B hero data
     * @param array $basicAnalysis Basic rule-based analysis
     * @return array|null AI-enhanced analysis or null if API not configured
     */
    public function generateMatchupAnalysis(
        array $teamAHeroes, 
        array $teamBHeroes, 
        array $basicAnalysis
    ): ?array {
        if (!$this->isConfigured()) {
            Log::info('OpenAI API not configured, skipping AI analysis');
            return null;
        }

        try {
            // Prepare team composition data
            $teamAComposition = $this->formatTeamComposition($teamAHeroes);
            $teamBComposition = $this->formatTeamComposition($teamBHeroes);

            // Create prompt for ChatGPT
            $prompt = $this->buildMatchupPrompt(
                $teamAComposition, 
                $teamBComposition, 
                $basicAnalysis
            );

            // Call OpenAI API
            $response = $this->callChatGPT($prompt);

            if ($response) {
                return $this->parseAIResponse($response);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format team composition for AI analysis
     */
    protected function formatTeamComposition(array $heroes): string
    {
        $composition = [];
        foreach ($heroes as $hero) {
            $composition[] = sprintf(
                "- %s (%s): Durability %d/10, Offense %d/10, Control %d/10",
                $hero['name'],
                $hero['role'],
                $hero['durability'],
                $hero['offense'],
                $hero['control']
            );
        }
        return implode("\n", $composition);
    }

    /**
     * Build prompt for ChatGPT
     */
    protected function buildMatchupPrompt(
        string $teamAComposition, 
        string $teamBComposition, 
        array $basicAnalysis
    ): string {
        $teamAWinRate = $basicAnalysis['team_a']['win_probability'];
        $teamBWinRate = $basicAnalysis['team_b']['win_probability'];

        return <<<PROMPT
You are a professional Mobile Legends: Bang Bang (MLBB) analyst. Analyze this matchup between two teams.

**Team A Composition:**
{$teamAComposition}

**Team B Composition:**
{$teamBComposition}

**Basic Analysis:**
- Team A Win Probability: {$teamAWinRate}%
- Team B Win Probability: {$teamBWinRate}%

Provide a concise strategic analysis covering:
1. **Key Matchup Insights**: 2-3 critical observations about this matchup
2. **Team A Strategy**: Specific tactical advice for Team A to win
3. **Team B Strategy**: Specific tactical advice for Team B to win
4. **Game Phase Advantage**: Which team is stronger in early/mid/late game and why

Keep the response focused and actionable. Format your response as JSON with these keys:
{
    "key_insights": ["insight1", "insight2", "insight3"],
    "team_a_strategy": "strategy text",
    "team_b_strategy": "strategy text",
    "phase_advantage": "phase advantage analysis"
}
PROMPT;
    }

    /**
     * Call AI API (OpenAI or GitHub Models)
     */
    protected function callChatGPT(string $prompt): ?string
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
            ];

            // Set authentication header based on provider
            if ($this->provider === 'github') {
                $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            } else {
                $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            }

            $response = Http::withHeaders($headers)
            ->timeout(30)
            ->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional MLBB esports analyst providing concise, actionable strategic advice.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error("AI API Error ({$this->provider}): " . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error("AI API Request Failed ({$this->provider}): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse AI response
     */
    protected function parseAIResponse(string $response): ?array
    {
        try {
            // Try to extract JSON from response
            // Sometimes ChatGPT wraps JSON in markdown code blocks
            $response = preg_replace('/```json\s*|\s*```/', '', $response);
            $response = trim($response);

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Parse Error: ' . json_last_error_msg());
                // Fallback: return raw response
                return [
                    'key_insights' => [$response],
                    'team_a_strategy' => $response,
                    'team_b_strategy' => $response,
                    'phase_advantage' => $response,
                ];
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('Failed to parse AI response: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate hero counter suggestions using AI
     * 
     * @param array $enemyHeroes Enemy team composition
     * @param array $availableHeroes Available heroes to pick from
     * @return array|null Counter suggestions
     */
    public function suggestCounterPicks(array $enemyHeroes, array $availableHeroes): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $enemyComp = $this->formatTeamComposition($enemyHeroes);
            
            $heroNames = array_map(fn($h) => $h['name'], $availableHeroes);
            $heroList = implode(', ', array_slice($heroNames, 0, 20)); // Limit to avoid token overflow

            $prompt = <<<PROMPT
Analyze the enemy team composition and suggest the top 3 counter picks from the available heroes.

**Enemy Team:**
{$enemyComp}

**Available Heroes (sample):** {$heroList}

Provide JSON response:
{
    "counters": [
        {"hero": "hero_name", "reason": "why this hero counters"},
        {"hero": "hero_name", "reason": "why this hero counters"},
        {"hero": "hero_name", "reason": "why this hero counters"}
    ]
}
PROMPT;

            $response = $this->callChatGPT($prompt);
            if ($response) {
                return $this->parseAIResponse($response);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Counter pick suggestion failed: ' . $e->getMessage());
            return null;
        }
    }
}
