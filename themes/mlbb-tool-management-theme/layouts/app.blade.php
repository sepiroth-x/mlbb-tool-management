<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MLBB Coach')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Base Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Rajdhani', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #e2e8f0;
            background: #0a0e1a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(20, 40, 100, 0.3) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(100, 20, 150, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(20, 100, 150, 0.2) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(100, 50, 20, 0.2) 0px, transparent 50%);
            background-attachment: fixed;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles - Dark Gaming Theme */
        .mlbb-header {
            background: rgba(10, 14, 26, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 217, 255, 0.1), 0 0 50px rgba(255, 215, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid rgba(0, 217, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 0;
            gap: 2rem;
        }
        
        .logo h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00d9ff 0%, #ffd700 50%, #00fff5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(0, 217, 255, 0.3);
            letter-spacing: 1px;
        }
        
        .main-nav {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        
        .main-nav ul {
            list-style: none;
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .main-nav a {
            text-decoration: none;
            color: #94a3b8;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 0.7rem 1.3rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid transparent;
        }
        
        .main-nav a:hover {
            color: #00d9ff;
            background: rgba(0, 217, 255, 0.1);
            border-color: rgba(0, 217, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.2);
        }
        
        .main-nav a.active {
            color: #ffd700;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), rgba(0, 217, 255, 0.15));
            border-color: rgba(255, 215, 0, 0.4);
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.2);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-menu span {
            font-weight: 600;
            color: #cbd5e1;
            font-size: 1.05rem;
        }
        
        .user-menu a {
            text-decoration: none;
            color: #0a0e1a;
            font-weight: 700;
            padding: 0.6rem 1.8rem;
            background: linear-gradient(135deg, #00d9ff, #00fff5);
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            font-size: 1.05rem;
        }
        
        .user-menu a:hover {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #0a0e1a;
            transform: translateY(-2px);
            box-shadow: 0 5px 30px rgba(255, 215, 0, 0.5);
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
        }
        
        .mobile-menu-toggle span {
            width: 28px;
            height: 3px;
            background: linear-gradient(90deg, #00d9ff, #ffd700);
            border-radius: 3px;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
        
        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Main Content */
        .mlbb-main {
            min-height: calc(100vh - 200px);
        }
        
        /* Footer Styles - Dark Gaming Theme */
        .mlbb-footer {
            background: linear-gradient(180deg, rgba(10, 14, 26, 0.9) 0%, rgba(5, 7, 13, 0.98) 100%);
            color: #cbd5e1;
            padding: 4rem 0 2rem;
            margin-top: 5rem;
            border-top: 2px solid rgba(0, 217, 255, 0.2);
            box-shadow: 0 -10px 50px rgba(0, 217, 255, 0.1);
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-section h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #00d9ff, #ffd700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .footer-section p,
        .footer-section a {
            color: #94a3b8;
            text-decoration: none;
            line-height: 2.2;
            transition: all 0.3s ease;
            font-size: 1.05rem;
        }
        
        .footer-section a:hover {
            color: #00d9ff;
            padding-left: 8px;
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2.5rem;
            border-top: 1px solid rgba(0, 217, 255, 0.1);
            color: #64748b;
            font-size: 1.05rem;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .social-links a {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.1), rgba(255, 215, 0, 0.1));
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: linear-gradient(135deg, #00d9ff, #00fff5);
            border-color: #00d9ff;
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.4);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }
            
            .main-nav {
                position: fixed;
                top: 80px;
                left: 0;
                right: 0;
                background: rgba(10, 14, 26, 0.98);
                backdrop-filter: blur(20px);
                box-shadow: 0 10px 50px rgba(0, 217, 255, 0.2);
                padding: 1.5rem;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                border-bottom: 2px solid rgba(0, 217, 255, 0.3);
            }
            
            .main-nav.active {
                transform: translateX(0);
            }
            
            .main-nav ul {
                flex-direction: column;
                width: 100%;
                gap: 0;
            }
            
            .main-nav li {
                width: 100%;
            }
            
            .main-nav a {
                display: block;
                width: 100%;
                padding: 1.2rem;
                font-size: 1.1rem;
            }
            
            .logo h1 {
                font-size: 1.3rem;
            }
            
            .user-menu {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="mlbb-theme">
    
    <!-- Header -->
    <header class="mlbb-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/" style="text-decoration: none;">
                        <h1>MLBB Coach</h1>
                    </a>
                </div>
                
                <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <nav class="main-nav" id="mainNav">
                    <ul>
                        <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                        <li><a href="/features" class="{{ request()->is('features') ? 'active' : '' }}">Features</a></li>
                        <li><a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>
                        <li><a href="/mlbb/matchup" class="{{ request()->is('mlbb/matchup*') ? 'active' : '' }}">Analyze</a></li>
                        <li><a href="/mlbb/overlay/admin" class="{{ request()->is('mlbb/overlay*') ? 'active' : '' }}">Overlay</a></li>
                        <li><a href="https://buymeacoffee.com/richardcupal" target="_blank" rel="noopener noreferrer">☕ Support</a></li>
                    </ul>
                </nav>
                
                <div class="user-menu">
                    @auth
                        <span>{{ auth()->user()->name }}</span>
                        <a href="/admin/logout" style="color: #667eea; font-weight: 600; text-decoration: none; margin-left: 10px;">Logout</a>
                    @else
                        <a href="/admin/login">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mlbb-main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mlbb-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>MLBB Coach</h3>
                    <p>Professional tournament management tools for Mobile Legends: Bang Bang esports.</p>
                    <div class="social-links">
                        <a href="https://github.com/sepiroth-x/mlbb-tool-management" target="_blank" title="GitHub">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                        <a href="https://facebook.com/sepirothx" target="_blank" title="Facebook">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/sepirothx000" target="_blank" title="Twitter">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="/">Home</a></li>
                        <li><a href="/mlbb/matchup">Analyze</a></li>
                        <li><a href="/mlbb/overlay/admin">Overlay</a></li>
                        <li><a href="https://buymeacoffee.com/richardcupal" target="_blank" rel="noopener noreferrer">☕ Support</a></li>
                        @auth
                            <li><a href="/admin">Admin</a></li>
                        @else
                            <li><a href="/admin/login">Login</a></li>
                        @endauth
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Features</h3>
                    <ul class="footer-links">
                        <li><a href="/mlbb/matchup">131 Hero Database</a></li>
                        <li><a href="/mlbb/matchup">Counter Analysis</a></li>
                        <li><a href="/mlbb/overlay/admin">Live Overlays</a></li>
                        <li><a href="/mlbb/matchup">Draft Strategy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p><strong>Sepiroth X Villainous</strong><br>
                    Richard Cebel Cupal, LPT</p>
                    <p>📧 chardy.tsadiq02@gmail.com<br>
                    📱 +63 915 0388 448</p>
                    <p style="margin-top: 1rem; font-size: 0.9rem;">
                        <strong>Need a website?</strong><br>
                        Available for web development projects.<br>
                        Contact me for custom solutions!
                    </p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} MLBB Coach by Sepiroth X. Built with VantaPress CMS. All rights reserved.</p>
                <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #94a3b8;">
                    This website is not affiliated with, endorsed, sponsored, or specifically approved by Moonton. 
                    Mobile Legends: Bang Bang and all related marks and logos are trademarks of Moonton.
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const nav = document.getElementById('mainNav');
            const toggle = document.querySelector('.mobile-menu-toggle');
            nav.classList.toggle('active');
            toggle.classList.toggle('active');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('mainNav');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (!nav.contains(event.target) && !toggle.contains(event.target)) {
                nav.classList.remove('active');
                toggle.classList.remove('active');
            }
        });
        
        // Close mobile menu on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('mainNav').classList.remove('active');
                document.querySelector('.mobile-menu-toggle').classList.remove('active');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
            <p>&copy; {{ date('Y') }} MLBB Coach. Powered by VantaPress.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('themes/mlbb-tool-management-theme/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
