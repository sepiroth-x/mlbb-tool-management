<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1920, height=1080">
    <title>MLBB Pick/Ban Overlay</title>
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
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .overlay-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* Match Header */
        .match-header {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            z-index: 100;
        }

        .match-title {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .match-phase {
            font-size: 20px;
            font-weight: 600;
            color: #fbbc04;
            text-transform: uppercase;
        }

        /* Team Containers */
        .team-container {
            position: absolute;
            top: 100px;
            width: 500px;
        }

        .team-container.team-a {
            left: 50px;
        }

        .team-container.team-b {
            right: 50px;
        }

        .team-name {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            margin-bottom: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, rgba(26, 115, 232, 0.8), rgba(26, 115, 232, 0.4));
            border-radius: 8px;
        }

        .team-container.team-b .team-name {
            background: linear-gradient(135deg, rgba(234, 67, 53, 0.8), rgba(234, 67, 53, 0.4));
        }

        /* Picks Section */
        .picks-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            margin-bottom: 10px;
            padding-left: 10px;
        }

        .hero-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .hero-item {
            display: flex;
            align-items: center;
            background: rgba(15, 15, 35, 0.9);
            border: 2px solid rgba(26, 115, 232, 0.5);
            border-radius: 8px;
            padding: 10px;
            height: 80px;
            opacity: 0;
            transform: translateX(-50px);
            animation: slideIn 0.5s forwards;
        }

        .team-container.team-b .hero-item {
            transform: translateX(50px);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-item img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            margin-right: 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .hero-info {
            flex: 1;
        }

        .hero-name {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .hero-role {
            font-size: 14px;
            color: #fbbc04;
            font-weight: 600;
        }

        /* Bans Section */
        .bans-section {
            margin-top: 20px;
        }

        .ban-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ban-item {
            width: 70px;
            height: 70px;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid rgba(234, 67, 53, 0.7);
            opacity: 0;
            transform: scale(0);
            animation: popIn 0.4s forwards;
        }

        @keyframes popIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .ban-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%) brightness(0.4);
        }

        .ban-item::after {
            content: '✕';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: #ea4335;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        /* Phase Indicator */
        .phase-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #1a73e8, #ea4335);
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 3px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translateX(-50%) scale(1); }
            50% { transform: translateX(-50%) scale(1.05); }
        }

        .phase-locked {
            background: linear-gradient(135deg, #34a853, #fbbc04);
        }
    </style>
</head>
<body>
    <div class="overlay-container">
        <!-- Match Header -->
        <div class="match-header">
            <div class="match-title" id="matchTitle">{{ $matchState['name'] }}</div>
            <div class="match-phase" id="matchPhase">{{ strtoupper($matchState['phase']) }} PHASE</div>
        </div>

        <!-- Team A -->
        <div class="team-container team-a">
            <div class="team-name" id="teamAName">{{ $matchState['team_a']['name'] }}</div>
            
            <div class="picks-section">
                <div class="section-title">PICKS</div>
                <div class="hero-grid" id="teamAPicks"></div>
            </div>

            <div class="bans-section">
                <div class="section-title">BANS</div>
                <div class="ban-grid" id="teamABans"></div>
            </div>
        </div>

        <!-- Team B -->
        <div class="team-container team-b">
            <div class="team-name" id="teamBName">{{ $matchState['team_b']['name'] }}</div>
            
            <div class="picks-section">
                <div class="section-title">PICKS</div>
                <div class="hero-grid" id="teamBPicks"></div>
            </div>

            <div class="bans-section">
                <div class="section-title">BANS</div>
                <div class="ban-grid" id="teamBBans"></div>
            </div>
        </div>

        <!-- Phase Indicator -->
        <div class="phase-indicator" id="phaseIndicator">
            <span id="phaseText">{{ strtoupper($matchState['phase']) }} PHASE</span>
        </div>
    </div>

    <script>
        const matchId = {{ $matchId }};
        let lastUpdate = null;

        // Initial render
        let matchState = @json($matchState);
        renderOverlay(matchState);

        // Poll for updates every 2 seconds
        setInterval(pollMatchState, 2000);

        async function pollMatchState() {
            try {
                const response = await fetch(`/mlbb/overlay/match/${matchId}/state`);
                const result = await response.json();

                if (result.success) {
                    const newState = result.data;
                    
                    // Check if state changed
                    if (lastUpdate !== newState.updated_at) {
                        matchState = newState;
                        renderOverlay(matchState);
                        lastUpdate = newState.updated_at;
                    }
                }
            } catch (error) {
                console.error('Poll error:', error);
            }
        }

        function renderOverlay(state) {
            // Update match info
            document.getElementById('matchTitle').textContent = state.name;
            document.getElementById('matchPhase').textContent = state.phase.toUpperCase() + ' PHASE';
            document.getElementById('phaseText').textContent = state.phase.toUpperCase() + ' PHASE';
            
            // Update phase indicator style
            const phaseIndicator = document.getElementById('phaseIndicator');
            if (state.phase === 'locked') {
                phaseIndicator.classList.add('phase-locked');
            } else {
                phaseIndicator.classList.remove('phase-locked');
            }

            // Update team names
            document.getElementById('teamAName').textContent = state.team_a.name;
            document.getElementById('teamBName').textContent = state.team_b.name;

            // Update Team A
            renderTeamPicks('teamAPicks', state.team_a.picks_details);
            renderTeamBans('teamABans', state.team_a.bans_details);

            // Update Team B
            renderTeamPicks('teamBPicks', state.team_b.picks_details);
            renderTeamBans('teamBBans', state.team_b.bans_details);
        }

        function renderTeamPicks(containerId, picks) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            picks.forEach((hero, index) => {
                const item = document.createElement('div');
                item.className = 'hero-item';
                item.style.animationDelay = `${index * 0.1}s`;
                item.innerHTML = `
                    <img src="${hero.image}" alt="${hero.name}">
                    <div class="hero-info">
                        <div class="hero-name">${hero.name}</div>
                        <div class="hero-role">${hero.role}</div>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        function renderTeamBans(containerId, bans) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            bans.forEach((hero, index) => {
                const item = document.createElement('div');
                item.className = 'ban-item';
                item.style.animationDelay = `${index * 0.1}s`;
                item.innerHTML = `<img src="${hero.image}" alt="${hero.name}">`;
                container.appendChild(item);
            });
        }
    </script>
</body>
</html>
