@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'MLBB Tournament Management - Home')

@push('styles')
<style>
    /* Hero Section - Dark MLBB Theme */
    .hero-section {
        background: linear-gradient(135deg, #0a0e1a 0%, #1a1f3a 50%, #0f1729 100%);
        padding: 140px 0 100px;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(0, 217, 255, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 20%, rgba(0, 255, 245, 0.1) 0%, transparent 50%);
        animation: pulse 8s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
        color: white;
    }
    
    .hero-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 4.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        background: linear-gradient(135deg, #00d9ff 0%, #ffd700 50%, #00fff5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 80px rgba(0, 217, 255, 0.5);
        animation: glow 3s ease-in-out infinite;
    }
    
    @keyframes glow {
        0%, 100% { filter: drop-shadow(0 0 20px rgba(0, 217, 255, 0.8)); }
        50% { filter: drop-shadow(0 0 40px rgba(255, 215, 0, 0.8)); }
    }
    
    .hero-subtitle {
        font-size: 1.6rem;
        font-weight: 400;
        margin-bottom: 3rem;
        color: #cbd5e1;
        letter-spacing: 0.5px;
    }
    
    .cta-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 1.2rem 3rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        font-size: 1.15rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #00d9ff, #00fff5);
        color: #0a0e1a;
        box-shadow: 0 5px 25px rgba(0, 217, 255, 0.4);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 35px rgba(255, 215, 0, 0.6);
    }
    
    .btn-outline {
        background: transparent;
        color: #00d9ff;
        border: 2px solid #00d9ff;
        box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
    }
    
    .btn-outline:hover {
        background: rgba(0, 217, 255, 0.1);
        border-color: #00fff5;
        color: #00fff5;
        transform: translateY(-3px);
        box-shadow: 0 5px 30px rgba(0, 217, 255, 0.5);
    }
    
    /* Stats Section - Dark Theme */
    .stats-section {
        background: linear-gradient(135deg, rgba(0, 20, 40, 0.9), rgba(10, 14, 26, 0.95));
        padding: 80px 0;
        color: white;
        border-top: 2px solid rgba(0, 217, 255, 0.2);
        border-bottom: 2px solid rgba(0, 217, 255, 0.2);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 3rem;
        margin-top: 3rem;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem;
        background: rgba(0, 217, 255, 0.05);
        border: 1px solid rgba(0, 217, 255, 0.2);
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        background: rgba(0, 217, 255, 0.1);
        border-color: rgba(255, 215, 0, 0.4);
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0, 217, 255, 0.3);
    }
    
    .stat-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 4.5rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-label {
        font-size: 1.3rem;
        color: #cbd5e1;
        font-weight: 400;
        letter-spacing: 0.5px;
    }
    
    /* Features Section - Dark Theme */
    .features-section {
        padding: 100px 0;
        background: linear-gradient(180deg, #0a0e1a 0%, #12182e 100%);
    }
    
    .section-title {
        font-family: 'Orbitron', sans-serif;
        text-align: center;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.3rem;
        color: #94a3b8;
        margin-bottom: 5rem;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2.5rem;
        margin-top: 3rem;
    }
    
    .feature-card {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.05), rgba(255, 215, 0, 0.05));
        padding: 3rem;
        border-radius: 20px;
        border: 1px solid rgba(0, 217, 255, 0.2);
        box-shadow: 0 10px 40px rgba(0, 217, 255, 0.1);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        border-color: rgba(255, 215, 0, 0.5);
        box-shadow: 0 20px 60px rgba(0, 217, 255, 0.3);
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.1), rgba(255, 215, 0, 0.1));
    }
    
    .feature-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #00d9ff, #00fff5);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        box-shadow: 0 10px 30px rgba(0, 217, 255, 0.4);
    }
    
    .feature-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #e2e8f0;
    }
    
    .feature-description {
        font-size: 1.05rem;
        color: #94a3b8;
        line-height: 1.7;
    }
    
    /* Tools Section - Dark Theme */
    .tools-section {
        padding: 100px 0;
        background: linear-gradient(180deg, #12182e 0%, #0a0e1a 100%);
    }
    
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 3rem;
        margin-top: 4rem;
    }
    
    .tool-card {
        background: linear-gradient(135deg, rgba(0, 50, 100, 0.4), rgba(50, 0, 100, 0.3));
        padding: 3.5rem;
        border-radius: 25px;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(0, 217, 255, 0.2);
    }
    
    .tool-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(0, 217, 255, 0.1) 0%, transparent 70%);
        transform: rotate(45deg);
    }
    
    .tool-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 25px 70px rgba(0, 217, 255, 0.4);
        border-color: rgba(255, 215, 0, 0.5);
    }
    
    .tool-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        opacity: 0.2;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .tool-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 1.2rem;
        position: relative;
        z-index: 1;
        color: #e2e8f0;
    }
    
    .tool-description {
        font-size: 1.15rem;
        color: #cbd5e1;
        margin-bottom: 2.5rem;
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }
    
    .tool-features {
        list-style: none;
        padding: 0;
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 1;
    }
    
    .tool-features li {
        padding: 0.7rem 0;
        padding-left: 2rem;
        position: relative;
        color: #cbd5e1;
        font-size: 1.05rem;
    }
    
    .tool-features li::before {
        content: '⚡';
        position: absolute;
        left: 0;
        font-size: 1.3rem;
        color: #ffd700;
    }
    
    .tool-link {
        display: inline-block;
        padding: 1rem 2.5rem;
        background: linear-gradient(135deg, #00d9ff, #00fff5);
        color: #0a0e1a;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        box-shadow: 0 5px 20px rgba(0, 217, 255, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .tool-link:hover {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(255, 215, 0, 0.6);
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, rgba(0, 50, 100, 0.6), rgba(100, 0, 150, 0.4));
        padding: 100px 0;
        text-align: center;
        border-top: 2px solid rgba(0, 217, 255, 0.3);
    }
    
    .cta-section h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        margin-bottom: 2rem;
        color: #e2e8f0;
    }
    
    .cta-section p {
        font-size: 1.4rem;
        color: #cbd5e1;
        margin-bottom: 3rem;
    }
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .cta-section {
        background: #f8f9fa;
        padding: 100px 0;
        text-align: center;
    }
    
    .cta-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        color: #2d3748;
    }
    
    .cta-description {
        font-size: 1.3rem;
        color: #718096;
        margin-bottom: 2.5rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .tools-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 80px 0 60px;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
        }
        
        .tool-card {
            padding: 2rem;
        }
        
        .cta-title {
            font-size: 2rem;
        }
        
        .cta-description {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 480px) {
        .hero-title {
            font-size: 1.75rem;
        }
        
        .cta-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .features-grid,
        .tools-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">MLBB Tournament Management</h1>
            <p class="hero-subtitle">Professional Tools for Mobile Legends: Bang Bang Esports</p>
            <div class="cta-buttons">
                <a href="/mlbb/matchup" class="btn btn-primary">Launch Matchup Tool</a>
                <a href="/mlbb/overlay/admin" class="btn btn-outline">Tournament Overlay</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <h2 class="section-title" style="color: white;">Powerful Statistics</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">131</div>
                <div class="stat-label">MLBB Heroes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">6</div>
                <div class="stat-label">Hero Roles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">∞</div>
                <div class="stat-label">Team Compositions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Real-time Analysis</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Why Choose MLBB Tool Management?</h2>
        <p class="section-subtitle">Everything you need to manage professional MLBB tournaments</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎮</div>
                <h3 class="feature-title">Complete Hero Database</h3>
                <p class="feature-description">Access all 131 MLBB heroes with detailed stats, roles, counters, and synergies updated with every meta shift.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⚔️</div>
                <h3 class="feature-title">Matchup Analysis</h3>
                <p class="feature-description">Advanced AI-powered matchup calculator to predict team composition strengths and weaknesses.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📺</div>
                <h3 class="feature-title">Live Overlay System</h3>
                <p class="feature-description">Professional OBS-compatible overlays for streaming tournaments with real-time pick/ban tracking.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Draft Strategy Tools</h3>
                <p class="feature-description">Analyze draft patterns, track hero priorities, and develop winning team compositions.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔄</div>
                <h3 class="feature-title">Real-time Sync</h3>
                <p class="feature-description">Instant updates across all connected devices for seamless tournament management.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3 class="feature-title">Counter Recommendations</h3>
                <p class="feature-description">Smart counter picks based on enemy composition, role balance, and current meta trends.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tools Section -->
<section class="tools-section">
    <div class="container">
        <h2 class="section-title">Our Professional Tools</h2>
        <p class="section-subtitle">Built for tournament organizers, coaches, and esports teams</p>
        
        <div class="tools-grid">
            <div class="tool-card">
                <div class="tool-number">01</div>
                <h3 class="tool-title">Hero Matchup Tool</h3>
                <p class="tool-description">Analyze hero matchups, counters, and team synergies with our comprehensive database.</p>
                <ul class="tool-features">
                    <li>131 Complete Hero Profiles</li>
                    <li>Role-based Filtering</li>
                    <li>Counter Analysis Matrix</li>
                    <li>Synergy Calculator</li>
                    <li>Meta Tier Rankings</li>
                </ul>
                <a href="/mlbb/matchup" class="tool-link">Open Matchup Tool →</a>
            </div>
            
            <div class="tool-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="tool-number">02</div>
                <h3 class="tool-title">Tournament Overlay</h3>
                <p class="tool-description">Professional streaming overlays for live tournament broadcasts and match analysis.</p>
                <ul class="tool-features">
                    <li>OBS Studio Compatible</li>
                    <li>Real-time Pick/Ban Display</li>
                    <li>Customizable Layouts</li>
                    <li>Team Logo Support</li>
                    <li>Instant Updates</li>
                </ul>
                <a href="/mlbb/overlay/admin" class="tool-link">Open Overlay Admin →</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">Ready to Level Up Your Tournaments?</h2>
        <p class="cta-description">Join professional tournament organizers and esports teams using MLBB Tool Management</p>
        <div class="cta-buttons">
            @auth
                <a href="/admin" class="btn btn-primary">Go to Dashboard</a>
            @else
                <a href="/admin/login" class="btn btn-primary">Get Started Now</a>
            @endauth
            <a href="#features" class="btn btn-outline" style="color: #667eea; border-color: #667eea;">Learn More</a>
        </div>
    </div>
</section>
@endsection
