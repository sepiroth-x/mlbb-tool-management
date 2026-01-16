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
    protected ?array $heroSkills = null;

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
        
        // Load hero skills data
        $this->loadHeroSkills();
    }

    /**
     * Check if AI API is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
    
    /**
     * Load hero skills data from JSON file
     */
    protected function loadHeroSkills(): void
    {
        try {
            $skillsPath = base_path('Modules/MLBBToolManagement/Data/hero-skills.json');
            if (file_exists($skillsPath)) {
                $content = file_get_contents($skillsPath);
                $data = json_decode($content, true);
                $this->heroSkills = $data['skills'] ?? [];
                Log::info('Hero skills data loaded', ['hero_count' => count($this->heroSkills)]);
            } else {
                Log::warning('Hero skills file not found', ['path' => $skillsPath]);
                $this->heroSkills = [];
            }
        } catch (\Exception $e) {
            Log::error('Error loading hero skills: ' . $e->getMessage());
            $this->heroSkills = [];
        }
    }
    
    /**
     * Get hero skills data by slug
     */
    protected function getHeroSkills(string $heroSlug): ?array
    {
        $normalizedSlug = strtolower(str_replace([' ', '-', '_'], '', $heroSlug));
        
        // Try direct match first
        if (isset($this->heroSkills[$heroSlug])) {
            return $this->heroSkills[$heroSlug];
        }
        
        // Try normalized match
        if (isset($this->heroSkills[$normalizedSlug])) {
            return $this->heroSkills[$normalizedSlug];
        }
        
        // Try case-insensitive search
        foreach ($this->heroSkills as $slug => $skills) {
            if (strcasecmp($slug, $heroSlug) === 0 || strcasecmp($slug, $normalizedSlug) === 0) {
                return $skills;
            }
        }
        
        return null;
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
            $heroInfo = sprintf(
                "**%s** (%s): Durability %d/10, Offense %d/10, Control %d/10",
                $hero['name'],
                $hero['role'],
                $hero['durability'] ?? 5,
                $hero['offense'] ?? 5,
                $hero['control'] ?? 5
            );
            
            // Add skills information if available
            $skills = $this->getHeroSkills($hero['slug']);
            if ($skills) {
                $heroInfo .= "\n  Passive: " . ($skills['passive']['name'] ?? 'N/A');
                $heroInfo .= "\n  Skills: " . 
                    ($skills['skill1']['name'] ?? 'N/A') . ', ' . 
                    ($skills['skill2']['name'] ?? 'N/A') . ', ' . 
                    ($skills['ultimate']['name'] ?? 'N/A');
                
                // Add key skill descriptions (abbreviated)
                if (isset($skills['passive']['description'])) {
                    $passiveDesc = substr($skills['passive']['description'], 0, 100);
                    $heroInfo .= "\n  Passive Effect: " . $passiveDesc . (strlen($skills['passive']['description']) > 100 ? '...' : '');
                }
            } else {
                $heroInfo .= "\n  [Note: Detailed skill information not available for this hero]";
            }
            
            $composition[] = $heroInfo;
        }
        return implode("\n\n", $composition);
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
You are a professional Mobile Legends: Bang Bang (MLBB) analyst with deep knowledge of hero abilities, skill interactions, and team compositions. Analyze this matchup between two teams.

**Team A Composition:**
{$teamAComposition}

**Team B Composition:**
{$teamBComposition}

**Basic Win Probability Analysis:**
- Team A Win Probability: {$teamAWinRate}%
- Team B Win Probability: {$teamBWinRate}%

**IMPORTANT INSTRUCTIONS:**
- For heroes WITH detailed skill information: Use their ACTUAL abilities, passive effects, and skill synergies in your analysis
- For heroes WITHOUT skill details: Base your analysis on their role, stats, and general MLBB knowledge
- Focus on REAL skill interactions, crowd control chains, damage combos, and positioning requirements
- Consider actual cooldowns, energy costs, and skill mechanics when suggesting strategies

Provide a concise strategic analysis covering:
1. **Key Matchup Insights**: 2-3 critical observations based on actual hero abilities and team synergies
2. **Team A Strategy**: Specific tactical advice using their heroes' real skill sets
3. **Team B Strategy**: Specific tactical advice using their heroes' real skill sets
4. **Game Phase Advantage**: Which team is stronger in early/mid/late game based on hero power spikes and skill scaling
5. **Essential Item Builds**: For each team, recommend 3-4 CRUCIAL items that would be most effective in THIS SPECIFIC matchup, with brief explanations why

Keep the response focused and actionable. Format your response as JSON with these keys:
{
    "key_insights": ["insight1", "insight2", "insight3"],
    "team_a_strategy": "strategy text",
    "team_b_strategy": "strategy text",
    "phase_advantage": "phase advantage analysis",
    "team_a_builds": [
        {"item": "Item Name", "reason": "why this item is crucial for this matchup"},
        {"item": "Item Name", "reason": "why this item is crucial"}
    ],
    "team_b_builds": [
        {"item": "Item Name", "reason": "why this item is crucial for this matchup"},
        {"item": "Item Name", "reason": "why this item is crucial"}
    ]
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

    /**
     * Handle chat messages about matchup analysis
     * 
     * @param string $message User's question
     * @param array $context Matchup context (teams and analysis)
     * @param array $history Conversation history
     * @return string AI response
     */
    public function handleMatchupChat(string $message, array $context, array $history = []): string
    {
        try {
            // Build team composition summaries
            $teamAHeroes = collect($context['teamA'])->pluck('name')->implode(', ');
            $teamBHeroes = collect($context['teamB'])->pluck('name')->implode(', ');
            
            // Get hero skills for context if available
            $relevantSkills = $this->getRelevantHeroSkills($context);
            
            // Build system context
            $systemMessage = <<<SYSTEM
You are an expert Mobile Legends: Bang Bang analyst helping a player understand their team matchup.

MATCHUP CONTEXT:
Team A: {$teamAHeroes}
Team B: {$teamBHeroes}

{$relevantSkills}

The player has received a detailed analysis of this matchup and now has follow-up questions.

YOUR ROLE:
- Answer questions about heroes, strategies, items, and tactics
- Provide specific, actionable advice
- Reference the actual team composition in your answers
- Keep responses concise (2-4 short paragraphs max)
- Use MLBB terminology and concepts
- Be encouraging and helpful

GUIDELINES:
- If asked about items: recommend specific MLBB items with reasoning
- If asked about strategy: explain early/mid/late game approaches
- If asked about heroes: discuss their role in THIS specific matchup
- If asked "why": explain the strategic reasoning clearly
- Always relate answers back to the current matchup
SYSTEM;

            // Build messages array
            $messages = [
                ['role' => 'system', 'content' => $systemMessage]
            ];
            
            // Add conversation history (limit to last 10 messages to manage token usage)
            $recentHistory = array_slice($history, -10);
            foreach ($recentHistory as $msg) {
                $messages[] = $msg;
            }
            
            // Add current user message
            $messages[] = ['role' => 'user', 'content' => $message];
            
            // Call AI API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 400,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'I apologize, but I could not generate a response.';
            }

            Log::error('AI chat API error: ' . $response->body());
            return 'I\'m having trouble connecting to the AI service. Please try again.';

        } catch (\Exception $e) {
            Log::error('Matchup chat error: ' . $e->getMessage());
            return 'I encountered an error processing your question. Please try rephrasing it.';
        }
    }

    /**
     * Get relevant hero skills for the matchup context
     */
    protected function getRelevantHeroSkills(array $context): string
    {
        if (!$this->heroSkills) {
            return '';
        }

        $allHeroNames = array_merge(
            collect($context['teamA'])->pluck('slug')->toArray(),
            collect($context['teamB'])->pluck('slug')->toArray()
        );

        $relevantSkills = [];
        foreach ($allHeroNames as $heroSlug) {
            if (isset($this->heroSkills[$heroSlug])) {
                $hero = $this->heroSkills[$heroSlug];
                $relevantSkills[] = "{$hero['name']}: " . 
                    "Passive: {$hero['passive']['name']}, " .
                    "Ultimate: {$hero['ultimate']['name']}";
            }
        }

        if (empty($relevantSkills)) {
            return '';
        }

        return "KEY HERO ABILITIES:\n" . implode("\n", $relevantSkills);
    }
}
