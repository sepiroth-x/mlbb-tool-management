@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'Dashboard - MLBB Tournament Manager')

@section('content')
<style>
    .dashboard-container {
        padding: 2rem 0;
        min-height: 100vh;
    }

    .dashboard-header {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.1), rgba(123, 47, 247, 0.1));
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .dashboard-subtitle {
        color: #94a3b8;
        font-size: 1.1rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }

    .dashboard-card:hover {
        border-color: #00d9ff;
        box-shadow: 0 0 20px rgba(0, 217, 255, 0.2);
        transform: translateY(-5px);
    }

    .card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 0.5rem;
    }

    .card-description {
        color: #94a3b8;
        font-size: 1rem;
        line-height: 1.6;
    }

    .quick-actions {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .quick-actions-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00d9ff;
        margin-bottom: 1.5rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        padding: 1rem 1.5rem;
        background: rgba(0, 217, 255, 0.1);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        color: #00d9ff;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
    }

    .action-btn:hover {
        background: rgba(0, 217, 255, 0.2);
        border-color: #00d9ff;
        transform: translateY(-2px);
    }

    .user-info {
        background: rgba(30, 41, 59, 0.6);
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-welcome {
        font-size: 1.2rem;
        color: #e2e8f0;
    }

    .user-email {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .logout-btn {
        padding: 0.625rem 1.5rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        color: #ef4444;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }

        .user-info {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>

<div class="dashboard-container">
    <div class="container">
        <div class="user-info">
            <div>
                <div class="user-welcome">👋 Welcome, <strong>{{ auth()->user()->name }}</strong>!</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('mlbb.auth.logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="dashboard-header">
            <h1 class="dashboard-title">MLBB Tournament Manager</h1>
            <p class="dashboard-subtitle">Your hub for Mobile Legends: Bang Bang competitive analysis and tournament management</p>
        </div>

        <div class="dashboard-grid">
            <a href="{{ route('mlbb.matchup.index') }}" class="dashboard-card">
                <div class="card-icon">⚔️</div>
                <div class="card-title">Team Matchup Analysis</div>
                <div class="card-description">
                    Analyze team compositions, counter picks, and winning probabilities with AI-powered insights.
                </div>
            </a>

            <a href="{{ route('mlbb.matchup.statistics') }}" class="dashboard-card">
                <div class="card-icon">📊</div>
                <div class="card-title">Statistics & Insights</div>
                <div class="card-description">
                    View top performing lineups, trending heroes, win rates, and comprehensive analytics.
                </div>
            </a>

            <a href="{{ route('mlbb.overlay.admin') }}" class="dashboard-card">
                <div class="card-icon">🎮</div>
                <div class="card-title">Overlay Control</div>
                <div class="card-description">
                    Manage live tournament overlays for streaming with real-time pick/ban phase control.
                </div>
            </a>
        </div>

        <div class="quick-actions">
            <h2 class="quick-actions-title">⚡ Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('mlbb.matchup.index') }}" class="action-btn">
                    <span>🎯</span>
                    <span>Start New Analysis</span>
                </a>
                <a href="{{ route('mlbb.matchup.statistics') }}" class="action-btn">
                    <span>🔥</span>
                    <span>View Trending Lineups</span>
                </a>
                <a href="{{ route('mlbb.overlay.admin') }}" class="action-btn">
                    <span>📺</span>
                    <span>Setup Overlay</span>
                </a>
                <a href="{{ route('mlbb.matchup.index') }}" class="action-btn">
                    <span>🤖</span>
                    <span>Ask AI Coach</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
