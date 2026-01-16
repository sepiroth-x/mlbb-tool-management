@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'Tournament Overlay Control')

@section('content')
<style>
    .overlay-admin-modern {
        padding: 2rem 0;
        min-height: 100vh;
    }

    .admin-header {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.1), rgba(123, 47, 247, 0.1));
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .admin-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(0,217,255,0.1)" stroke-width="0.5"/></svg>');
        opacity: 0.3;
        z-index: 0;
    }

    .admin-header-content {
        position: relative;
        z-index: 1;
    }

    .admin-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .admin-header p {
        color: #94a3b8;
        font-size: 1.1rem;
    }

    .admin-layout {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Sidebar */
    .admin-sidebar {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .sidebar-section {
        margin-bottom: 2rem;
    }

    .sidebar-section:last-child {
        margin-bottom: 0;
    }

    .sidebar-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #00d9ff;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .create-match-card {
        background: rgba(30, 41, 59, 0.6);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        color: #e2e8f0;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #00d9ff;
        box-shadow: 0 0 0 3px rgba(0, 217, 255, 0.1);
    }

    .form-input::placeholder {
        color: #64748b;
    }

    .team-names-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:active::before {
        width: 300px;
        height: 300px;
    }

    .btn-create {
        background: linear-gradient(135deg, #00d9ff, #7b2ff7);
        color: #fff;
        box-shadow: 0 4px 15px rgba(0, 217, 255, 0.3);
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 217, 255, 0.4);
    }

    .match-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .match-item {
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.2);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .match-item:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.05);
        transform: translateX(5px);
    }

    .match-item.active {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.1);
        box-shadow: 0 0 15px rgba(0, 217, 255, 0.2);
    }

    .match-item-name {
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 0.25rem;
    }

    .match-item-teams {
        font-size: 0.9rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }

    .match-item-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .match-item-status.pending {
        background: rgba(251, 188, 4, 0.2);
        color: #fbbc04;
    }

    .match-item-status.active {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }

    .match-item-status.completed {
        background: rgba(148, 163, 184, 0.2);
        color: #94a3b8;
    }

    /* Main Control Panel */
    .control-main {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 16px;
        padding: 2rem;
    }

    .control-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 217, 255, 0.2);
    }

    .control-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #e2e8f0;
    }

    .control-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-action {
        padding: 0.625rem 1.25rem;
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        background: rgba(0, 217, 255, 0.1);
        color: #00d9ff;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-action:hover {
        background: rgba(0, 217, 255, 0.2);
        border-color: #00d9ff;
        transform: translateY(-2px);
    }

    .btn-action.danger {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .btn-action.danger:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
    }

    .phase-control {
        background: rgba(30, 41, 59, 0.6);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .phase-label {
        font-size: 1rem;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .phase-buttons {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .phase-btn {
        padding: 1rem;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        color: #94a3b8;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .phase-btn:hover {
        border-color: #00d9ff;
        color: #00d9ff;
    }

    .phase-btn.active {
        background: linear-gradient(135deg, #00d9ff, #7b2ff7);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
    }

    .teams-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .team-control {
        background: rgba(30, 41, 59, 0.6);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .team-control.team-a {
        border: 2px solid rgba(0, 217, 255, 0.3);
    }

    .team-control.team-b {
        border: 2px solid rgba(255, 215, 0, 0.3);
    }

    .team-header {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-align: center;
        padding: 0.75rem;
        border-radius: 8px;
    }

    .team-control.team-a .team-header {
        background: rgba(0, 217, 255, 0.1);
        color: #00d9ff;
    }

    .team-control.team-b .team-header {
        background: rgba(255, 215, 0, 0.1);
        color: #ffd700;
    }

    .picks-section, .bans-section {
        margin-bottom: 1.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0, 217, 255, 0.2);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #cbd5e1;
    }

    .section-count {
        font-size: 0.9rem;
        font-weight: 700;
        color: #00d9ff;
    }

    .hero-slots {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
    }

    .hero-slots.bans {
        grid-template-columns: repeat(3, 1fr);
    }

    .hero-slot {
        aspect-ratio: 1;
        background: rgba(15, 23, 42, 0.8);
        border: 2px dashed rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .hero-slot:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.05);
    }

    .hero-slot.filled {
        border-style: solid;
        border-color: #00d9ff;
        padding: 0;
    }

    .hero-slot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .hero-slot-placeholder {
        font-size: 2rem;
        color: #64748b;
    }

    .hero-remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 24px;
        height: 24px;
        background: rgba(239, 68, 68, 0.9);
        border: none;
        border-radius: 4px;
        color: #fff;
        font-size: 0.75rem;
        cursor: pointer;
        opacity: 0;
        transition: all 0.2s ease;
    }

    .hero-slot:hover .hero-remove-btn {
        opacity: 1;
    }

    .team-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-team {
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .btn-team.pick {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        color: #22c55e;
    }

    .btn-team.pick:hover {
        background: rgba(34, 197, 94, 0.3);
        border-color: #22c55e;
    }

    .btn-team.ban {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #ef4444;
    }

    .btn-team.ban:hover {
        background: rgba(239, 68, 68, 0.3);
        border-color: #ef4444;
    }

    .global-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 217, 255, 0.2);
    }

    .btn-undo {
        padding: 0.875rem 2rem;
        background: rgba(251, 188, 4, 0.2);
        border: 1px solid rgba(251, 188, 4, 0.4);
        border-radius: 8px;
        color: #fbbc04;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-undo:hover {
        background: rgba(251, 188, 4, 0.3);
        border-color: #fbbc04;
        transform: translateY(-2px);
    }

    .no-match-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }

    .no-match-icon {
        font-size: 5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .no-match-text {
        font-size: 1.2rem;
    }

    /* Hero Selector Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
    }

    .modal-content {
        position: relative;
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 16px;
        width: 90%;
        max-width: 900px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 217, 255, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00d9ff;
    }

    .close-btn {
        width: 36px;
        height: 36px;
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        color: #ef4444;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover {
        background: rgba(239, 68, 68, 0.3);
        border-color: #ef4444;
    }

    .modal-body {
        padding: 2rem;
        max-height: calc(80vh - 100px);
        overflow-y: auto;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 1rem;
    }

    .hero-card {
        background: rgba(30, 41, 59, 0.6);
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .hero-card:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.1);
        transform: translateY(-3px);
    }

    .hero-card img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .hero-card-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #e2e8f0;
    }

    @media (max-width: 1024px) {
        .admin-layout {
            grid-template-columns: 1fr;
        }

        .teams-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="overlay-admin-modern">
    <div class="container">
        <div class="admin-header">
            <div class="admin-header-content">
                <h1>🎮 Tournament Overlay Control</h1>
                <p>Real-time pick/ban management for professional esports broadcasting</p>
            </div>
        </div>

        <div class="admin-layout">
            <!-- Sidebar -->
            <div class="admin-sidebar">
                <div class="sidebar-section">
                    <div class="sidebar-title">➕ Create Match</div>
                    <div class="create-match-card">
                        <div class="form-group">
                            <label class="form-label">Match Name</label>
                            <input type="text" id="matchName" class="form-input" placeholder="Finals - Game 1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Team Names</label>
                            <div class="team-names-grid">
                                <input type="text" id="teamAName" class="form-input" placeholder="Team A">
                                <input type="text" id="teamBName" class="form-input" placeholder="Team B">
                            </div>
                        </div>
                        <button onclick="createMatch()" class="btn btn-create">Create Match</button>
                    </div>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-title">📋 Recent Matches</div>
                    <div class="match-list" id="matchesList">
                        @foreach($matches as $match)
                        <div class="match-item {{ $match->status === 'active' ? 'active' : '' }}" data-match-id="{{ $match->id }}" onclick="selectMatch({{ $match->id }})">
                            <div class="match-item-name">{{ $match->name }}</div>
                            <div class="match-item-teams">{{ $match->team_a_name }} vs {{ $match->team_b_name }}</div>
                            <span class="match-item-status {{ $match->status }}">{{ ucfirst($match->status) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Control Panel -->
            <div class="control-main">
                <div id="activeMatchControls" style="display: none;">
                    <div class="control-header">
                        <div class="control-title" id="activeMatchName">No Match Selected</div>
                        <div class="control-actions">
                            <button onclick="copyOverlayUrl()" class="btn-action">
                                📋 Copy URL
                            </button>
                            <button onclick="openOverlay()" class="btn-action">
                                🔗 Open Overlay
                            </button>
                            <button onclick="resetMatch()" class="btn-action danger">
                                🔄 Reset
                            </button>
                        </div>
                    </div>

                    <div class="phase-control">
                        <div class="phase-label">⏱️ Current Phase</div>
                        <div class="phase-buttons">
                            <button class="phase-btn" data-phase="ban" onclick="setPhase('ban')">🚫 Ban</button>
                            <button class="phase-btn" data-phase="pick" onclick="setPhase('pick')">⚔️ Pick</button>
                            <button class="phase-btn" data-phase="locked" onclick="setPhase('locked')">🔒 Locked</button>
                        </div>
                    </div>

                    <div class="teams-grid">
                        <!-- Team A -->
                        <div class="team-control team-a">
                            <div class="team-header" id="teamANameDisplay">Team A</div>
                            
                            <div class="picks-section">
                                <div class="section-header">
                                    <div class="section-title">Picks</div>
                                    <div class="section-count"><span id="teamAPickCount">0</span>/5</div>
                                </div>
                                <div class="hero-slots" id="teamAPicks">
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                </div>
                            </div>

                            <div class="bans-section">
                                <div class="section-header">
                                    <div class="section-title">Bans</div>
                                    <div class="section-count"><span id="teamABanCount">0</span>/3</div>
                                </div>
                                <div class="hero-slots bans" id="teamABans">
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                </div>
                            </div>

                            <div class="team-actions">
                                <button onclick="openHeroSelector('a', 'pick')" class="btn-team pick">Add Pick</button>
                                <button onclick="openHeroSelector('a', 'ban')" class="btn-team ban">Add Ban</button>
                            </div>
                        </div>

                        <!-- Team B -->
                        <div class="team-control team-b">
                            <div class="team-header" id="teamBNameDisplay">Team B</div>
                            
                            <div class="picks-section">
                                <div class="section-header">
                                    <div class="section-title">Picks</div>
                                    <div class="section-count"><span id="teamBPickCount">0</span>/5</div>
                                </div>
                                <div class="hero-slots" id="teamBPicks">
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                </div>
                            </div>

                            <div class="bans-section">
                                <div class="section-header">
                                    <div class="section-title">Bans</div>
                                    <div class="section-count"><span id="teamBBanCount">0</span>/3</div>
                                </div>
                                <div class="hero-slots bans" id="teamBBans">
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                    <div class="hero-slot"><div class="hero-slot-placeholder">+</div></div>
                                </div>
                            </div>

                            <div class="team-actions">
                                <button onclick="openHeroSelector('b', 'pick')" class="btn-team pick">Add Pick</button>
                                <button onclick="openHeroSelector('b', 'ban')" class="btn-team ban">Add Ban</button>
                            </div>
                        </div>
                    </div>

                    <div class="global-actions">
                        <button onclick="undoLastAction()" class="btn-undo">
                            ↩️ Undo Last Action
                        </button>
                    </div>
                </div>

                <div id="noMatchSelected" class="no-match-state">
                    <div class="no-match-icon">🎮</div>
                    <div class="no-match-text">Select or create a match to begin overlay control</div>
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
            <div class="modal-title" id="heroSelectorTitle">Select Hero</div>
            <button class="close-btn" onclick="closeHeroSelector()">×</button>
        </div>
        <div class="modal-body">
            <div class="hero-grid" id="heroSelectorGrid">
                @foreach($heroes as $hero)
                <div class="hero-card" data-slug="{{ $hero['slug'] }}" onclick="selectHeroForAction('{{ $hero['slug'] }}')">
                    <img src="{{ asset('modules/mlbb-tool-management/images/heroes/' . $hero['image']) }}" 
                         alt="{{ $hero['name'] }}"
                         loading="lazy"
                         decoding="async">
                    <div class="hero-card-name">{{ $hero['name'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    let currentMatchId = null;
    let currentTeam = null;
    let currentAction = null;
    let matchState = null;

    // (Rest of the JavaScript remains the same as original, just update any CSS class references)
</script>
@endsection
