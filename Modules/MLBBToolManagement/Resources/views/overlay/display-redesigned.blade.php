<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1920, height=1080">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tournament Overlay - {{ $match->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 1920px;
            height: 1080px;
            background: transparent;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* Match Header */
        .match-header {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }

        .match-title {
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.95), rgba(123, 47, 247, 0.95));
            padding: 12px 40px;
            border-radius: 30px;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 8px 32px rgba(0, 217, 255, 0.4);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* Phase Indicator */
        .phase-indicator {
            position: absolute;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 99;
        }

        .phase-badge {
            background: rgba(15, 23, 42, 0.9);
            padding: 8px 28px;
            border-radius: 20px;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: 2px solid rgba(0, 217, 255, 0.5);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: pulseBadge 2s infinite;
        }

        .phase-badge.ban {
            border-color: rgba(239, 68, 68, 0.8);
            color: #ef4444;
            background: rgba(15, 23, 42, 0.95);
        }

        .phase-badge.pick {
            border-color: rgba(34, 197, 94, 0.8);
            color: #22c55e;
            background: rgba(15, 23, 42, 0.95);
        }

        .phase-badge.locked {
            border-color: rgba(251, 188, 4, 0.8);
            color: #fbbc04;
            background: rgba(15, 23, 42, 0.95);
        }

        @keyframes pulseBadge {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        /* Team Containers */
        .team-container {
            position: absolute;
            top: 140px;
            width: 800px;
            height: 880px;
        }

        .team-container.team-a {
            left: 40px;
        }

        .team-container.team-b {
            right: 40px;
        }

        .team-header {
            position: relative;
            padding: 20px 30px;
            margin-bottom: 25px;
            border-radius: 15px;
            overflow: hidden;
        }

        .team-container.team-a .team-header {
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.9), rgba(0, 217, 255, 0.6));
            box-shadow: 0 8px 32px rgba(0, 217, 255, 0.3);
        }

        .team-container.team-b .team-header {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.9), rgba(255, 165, 0, 0.6));
            box-shadow: 0 8px 32px rgba(255, 215, 0, 0.3);
        }

        .team-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="15" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="20" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
            z-index: 0;
        }

        .team-name {
            position: relative;
            z-index: 1;
            font-size: 36px;
            font-weight: 900;
            text-align: center;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }

        /* Picks Section */
        .picks-section {
            margin-bottom: 30px;
        }

        .section-label {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            padding: 10px 20px;
            background: rgba(15, 23, 42, 0.9);
            border-radius: 10px;
            border: 2px solid rgba(0, 217, 255, 0.4);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            display: inline-block;
        }

        .team-container.team-b .section-label {
            border-color: rgba(255, 215, 0, 0.4);
        }

        .picks-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .hero-slot {
            position: relative;
            aspect-ratio: 1;
            background: rgba(15, 23, 42, 0.8);
            border: 3px solid rgba(100, 116, 139, 0.4);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .hero-slot.filled {
            border-color: rgba(0, 217, 255, 0.8);
            animation: heroAppear 0.5s ease;
        }

        .team-container.team-b .hero-slot.filled {
            border-color: rgba(255, 215, 0, 0.8);
        }

        @keyframes heroAppear {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .hero-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-slot::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
            z-index: 1;
        }

        .hero-name {
            position: absolute;
            bottom: 8px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            z-index: 2;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-slot.empty {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: rgba(100, 116, 139, 0.3);
        }

        /* Bans Section */
        .bans-section {
            margin-top: 30px;
        }

        .bans-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .ban-slot {
            position: relative;
            aspect-ratio: 1;
            background: rgba(15, 23, 42, 0.8);
            border: 3px solid rgba(239, 68, 68, 0.3);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .ban-slot.filled {
            border-color: rgba(239, 68, 68, 0.8);
            animation: banAppear 0.5s ease;
        }

        @keyframes banAppear {
            0% {
                transform: scale(0.8) rotate(-5deg);
                opacity: 0;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .ban-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%) brightness(0.4);
        }

        .ban-slot::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(239, 68, 68, 0.3);
            z-index: 1;
        }

        .ban-slot::after {
            content: '🚫';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 64px;
            z-index: 2;
            filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.8));
        }

        .ban-slot .hero-name {
            color: #ef4444;
            text-decoration: line-through;
        }

        .ban-slot.empty {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: rgba(239, 68, 68, 0.2);
            border-style: dashed;
        }

        /* Center Divider */
        .center-divider {
            position: absolute;
            top: 140px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 880px;
            background: linear-gradient(180deg, 
                transparent 0%, 
                rgba(0, 217, 255, 0.3) 20%, 
                rgba(0, 217, 255, 0.5) 50%, 
                rgba(0, 217, 255, 0.3) 80%, 
                transparent 100%
            );
            animation: dividerPulse 3s infinite;
        }

        @keyframes dividerPulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* VS Badge */
        .vs-badge {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.95), rgba(123, 47, 247, 0.95));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 900;
            color: #fff;
            box-shadow: 0 0 40px rgba(0, 217, 255, 0.6);
            border: 4px solid rgba(255, 255, 255, 0.3);
            z-index: 101;
            animation: vsRotate 10s linear infinite;
        }

        @keyframes vsRotate {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Glow Effects */
        .glow-effect {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: glowPulse 4s infinite;
        }

        .glow-team-a {
            top: 200px;
            left: 200px;
            background: #00d9ff;
        }

        .glow-team-b {
            top: 200px;
            right: 200px;
            background: #ffd700;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.1); }
        }

        /* Responsive for different resolutions */
        @media (max-width: 1920px) {
            body {
                transform-origin: top left;
                transform: scale(calc(100vw / 1920));
            }
        }
    </style>
</head>
<body>
    <!-- Glow Effects -->
    <div class="glow-effect glow-team-a"></div>
    <div class="glow-effect glow-team-b"></div>

    <!-- Match Header -->
    <div class="match-header">
        <div class="match-title" id="matchTitle">{{ $match->name }}</div>
    </div>

    <!-- Phase Indicator -->
    <div class="phase-indicator">
        <div class="phase-badge" id="phaseBadge">Waiting</div>
    </div>

    <!-- Center Divider & VS Badge -->
    <div class="center-divider"></div>
    <div class="vs-badge">VS</div>

    <!-- Team A Container -->
    <div class="team-container team-a">
        <div class="team-header">
            <div class="team-name" id="teamAName">{{ $match->team_a_name }}</div>
        </div>

        <div class="picks-section">
            <div class="section-label">⚔️ PICKS</div>
            <div class="picks-grid" id="teamAPicks">
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
            </div>
        </div>

        <div class="bans-section">
            <div class="section-label">🚫 BANS</div>
            <div class="bans-grid" id="teamABans">
                <div class="ban-slot empty">×</div>
                <div class="ban-slot empty">×</div>
                <div class="ban-slot empty">×</div>
            </div>
        </div>
    </div>

    <!-- Team B Container -->
    <div class="team-container team-b">
        <div class="team-header">
            <div class="team-name" id="teamBName">{{ $match->team_b_name }}</div>
        </div>

        <div class="picks-section">
            <div class="section-label">⚔️ PICKS</div>
            <div class="picks-grid" id="teamBPicks">
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
                <div class="hero-slot empty">+</div>
            </div>
        </div>

        <div class="bans-section">
            <div class="section-label">🚫 BANS</div>
            <div class="bans-grid" id="teamBBans">
                <div class="ban-slot empty">×</div>
                <div class="ban-slot empty">×</div>
                <div class="ban-slot empty">×</div>
            </div>
        </div>
    </div>

    <script>
        const matchId = {{ $match->id }};
        let currentState = null;

        function updateDisplay(state) {
            currentState = state;

            // Update phase
            const phaseBadge = document.getElementById('phaseBadge');
            phaseBadge.className = 'phase-badge ' + state.phase;
            phaseBadge.textContent = state.phase === 'ban' ? '🚫 BAN PHASE' : 
                                     state.phase === 'pick' ? '⚔️ PICK PHASE' : 
                                     '🔒 LOCKED';

            // Update Team A
            updateTeam('A', state.team_a_picks, state.team_a_bans);

            // Update Team B
            updateTeam('B', state.team_b_picks, state.team_b_bans);
        }

        function updateTeam(team, picks, bans) {
            const picksContainer = document.getElementById(`team${team}Picks`);
            const bansContainer = document.getElementById(`team${team}Bans`);

            // Update picks
            picksContainer.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const slot = document.createElement('div');
                if (picks[i]) {
                    slot.className = 'hero-slot filled';
                    slot.innerHTML = `
                        <img src="${picks[i].image}" alt="${picks[i].name}">
                        <div class="hero-name">${picks[i].name}</div>
                    `;
                } else {
                    slot.className = 'hero-slot empty';
                    slot.textContent = '+';
                }
                picksContainer.appendChild(slot);
            }

            // Update bans
            bansContainer.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const slot = document.createElement('div');
                if (bans[i]) {
                    slot.className = 'ban-slot filled';
                    slot.innerHTML = `
                        <img src="${bans[i].image}" alt="${bans[i].name}">
                        <div class="hero-name">${bans[i].name}</div>
                    `;
                } else {
                    slot.className = 'ban-slot empty';
                    slot.textContent = '×';
                }
                bansContainer.appendChild(slot);
            }
        }

        // Poll for updates
        function pollUpdates() {
            fetch(`/mlbb/overlay/match/${matchId}/state`)
                .then(response => response.json())
                .then(data => {
                    if (JSON.stringify(data) !== JSON.stringify(currentState)) {
                        updateDisplay(data);
                    }
                })
                .catch(error => console.error('Error fetching state:', error));
        }

        // Initialize
        pollUpdates();
        setInterval(pollUpdates, 1000);

        // Add sound effects for picks/bans
        function playSound(type) {
            // Can integrate audio later
            console.log(`Sound: ${type}`);
        }
    </script>
</body>
</html>
