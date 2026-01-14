@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'Features - MLBB Tournament Management')

@push('styles')
<style>
    .features-hero {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 80px 0;
        text-align: center;
        color: white;
    }
    
    .features-hero h1 {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }
    
    .features-hero p {
        font-size: 1.3rem;
        opacity: 0.95;
    }
    
    .features-list {
        padding: 80px 0;
    }
    
    .feature-item {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-bottom: 6rem;
    }
    
    .feature-item:nth-child(even) {
        direction: rtl;
    }
    
    .feature-item:nth-child(even) > * {
        direction: ltr;
    }
    
    .feature-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: #2d3748;
    }
    
    .feature-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 1.5rem;
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
    }
    
    .feature-list li {
        padding: 0.75rem 0;
        padding-left: 2rem;
        position: relative;
        color: #4a5568;
        font-size: 1.1rem;
    }
    
    .feature-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #667eea;
        font-weight: bold;
        font-size: 1.3rem;
    }
    
    .feature-visual {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 3rem;
        color: white;
        text-align: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }
    
    .cta-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 4rem;
        border-radius: 20px;
        text-align: center;
        color: white;
        margin-top: 4rem;
    }
    
    .cta-box h2 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }
    
    .cta-box p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }
    
    .cta-box .btn {
        padding: 1rem 2.5rem;
        background: white;
        color: #667eea;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .cta-box .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 768px) {
        .features-hero h1 {
            font-size: 2rem;
        }
        
        .feature-item {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .feature-item:nth-child(even) {
            direction: ltr;
        }
        
        .feature-content h2 {
            font-size: 2rem;
        }
        
        .feature-visual {
            min-height: 200px;
            font-size: 3rem;
        }
        
        .cta-box {
            padding: 2rem;
        }
        
        .cta-box h2 {
            font-size: 1.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="features-hero">
    <div class="container">
        <h1>Powerful Features for Professional Tournaments</h1>
        <p>Everything you need to manage MLBB esports competitions</p>
    </div>
</div>

<div class="features-list">
    <div class="container">
        <div class="feature-item">
            <div class="feature-content">
                <h2>Complete Hero Database</h2>
                <p>Access comprehensive information on all 131 Mobile Legends: Bang Bang heroes with real-time updates as the meta evolves.</p>
                <ul class="feature-list">
                    <li>Detailed hero statistics (durability, offense, control, difficulty)</li>
                    <li>Role-based filtering (Tank, Fighter, Assassin, Mage, Marksman, Support)</li>
                    <li>Counter and synergy relationships</li>
                    <li>Game phase effectiveness ratings</li>
                    <li>Specialty tags and playstyle indicators</li>
                </ul>
            </div>
            <div class="feature-visual">
                🎮
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-content">
                <h2>Advanced Matchup Analysis</h2>
                <p>Powerful AI-driven analysis tools that predict team composition outcomes and provide strategic recommendations.</p>
                <ul class="feature-list">
                    <li>Real-time team composition analysis</li>
                    <li>Win probability calculations</li>
                    <li>Counter pick recommendations</li>
                    <li>Role balance optimization</li>
                    <li>Meta tier list integration</li>
                </ul>
            </div>
            <div class="feature-visual">
                ⚔️
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-content">
                <h2>Professional Tournament Overlays</h2>
                <p>Broadcast-ready overlays compatible with OBS Studio for professional tournament streaming.</p>
                <ul class="feature-list">
                    <li>Real-time pick/ban phase display</li>
                    <li>Team name and logo customization</li>
                    <li>Instant synchronization across devices</li>
                    <li>Multiple layout options</li>
                    <li>Zero latency updates</li>
                </ul>
            </div>
            <div class="feature-visual">
                📺
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-content">
                <h2>Draft Strategy Tools</h2>
                <p>Analyze and optimize your draft phase with data-driven insights and strategic recommendations.</p>
                <ul class="feature-list">
                    <li>Ban phase priority suggestions</li>
                    <li>Team synergy calculator</li>
                    <li>Draft pattern analysis</li>
                    <li>Historical match data</li>
                    <li>Pro player pick preferences</li>
                </ul>
            </div>
            <div class="feature-visual">
                📊
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-content">
                <h2>Mobile-First Responsive Design</h2>
                <p>Access all features seamlessly across desktop, tablet, and mobile devices.</p>
                <ul class="feature-list">
                    <li>Optimized for all screen sizes</li>
                    <li>Touch-friendly interface</li>
                    <li>Fast loading performance</li>
                    <li>Offline capability (coming soon)</li>
                    <li>Progressive web app features</li>
                </ul>
            </div>
            <div class="feature-visual">
                📱
            </div>
        </div>
        
        <div class="feature-item">
            <div class="feature-content">
                <h2>Real-Time Synchronization</h2>
                <p>All devices stay in sync instantly for seamless tournament management across multiple operators.</p>
                <ul class="feature-list">
                    <li>Multi-user collaboration</li>
                    <li>Instant overlay updates</li>
                    <li>Cloud-based storage</li>
                    <li>Automatic backup</li>
                    <li>Conflict resolution</li>
                </ul>
            </div>
            <div class="feature-visual">
                🔄
            </div>
        </div>
        
        <div class="cta-box">
            <h2>Ready to Get Started?</h2>
            <p>Experience all these features and more with MLBB Tournament Manager</p>
            <a href="/mlbb/matchup" class="btn">Launch Matchup Tool</a>
        </div>
    </div>
</div>
@endsection
