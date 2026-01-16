@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'Lineup & Hero Statistics')

@section('content')
<style>
    .statistics-container {
        padding: 2rem 0;
        min-height: 100vh;
    }

    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #94a3b8;
        font-size: 1.1rem;
    }

    .stats-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid rgba(0, 217, 255, 0.2);
        overflow-x: auto;
    }

    .stats-tab {
        padding: 1rem 2rem;
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
    }

    .stats-tab:hover {
        color: #00d9ff;
    }

    .stats-tab.active {
        color: #00d9ff;
        border-bottom-color: #00d9ff;
    }

    .stats-content {
        display: none;
    }

    .stats-content.active {
        display: block;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        border-color: #00d9ff;
        box-shadow: 0 0 20px rgba(0, 217, 255, 0.2);
        transform: translateY(-2px);
    }

    .stats-card h3 {
        font-size: 1.3rem;
        color: #00d9ff;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .lineup-item {
        background: rgba(30, 41, 59, 0.6);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .lineup-item:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.05);
    }

    .lineup-heroes {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .lineup-hero-badge {
        background: rgba(0, 217, 255, 0.1);
        border: 1px solid rgba(0, 217, 255, 0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #e2e8f0;
    }

    .lineup-stats {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .lineup-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .lineup-stat-label {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }

    .lineup-stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #00d9ff;
    }

    .lineup-stat-value.positive {
        color: #22c55e;
    }

    .lineup-stat-value.negative {
        color: #ef4444;
    }

    .hero-stats-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .hero-stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(30, 41, 59, 0.6);
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .hero-stat-item:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.05);
    }

    .hero-stat-rank {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffd700;
        min-width: 40px;
        text-align: center;
    }

    .hero-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: rgba(0, 217, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .hero-stat-info {
        flex: 1;
    }

    .hero-stat-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 0.25rem;
    }

    .hero-stat-role {
        font-size: 0.9rem;
        color: #94a3b8;
    }

    .hero-stat-metrics {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .hero-stat-metric {
        text-align: center;
    }

    .hero-stat-metric-label {
        font-size: 0.75rem;
        color: #94a3b8;
        text-transform: uppercase;
    }

    .hero-stat-metric-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #00d9ff;
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        font-size: 1.2rem;
        color: #94a3b8;
    }

    .loading-spinner::before {
        content: '⚡';
        display: inline-block;
        animation: pulse 1s infinite;
        margin-right: 0.5rem;
        font-size: 2rem;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .chart-container {
        background: rgba(30, 41, 59, 0.6);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        position: relative;
        min-height: 300px;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(0, 217, 255, 0.1);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        color: #00d9ff;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .back-button:hover {
        background: rgba(0, 217, 255, 0.2);
        border-color: #00d9ff;
        transform: translateX(-3px);
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .hero-stat-metrics {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>

<div class="statistics-container">
    <div class="container">
        <div class="page-header">
            <h2>📊 Lineup & Hero Statistics</h2>
            <p>Comprehensive analytics from matchup analyses</p>
        </div>

        <a href="{{ route('mlbb.matchup.index') }}" class="back-button">
            ← Back to Matchup Analysis
        </a>

        <div class="stats-tabs">
            <button class="stats-tab active" onclick="switchTab('top-lineups')">
                🏆 Top Lineups
            </button>
            <button class="stats-tab" onclick="switchTab('trending')">
                🔥 Trending
            </button>
            <button class="stats-tab" onclick="switchTab('heroes')">
                ⚔️ Hero Stats
            </button>
            <button class="stats-tab" onclick="switchTab('winrate')">
                📈 Win Rates
            </button>
            <button class="stats-tab" onclick="switchTab('bans')">
                🚫 Most Banned
            </button>
        </div>

        <!-- Top Lineups Tab -->
        <div id="top-lineups" class="stats-content active">
            <div class="stats-grid">
                <div class="stats-card">
                    <h3>🏆 Top 10 Performing Lineups</h3>
                    <div id="topLineupsContainer" class="loading-spinner">
                        Loading top lineups...
                    </div>
                </div>
            </div>
        </div>

        <!-- Trending Tab -->
        <div id="trending" class="stats-content">
            <div class="stats-grid">
                <div class="stats-card">
                    <h3>🔥 Trending Lineups (Last 7 Days)</h3>
                    <div id="trendingLineupsContainer" class="loading-spinner">
                        Loading trending lineups...
                    </div>
                </div>
            </div>
        </div>

        <!-- Heroes Tab -->
        <div id="heroes" class="stats-content">
            <div class="stats-grid">
                <div class="stats-card">
                    <h3>⚔️ Most Picked Heroes</h3>
                    <div id="topPickedContainer" class="loading-spinner">
                        Loading hero statistics...
                    </div>
                </div>
            </div>
        </div>

        <!-- Win Rate Tab -->
        <div id="winrate" class="stats-content">
            <div class="stats-grid">
                <div class="stats-card">
                    <h3>📈 Highest Win Rate Heroes</h3>
                    <div id="topWinrateContainer" class="loading-spinner">
                        Loading win rates...
                    </div>
                </div>
            </div>
        </div>

        <!-- Bans Tab -->
        <div id="bans" class="stats-content">
            <div class="stats-grid">
                <div class="stats-card">
                    <h3>🚫 Most Banned Heroes</h3>
                    <div id="topBannedContainer" class="loading-spinner">
                        Loading ban statistics...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    let statsData = null;

    // Switch tabs
    function switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.stats-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');

        // Update content
        document.querySelectorAll('.stats-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(tabName).classList.add('active');
    }

    // Load statistics on page load
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const response = await fetch('/mlbb/matchup/statistics/dashboard');
            const result = await response.json();

            if (result.success) {
                statsData = result.data;
                renderTopLineups(statsData.top_lineups);
                renderTrendingLineups(statsData.trending_lineups);
                renderTopPicked(statsData.top_picked_heroes);
                renderTopWinrate(statsData.highest_winrate_heroes);
                renderMostBanned(statsData.most_banned_heroes);
            } else {
                showError('Failed to load statistics');
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
            showError('An error occurred while loading statistics');
        }
    });

    function renderTopLineups(lineups) {
        const container = document.getElementById('topLineupsContainer');

        if (!lineups || lineups.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📊</div>
                    <p>No lineup statistics available yet.<br>Start analyzing matchups to generate data!</p>
                </div>
            `;
            return;
        }

        container.innerHTML = lineups.map((lineup, index) => `
            <div class="lineup-item" onclick="viewLineupDetails('${lineup.lineup_hash}')">
                <div class="lineup-heroes">
                    ${lineup.lineup_heroes.map(hero => `
                        <span class="lineup-hero-badge">${formatHeroName(hero)}</span>
                    `).join('')}
                </div>
                <div class="lineup-stats">
                    <div class="lineup-stat">
                        <div class="lineup-stat-label">Rank</div>
                        <div class="lineup-stat-value" style="color: ${getRankColor(index)}">#${index + 1}</div>
                    </div>
                    <div class="lineup-stat">
                        <div class="lineup-stat-label">Win Rate</div>
                        <div class="lineup-stat-value ${lineup.win_rate >= 60 ? 'positive' : lineup.win_rate <= 40 ? 'negative' : ''}">${lineup.win_rate.toFixed(1)}%</div>
                    </div>
                    <div class="lineup-stat">
                        <div class="lineup-stat-label">Analyses</div>
                        <div class="lineup-stat-value">${lineup.times_analyzed}</div>
                    </div>
                    <div class="lineup-stat">
                        <div class="lineup-stat-label">Power</div>
                        <div class="lineup-stat-value">${lineup.avg_matchup_score.toFixed(1)}</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderTrendingLineups(lineups) {
        const container = document.getElementById('trendingLineupsContainer');

        if (!lineups || lineups.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">🔥</div>
                    <p>No trending lineups this week.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = lineups.map((lineup, index) => `
            <div class="lineup-item" onclick="viewLineupDetails('${lineup.lineup_hash}')">
                <div class="lineup-heroes">
                    ${lineup.lineup_heroes.map(hero => `
                        <span class="lineup-hero-badge">${formatHeroName(hero)}</span>
                    `).join('')}
                </div>
                <div class="lineup-stats">
                    <div class="lineup-stat">
                        <div class="lineup-stat-label">Win Rate</div>
                        <div class="lineup-stat-value ${lineup.win_rate >= 60 ? 'positive' : lineup.win_rate <= 40 ? 'negative' : ''}">${lineup.win_rate.toFixed(1)}%</div>
                    </div>
                    <div class="lineup-stat">
                        <div class="lineup-stat-label">Recent</div>
                        <div class="lineup-stat-value">${lineup.times_analyzed}</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderTopPicked(heroes) {
        const container = document.getElementById('topPickedContainer');

        if (!heroes || heroes.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">⚔️</div>
                    <p>No hero pick statistics available yet.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="hero-stats-list">
                ${heroes.map((hero, index) => `
                    <div class="hero-stat-item">
                        <div class="hero-stat-rank">${index + 1}</div>
                        <div class="hero-stat-icon">${getRoleIcon(hero.hero_role)}</div>
                        <div class="hero-stat-info">
                            <div class="hero-stat-name">${hero.hero_name}</div>
                            <div class="hero-stat-role">${hero.hero_role}</div>
                        </div>
                        <div class="hero-stat-metrics">
                            <div class="hero-stat-metric">
                                <div class="hero-stat-metric-label">Picks</div>
                                <div class="hero-stat-metric-value">${hero.times_picked}</div>
                            </div>
                            <div class="hero-stat-metric">
                                <div class="hero-stat-metric-label">Win %</div>
                                <div class="hero-stat-metric-value">${hero.win_rate.toFixed(1)}%</div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function renderTopWinrate(heroes) {
        const container = document.getElementById('topWinrateContainer');

        if (!heroes || heroes.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📈</div>
                    <p>Not enough data for win rate statistics yet.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="hero-stats-list">
                ${heroes.map((hero, index) => `
                    <div class="hero-stat-item">
                        <div class="hero-stat-rank">${index + 1}</div>
                        <div class="hero-stat-icon">${getRoleIcon(hero.hero_role)}</div>
                        <div class="hero-stat-info">
                            <div class="hero-stat-name">${hero.hero_name}</div>
                            <div class="hero-stat-role">${hero.hero_role}</div>
                        </div>
                        <div class="hero-stat-metrics">
                            <div class="hero-stat-metric">
                                <div class="hero-stat-metric-label">Win %</div>
                                <div class="hero-stat-metric-value" style="color: #22c55e">${hero.win_rate.toFixed(1)}%</div>
                            </div>
                            <div class="hero-stat-metric">
                                <div class="hero-stat-metric-label">Games</div>
                                <div class="hero-stat-metric-value">${hero.times_picked}</div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function renderMostBanned(heroes) {
        const container = document.getElementById('topBannedContainer');

        if (!heroes || heroes.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">🚫</div>
                    <p>No ban statistics available yet.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="hero-stats-list">
                ${heroes.map((hero, index) => `
                    <div class="hero-stat-item">
                        <div class="hero-stat-rank">${index + 1}</div>
                        <div class="hero-stat-icon">${getRoleIcon(hero.hero_role)}</div>
                        <div class="hero-stat-info">
                            <div class="hero-stat-name">${hero.hero_name}</div>
                            <div class="hero-stat-role">${hero.hero_role}</div>
                        </div>
                        <div class="hero-stat-metrics">
                            <div class="hero-stat-metric">
                                <div class="hero-stat-metric-label">Bans</div>
                                <div class="hero-stat-metric-value" style="color: #ef4444">${hero.times_banned}</div>
                            </div>
                            <div class="hero-stat-metric">
                                <div class="hero-stat-metric-label">Picks</div>
                                <div class="hero-stat-metric-value">${hero.times_picked}</div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function formatHeroName(slug) {
        return slug.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    function getRoleIcon(role) {
        const icons = {
            'Tank': '🛡️',
            'Fighter': '⚔️',
            'Assassin': '🗡️',
            'Mage': '🔮',
            'Marksman': '🏹',
            'Support': '💚'
        };
        return icons[role] || '⚡';
    }

    function getRankColor(index) {
        if (index === 0) return '#ffd700';
        if (index === 1) return '#c0c0c0';
        if (index === 2) return '#cd7f32';
        return '#00d9ff';
    }

    function viewLineupDetails(hash) {
        // Navigate to lineup details page or show modal
        console.log('View details for lineup:', hash);
        // You could implement a modal or navigate to a details page
        alert('Lineup details feature coming soon!');
    }

    function showError(message) {
        document.querySelectorAll('.loading-spinner').forEach(el => {
            el.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">⚠️</div>
                    <p>${message}</p>
                </div>
            `;
        });
    }
</script>
@endsection
