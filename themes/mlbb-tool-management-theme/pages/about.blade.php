@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'About Us - MLBB Tournament Management')

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0;
        text-align: center;
        color: white;
    }
    
    .about-hero h1 {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }
    
    .about-hero p {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .about-content {
        padding: 80px 0;
    }
    
    .content-section {
        margin-bottom: 4rem;
    }
    
    .content-section h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: #2d3748;
    }
    
    .content-section p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 1rem;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }
    
    .team-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        text-align: center;
    }
    
    .team-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
    }
    
    .team-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #2d3748;
    }
    
    .team-role {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .team-description {
        color: #4a5568;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2rem;
        }
        
        .about-hero p {
            font-size: 1.1rem;
        }
        
        .content-section h2 {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="about-hero">
    <div class="container">
        <h1>About MLBB Coach</h1>
        <p>Empowering esports organizers and teams with professional tournament management tools</p>
    </div>
</div>

<div class="about-content">
    <div class="container">
        <div class="content-section">
            <h2>Our Mission</h2>
            <p>MLBB Coach was created to provide professional-grade tournament management tools for the Mobile Legends: Bang Bang esports community. We believe that every tournament organizer, team, and coach deserves access to powerful analytics and management tools.</p>
            <p>Our platform combines comprehensive hero databases, real-time matchup analysis, and professional streaming overlays to help you organize and broadcast world-class MLBB tournaments.</p>
        </div>
        
        <div class="content-section">
            <h2>What We Offer</h2>
            <p><strong>Complete Hero Database:</strong> Access detailed information on all 131 MLBB heroes, including stats, counters, synergies, and role-specific data.</p>
            <p><strong>Matchup Analysis Tools:</strong> Our advanced algorithms analyze team compositions, predict matchup outcomes, and provide strategic recommendations.</p>
            <p><strong>Tournament Overlays:</strong> Professional OBS-compatible overlays for streaming tournaments with real-time pick/ban tracking and team information displays.</p>
            <p><strong>Draft Strategy Tools:</strong> Analyze draft patterns, track hero priorities, and develop winning team compositions based on current meta trends.</p>
        </div>
        
        <div class="content-section">
            <h2>Our Team</h2>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">👨‍💻</div>
                    <h3 class="team-name">Sepiroth X Villainous</h3>
                    <p class="team-role">Lead Developer & Creator</p>
                    <p class="team-description">Richard Cebel Cupal, LPT - Full-stack developer passionate about esports technology and tournament management systems.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-avatar">🎮</div>
                    <h3 class="team-name">MLBB Community</h3>
                    <p class="team-role">Beta Testers</p>
                    <p class="team-description">Tournament organizers and esports teams who helped shape this platform through valuable feedback.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-avatar">⚡</div>
                    <h3 class="team-name">VantaPress CMS</h3>
                    <p class="team-role">Technology Partner</p>
                    <p class="team-description">Built on VantaPress CMS framework, providing robust Laravel foundation for enterprise-grade applications.</p>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <h2>Get In Touch</h2>
            <p>We're always looking to improve and would love to hear from you. Whether you have questions, suggestions, or just want to say hello, feel free to reach out.</p>
            <p><strong>Email:</strong> chardy.tsadiq02@gmail.com<br>
            <strong>Phone:</strong> +63 915 0388 448<br>
            <strong>GitHub:</strong> <a href="https://github.com/sepiroth-x/mlbb-tool-management" target="_blank" style="color: #667eea;">github.com/sepiroth-x/mlbb-tool-management</a></p>
        </div>
    </div>
</div>
@endsection
