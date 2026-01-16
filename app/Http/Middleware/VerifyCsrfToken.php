<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // MLBB Matchup Analysis - Safe to exclude (no sensitive data, read-only calculation)
        'mlbb/matchup/analyze',
        // MLBB Matchup Chat - Safe to exclude (read-only Q&A about analysis results)
        'mlbb/matchup/chat',
        // MLBB Overlay Admin - Exclude for real-time tournament overlay control
        'mlbb/overlay/match/create',
        'mlbb/overlay/match/*/select',
        'mlbb/overlay/match/*/pick',
        'mlbb/overlay/match/*/ban',
        'mlbb/overlay/match/*/undo',
        'mlbb/overlay/match/*/reset',
        'mlbb/overlay/match/*/phase',
    ];
}
