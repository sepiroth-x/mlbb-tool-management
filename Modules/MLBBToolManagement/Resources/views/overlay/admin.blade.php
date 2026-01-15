@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'Live Overlay Admin Panel')

@section('content')
<div class="overlay-admin-container">
    <div class="container">
        <div class="page-header">
            <h2>Live Pick/Ban Overlay Control Panel</h2>
            <p>Manage real-time hero picks and bans for tournament streaming</p>
        </div>

        <!-- Match Management -->
        <div class="admin-grid">
            <!-- Left Panel: Match Selection & Creation -->
            <div class="admin-panel match-panel">
                <h3>Match Management</h3>
                
                <div class="create-match-form">
                    <h4>Create New Match</h4>
                    <input type="text" id="matchName" placeholder="Match Name (e.g., Finals - Game 1)" class="form-input">
                    <div class="team-names">
                        <input type="text" id="teamAName" placeholder="Team A Name" class="form-input">
                        <input type="text" id="teamBName" placeholder="Team B Name" class="form-input">
                    </div>
                    <button onclick="createMatch()" class="btn-primary">Create Match</button>
                </div>

                <div class="match-list">
                    <h4>Recent Matches</h4>
                    <div id="matchesList">
                        @foreach($matches as $match)
                        <div class="match-item {{ $match->status }}" data-match-id="{{ $match->id }}" onclick="selectMatch({{ $match->id }})">
                            <div class="match-info">
                                <strong>{{ $match->name }}</strong>
                                <span class="match-teams">{{ $match->team_a_name }} vs {{ $match->team_b_name }}</span>
                                <span class="match-status">{{ ucfirst($match->status) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Panel: Active Match Control -->
            <div class="admin-panel control-panel">
                <div id="activeMatchControls" style="display: none;">
                    <div class="match-header">
                        <h3 id="activeMatchName">No Match Selected</h3>
                        <div class="match-actions">
                            <button onclick="copyOverlayUrl()" class="btn-secondary">Copy Overlay URL</button>
                            <button onclick="openOverlay()" class="btn-secondary">Open Overlay</button>
                            <button onclick="resetMatch()" class="btn-danger">Reset Match</button>
                        </div>
                    </div>

                    <!-- Phase Control -->
                    <div class="phase-control">
                        <h4>Current Phase</h4>
                        <div class="phase-buttons">
                            <button class="phase-btn" data-phase="ban" onclick="setPhase('ban')">Ban Phase</button>
                            <button class="phase-btn" data-phase="pick" onclick="setPhase('pick')">Pick Phase</button>
                            <button class="phase-btn" data-phase="locked" onclick="setPhase('locked')">Locked</button>
                        </div>
                    </div>

                    <!-- Team Controls -->
                    <div class="team-controls-grid">
                        <!-- Team A Controls -->
                        <div class="team-control team-a-control">
                            <h4 id="teamANameDisplay">Team A</h4>
                            
                            <div class="picks-section">
                                <h5>Picks (<span id="teamAPickCount">0</span>/5)</h5>
                                <div class="hero-slots" id="teamAPicks"></div>
                            </div>

                            <div class="bans-section">
                                <h5>Bans (<span id="teamABanCount">0</span>/3)</h5>
                                <div class="hero-slots small" id="teamABans"></div>
                            </div>

                            <div class="action-buttons">
                                <button onclick="openHeroSelector('a', 'pick')" class="btn-primary">Add Pick</button>
                                <button onclick="openHeroSelector('a', 'ban')" class="btn-secondary">Add Ban</button>
                            </div>
                        </div>

                        <!-- Team B Controls -->
                        <div class="team-control team-b-control">
                            <h4 id="teamBNameDisplay">Team B</h4>
                            
                            <div class="picks-section">
                                <h5>Picks (<span id="teamBPickCount">0</span>/5)</h5>
                                <div class="hero-slots" id="teamBPicks"></div>
                            </div>

                            <div class="bans-section">
                                <h5>Bans (<span id="teamBBanCount">0</span>/3)</h5>
                                <div class="hero-slots small" id="teamBBans"></div>
                            </div>

                            <div class="action-buttons">
                                <button onclick="openHeroSelector('b', 'pick')" class="btn-primary">Add Pick</button>
                                <button onclick="openHeroSelector('b', 'ban')" class="btn-secondary">Add Ban</button>
                            </div>
                        </div>
                    </div>

                    <!-- Undo Button -->
                    <div class="global-actions">
                        <button onclick="undoLastAction()" class="btn-warning">Undo Last Action</button>
                    </div>
                </div>

                <div id="noMatchSelected" class="no-match-placeholder">
                    <p>Select or create a match to begin</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Selector Modal -->
<div id="heroSelectorModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeHeroSelector()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="heroSelectorTitle">Select Hero</h3>
            <button class="close-btn" onclick="closeHeroSelector()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="hero-grid" id="heroSelectorGrid">
                @foreach($heroes as $hero)
                <div class="hero-card-small" data-slug="{{ $hero['slug'] }}" onclick="selectHeroForAction('{{ $hero['slug'] }}')">
                    <img src="{{ asset('modules/mlbb-tool-management/images/heroes/' . $hero['image']) }}" 
                         alt="{{ $hero['name'] }}"
                         loading="lazy"
                         decoding="async"
                         width="80"
                         height="80">
                    <span>{{ $hero['name'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentMatchId = null;
    let currentTeam = null;
    let currentAction = null;
    let matchState = null;

    // Create new match
    async function createMatch() {
        const name = document.getElementById('matchName').value;
        const teamAName = document.getElementById('teamAName').value;
        const teamBName = document.getElementById('teamBName').value;

        if (!name) {
            alert('Please enter a match name');
            return;
        }

        try {
            const response = await fetch('{{ route("mlbb.overlay.match.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: name,
                    team_a_name: teamAName || 'Team A',
                    team_b_name: teamBName || 'Team B'
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('Match created successfully!');
                location.reload();
            } else {
                alert('Failed to create match: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    }

    // Select match
    async function selectMatch(matchId) {
        try {
            const response = await fetch(`/mlbb/overlay/match/${matchId}/select`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                currentMatchId = matchId;
                matchState = result.data;
                updateMatchDisplay();
                document.getElementById('noMatchSelected').style.display = 'none';
                document.getElementById('activeMatchControls').style.display = 'block';
                
                // Highlight selected match
                document.querySelectorAll('.match-item').forEach(item => {
                    item.classList.remove('selected');
                });
                document.querySelector(`[data-match-id="${matchId}"]`).classList.add('selected');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load match');
        }
    }

    // Update match display
    function updateMatchDisplay() {
        if (!matchState) return;

        document.getElementById('activeMatchName').textContent = matchState.name;
        document.getElementById('teamANameDisplay').textContent = matchState.team_a.name;
        document.getElementById('teamBNameDisplay').textContent = matchState.team_b.name;

        // Update phase buttons
        document.querySelectorAll('.phase-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.phase === matchState.phase) {
                btn.classList.add('active');
            }
        });

        // Update Team A
        updateTeamDisplay('a', matchState.team_a);
        // Update Team B
        updateTeamDisplay('b', matchState.team_b);
    }

    // Update team display
    function updateTeamDisplay(team, data) {
        const picksContainer = document.getElementById(`team${team.toUpperCase()}Picks`);
        const bansContainer = document.getElementById(`team${team.toUpperCase()}Bans`);
        const pickCount = document.getElementById(`team${team.toUpperCase()}PickCount`);
        const banCount = document.getElementById(`team${team.toUpperCase()}BanCount`);

        // Update picks
        picksContainer.innerHTML = '';
        data.picks_details.forEach(hero => {
            picksContainer.innerHTML += `
                <div class="hero-slot-filled">
                    <img src="${hero.image}" 
                         alt="${hero.name}"
                         loading="lazy"
                         decoding="async"
                         width="60"
                         height="60">
                    <span>${hero.name}</span>
                </div>
            `;
        });
        pickCount.textContent = data.picks_details.length;

        // Update bans
        bansContainer.innerHTML = '';
        data.bans_details.forEach(hero => {
            bansContainer.innerHTML += `
                <div class="hero-slot-filled">
                    <img src="${hero.image}" 
                         alt="${hero.name}"
                         loading="lazy"
                         decoding="async"
                         width="60"
                         height="60">
                    <span>${hero.name}</span>
                </div>
            `;
        });
        banCount.textContent = data.bans_details.length;
    }

    // Open hero selector
    function openHeroSelector(team, action) {
        currentTeam = team;
        currentAction = action;
        
        const title = `Select Hero to ${action.charAt(0).toUpperCase() + action.slice(1)} for Team ${team.toUpperCase()}`;
        document.getElementById('heroSelectorTitle').textContent = title;
        
        // Filter out already selected heroes
        const unavailable = [
            ...matchState.team_a.picks,
            ...matchState.team_b.picks,
            ...matchState.team_a.bans,
            ...matchState.team_b.bans
        ];

        document.querySelectorAll('.hero-card-small').forEach(card => {
            if (unavailable.includes(card.dataset.slug)) {
                card.classList.add('disabled');
                card.onclick = null;
            } else {
                card.classList.remove('disabled');
                card.onclick = () => selectHeroForAction(card.dataset.slug);
            }
        });

        document.getElementById('heroSelectorModal').style.display = 'block';
    }

    // Close hero selector
    function closeHeroSelector() {
        document.getElementById('heroSelectorModal').style.display = 'none';
    }

    // Select hero for action
    async function selectHeroForAction(heroSlug) {
        if (!currentMatchId || !currentTeam || !currentAction) return;

        try {
            const url = `/mlbb/overlay/match/${currentMatchId}/${currentAction}`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    team: currentTeam,
                    hero: heroSlug
                })
            });

            const result = await response.json();

            if (result.success) {
                matchState = result.data;
                updateMatchDisplay();
                closeHeroSelector();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    }

    // Set phase
    async function setPhase(phase) {
        if (!currentMatchId) return;

        try {
            const response = await fetch(`/mlbb/overlay/match/${currentMatchId}/phase`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phase: phase })
            });

            const result = await response.json();

            if (result.success) {
                matchState = result.data;
                updateMatchDisplay();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Undo last action
    async function undoLastAction() {
        if (!currentMatchId) return;

        if (!confirm('Undo the last action?')) return;

        try {
            const response = await fetch(`/mlbb/overlay/match/${currentMatchId}/undo`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                matchState = result.data;
                updateMatchDisplay();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Reset match
    async function resetMatch() {
        if (!currentMatchId) return;

        if (!confirm('Reset this match? This will clear all picks and bans.')) return;

        try {
            const response = await fetch(`/mlbb/overlay/match/${currentMatchId}/reset`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                matchState = result.data;
                updateMatchDisplay();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Copy overlay URL
    function copyOverlayUrl() {
        if (!currentMatchId) return;

        const url = `${window.location.origin}/mlbb/overlay/display/${currentMatchId}`;
        navigator.clipboard.writeText(url).then(() => {
            alert('Overlay URL copied to clipboard!\n\n' + url);
        });
    }

    // Open overlay in new window
    function openOverlay() {
        if (!currentMatchId) return;

        const url = `/mlbb/overlay/display/${currentMatchId}`;
        window.open(url, 'MLBBOverlay', 'width=1920,height=1080');
    }
</script>
@endpush
