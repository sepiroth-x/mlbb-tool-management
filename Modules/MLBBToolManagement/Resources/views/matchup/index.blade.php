@extends('mlbb-tool-management-theme::layouts.app')

@section('title', 'Team Matchup Analysis Tool')

@section('content')
<style>
    .matchup-tool-container {
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

    .teams-container {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
        align-items: start;
    }

    .team-section {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .team-section.team-a {
        border-color: rgba(0, 217, 255, 0.5);
    }

    .team-section.team-b {
        border-color: rgba(255, 215, 0, 0.5);
    }

    .team-section h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-align: center;
    }

    .team-section.team-a h3 {
        color: #00d9ff;
    }

    .team-section.team-b h3 {
        color: #ffd700;
    }

    .selected-heroes {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .hero-slot {
        background: rgba(15, 23, 42, 0.8);
        border: 2px dashed rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
        min-height: 70px;
    }

    .hero-slot:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.05);
        transform: translateX(5px);
    }

    .team-b .hero-slot:hover {
        border-color: #ffd700;
        background: rgba(255, 215, 0, 0.05);
        transform: translateX(-5px);
    }

    .hero-slot.filled {
        border-style: solid;
        border-color: rgba(0, 217, 255, 0.5);
        background: rgba(0, 217, 255, 0.1);
    }

    .team-b .hero-slot.filled {
        border-color: rgba(255, 215, 0, 0.5);
        background: rgba(255, 215, 0, 0.1);
    }

    .hero-slot .slot-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00d9ff;
        min-width: 30px;
        text-align: center;
    }

    .team-b .hero-slot .slot-number {
        color: #ffd700;
    }

    .hero-slot .slot-label {
        color: #64748b;
        font-size: 0.9rem;
    }

    .hero-slot img {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
    }

    .hero-slot .hero-info {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .hero-slot .hero-name {
        font-weight: 600;
        color: #e2e8f0;
        font-size: 1rem;
    }

    .hero-slot .hero-role {
        font-size: 0.85rem;
        color: #64748b;
    }

    .hero-slot .remove-hero {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.5);
        color: #ef4444;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .hero-slot .remove-hero:hover {
        background: rgba(239, 68, 68, 0.3);
        transform: scale(1.1);
    }

    .vs-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 3rem;
    }

    .vs-divider span {
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 0 30px rgba(0, 217, 255, 0.5);
    }

    .analyze-section {
        text-align: center;
        margin: 2rem 0;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-large {
        padding: 1rem 3rem;
        font-size: 1.2rem;
        font-weight: 700;
        border-radius: 8px;
        background: linear-gradient(135deg, #00d9ff, #0ea5e9);
        border: none;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 0 30px rgba(0, 217, 255, 0.4);
    }

    .btn-large:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 40px rgba(0, 217, 255, 0.6);
    }

    .btn-large.btn-clear {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
    }

    .btn-large.btn-clear:hover {
        box-shadow: 0 0 40px rgba(239, 68, 68, 0.6);
    }

    .btn-large:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-large:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Hero Picker Modal */
    .hero-picker {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
    }

    .hero-picker-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }

    .hero-picker-content {
        position: relative;
        width: 90%;
        max-width: 1000px;
        max-height: 90vh;
        margin: 2rem auto;
        background: #0f172a;
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .hero-picker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .hero-picker-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00d9ff;
        margin: 0;
    }

    .close-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 2rem;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .close-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .hero-picker-search {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        color: #e2e8f0;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #00d9ff;
        box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
    }

    .search-input::placeholder {
        color: #64748b;
    }

    .hero-picker-filters {
        display: flex;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 6px;
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        border-color: #00d9ff;
        color: #00d9ff;
    }

    .filter-btn.active {
        background: rgba(0, 217, 255, 0.2);
        border-color: #00d9ff;
        color: #00d9ff;
    }

    .hero-picker-grid {
        overflow-y: auto;
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        max-height: calc(90vh - 250px);
    }

    .hero-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .hero-card:hover {
        border-color: #00d9ff;
        background: rgba(0, 217, 255, 0.1);
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 217, 255, 0.3);
    }

    .hero-card.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .hero-card.disabled:hover {
        transform: none;
        border-color: rgba(148, 163, 184, 0.3);
        background: rgba(15, 23, 42, 0.6);
        box-shadow: none;
    }

    .hero-card img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }

    .hero-card .hero-name {
        display: block;
        font-weight: 600;
        color: #e2e8f0;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .hero-card .hero-role {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .teams-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .vs-divider {
            padding: 1rem 0;
        }

        .vs-divider span {
            font-size: 2rem;
        }

        .hero-picker-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .page-header h2 {
            font-size: 1.8rem;
        }

        .hero-slot {
            padding: 0.75rem;
        }

        .hero-slot img {
            width: 40px;
            height: 40px;
        }

        .hero-picker-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }

        .hero-card img {
            height: 80px;
        }
    }

    /* Results Section Styles */
    .results-container {
        margin-top: 3rem;
        padding: 2rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
    }

    .results-title {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 2rem;
    }

    .win-probability {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .team-prob h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .team-a-prob h3 {
        color: #00d9ff;
    }

    .team-b-prob h3 {
        color: #ffd700;
    }

    .probability-bar {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        overflow: hidden;
        height: 50px;
    }

    .probability-fill {
        height: 100%;
        background: linear-gradient(90deg, #00d9ff, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        transition: width 1s ease;
    }

    .team-b-prob .probability-fill {
        background: linear-gradient(90deg, #ffd700, #f59e0b);
    }

    .team-analysis-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .team-analysis {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .team-analysis h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .team-a-analysis h3 {
        color: #00d9ff;
    }

    .team-b-analysis h3 {
        color: #ffd700;
    }

    .analysis-section {
        margin-bottom: 1.5rem;
    }

    .analysis-section h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 0.75rem;
    }

    .analysis-section ul {
        list-style: none;
        padding: 0;
    }

    .analysis-section li {
        padding: 0.5rem 0;
        padding-left: 1.5rem;
        position: relative;
        color: #cbd5e1;
    }

    .analysis-section li:before {
        content: "▸";
        position: absolute;
        left: 0;
        color: #00d9ff;
        font-weight: 700;
    }

    .strategy-item {
        padding: 0.75rem;
        background: rgba(0, 217, 255, 0.05);
        border-left: 3px solid #00d9ff;
        border-radius: 4px;
        margin-bottom: 0.5rem;
        color: #cbd5e1;
    }

    .strategy-item strong {
        color: #00d9ff;
    }

    .phase-analysis {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .phase-analysis h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .phase-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .phase-item h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #00d9ff;
        margin-bottom: 0.75rem;
    }

    .phase-comparison {
        margin-bottom: 0.5rem;
    }

    .team-phase {
        padding: 0.5rem;
        background: rgba(0, 217, 255, 0.05);
        border-radius: 4px;
        margin-bottom: 0.25rem;
        color: #cbd5e1;
    }

    .advantage {
        font-weight: 600;
        color: #ffd700;
        margin-top: 0.5rem;
    }

    /* AI Insights Styles */
    .ai-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        border-radius: 20px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
    }

    .ai-insights-section {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(99, 102, 241, 0.1));
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .ai-insights-section h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #a78bfa;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .ai-insights-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .ai-insights-card h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #c4b5fd;
        margin-bottom: 1rem;
    }

    .ai-insights-list {
        list-style: none;
        padding: 0;
    }

    .ai-insights-list li {
        padding: 0.75rem;
        padding-left: 2rem;
        position: relative;
        color: #e0e7ff;
        margin-bottom: 0.5rem;
        background: rgba(139, 92, 246, 0.05);
        border-radius: 6px;
    }

    .ai-insights-list li:before {
        content: "💡";
        position: absolute;
        left: 0.75rem;
    }

    .ai-strategy-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .ai-strategy-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .ai-strategy-card h4 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .team-a-card h4 {
        color: #00d9ff;
    }

    .team-b-card h4 {
        color: #ffd700;
    }

    .ai-strategy-card p {
        color: #cbd5e1;
        line-height: 1.6;
    }

    .ai-phase-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 8px;
        padding: 1.5rem;
    }

    .ai-phase-card h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #c4b5fd;
        margin-bottom: 1rem;
    }

    .ai-phase-card p {
        color: #cbd5e1;
        line-height: 1.6;
    }

    @media (max-width: 1024px) {
        .win-probability,
        .team-analysis-grid,
        .ai-strategy-grid {
            grid-template-columns: 1fr;
        }

        .phase-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Hero Details Section */
    .hero-details-section {
        margin-top: 2rem;
        padding: 1.5rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 12px;
    }

    .hero-details-section h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 1rem;
        text-align: center;
    }

    .hero-details-teams {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .hero-details-team h4 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .hero-details-team-a h4 {
        color: #00d9ff;
    }

    .hero-details-team-b h4 {
        color: #ffd700;
    }

    .hero-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 1rem;
    }

    .hero-detail-card {
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .hero-detail-card:hover {
        transform: translateY(-4px);
        border-color: #00d9ff;
        box-shadow: 0 8px 25px rgba(0, 217, 255, 0.3);
    }

    .hero-detail-card img {
        width: 100%;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }

    .hero-detail-card .hero-name-small {
        font-size: 0.85rem;
        color: #cbd5e1;
        font-weight: 600;
    }

    /* Hero Detail Overlay */
    .hero-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(10px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        overflow-y: auto;
    }

    .hero-overlay.active {
        display: flex;
    }

    .hero-overlay-content {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.95));
        border: 2px solid rgba(0, 217, 255, 0.5);
        border-radius: 16px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 217, 255, 0.3);
        position: relative;
    }

    .hero-overlay-header {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.2), rgba(255, 215, 0, 0.2));
        padding: 2rem;
        border-bottom: 2px solid rgba(0, 217, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .hero-overlay-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid rgba(0, 217, 255, 0.5);
        box-shadow: 0 10px 30px rgba(0, 217, 255, 0.4);
    }

    .hero-overlay-title-section {
        flex: 1;
    }

    .hero-overlay-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .hero-overlay-role {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(0, 217, 255, 0.2);
        border: 1px solid rgba(0, 217, 255, 0.5);
        border-radius: 20px;
        color: #00d9ff;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }

    .hero-overlay-difficulty {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(255, 215, 0, 0.2);
        border: 1px solid rgba(255, 215, 0, 0.5);
        border-radius: 20px;
        color: #ffd700;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .hero-overlay-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 40px;
        height: 40px;
        background: rgba(239, 68, 68, 0.2);
        border: 2px solid rgba(239, 68, 68, 0.5);
        border-radius: 50%;
        color: #ef4444;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .hero-overlay-close:hover {
        background: rgba(239, 68, 68, 0.4);
        transform: rotate(90deg);
    }

    .hero-overlay-body {
        padding: 2rem;
    }

    .hero-description {
        font-size: 1.1rem;
        color: #cbd5e1;
        line-height: 1.8;
        margin-bottom: 2rem;
        padding: 1rem;
        background: rgba(0, 217, 255, 0.05);
        border-left: 3px solid #00d9ff;
        border-radius: 6px;
    }

    .hero-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .hero-stat-card {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 1rem;
    }

    .hero-stat-card h5 {
        font-size: 0.9rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .hero-stat-bar {
        background: rgba(15, 23, 42, 0.9);
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .hero-stat-fill {
        height: 100%;
        background: linear-gradient(90deg, #00d9ff, #0ea5e9);
        border-radius: 5px;
        transition: width 0.8s ease;
    }

    .hero-phase-ratings {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .hero-phase-card {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }

    .hero-phase-card h5 {
        font-size: 1rem;
        color: #00d9ff;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .hero-phase-value {
        font-size: 1.8rem;
        font-weight: 800;
        background: linear-gradient(135deg, #00d9ff, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-detail-section {
        margin-bottom: 2rem;
    }

    .hero-detail-section h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(0, 217, 255, 0.3);
    }

    .hero-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .hero-tag {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.2), rgba(139, 92, 246, 0.2));
        border: 1px solid rgba(0, 217, 255, 0.4);
        border-radius: 20px;
        color: #00d9ff;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .hero-list {
        list-style: none;
        padding: 0;
    }

    .hero-list li {
        padding: 0.75rem;
        padding-left: 2rem;
        position: relative;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
        background: rgba(0, 217, 255, 0.05);
        border-left: 3px solid #00d9ff;
        border-radius: 6px;
    }

    .hero-list li:before {
        content: "⚡";
        position: absolute;
        left: 0.75rem;
        color: #00d9ff;
    }

    .hero-list.weak li {
        background: rgba(239, 68, 68, 0.05);
        border-left-color: #ef4444;
    }

    .hero-list.weak li:before {
        content: "⚠️";
    }

    .hero-list.synergy li {
        background: rgba(34, 197, 94, 0.05);
        border-left-color: #22c55e;
    }

    .hero-list.synergy li:before {
        content: "🤝";
    }

    .hero-map-strategies {
        display: grid;
        gap: 1rem;
    }

    .hero-map-card {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        padding: 1rem;
    }

    .hero-map-card h5 {
        font-size: 1.1rem;
        color: #ffd700;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .hero-map-card p {
        color: #cbd5e1;
        line-height: 1.6;
    }

    /* AI Disclaimer */
    .ai-disclaimer {
        margin-top: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(99, 102, 241, 0.1));
        border: 2px solid rgba(139, 92, 246, 0.4);
        border-radius: 12px;
        text-align: center;
    }

    .ai-disclaimer-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .ai-disclaimer h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #a78bfa;
        margin-bottom: 0.75rem;
    }

    .ai-disclaimer p {
        color: #cbd5e1;
        line-height: 1.8;
        font-size: 1rem;
    }

    .ai-disclaimer strong {
        color: #c4b5fd;
    }

    @media (max-width: 768px) {
        .hero-details-teams {
            grid-template-columns: 1fr;
        }

        .hero-overlay-header {
            flex-direction: column;
            text-align: center;
        }

        .hero-stats-grid {
            grid-template-columns: 1fr;
        }

        .hero-phase-ratings {
            grid-template-columns: 1fr;
        }

        .hero-overlay-content {
            margin: 1rem;
        }
    }
</style>

<div class="matchup-tool-container">
    <div class="container">
        <div class="page-header">
            <h2>Team Matchup Probability Analyzer</h2>
            <p>Select 5 heroes for each team to analyze winning probability and strategies</p>
        </div>

        <div class="teams-container">
            <!-- Team A Selection -->
            <div class="team-section team-a">
                <h3>🔵 Team A</h3>
                <div class="hero-selection">
                    <div class="selected-heroes" id="teamASelected">
                        <div class="hero-slot" data-slot="0">
                            <span class="slot-number">1</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="1">
                            <span class="slot-number">2</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="2">
                            <span class="slot-number">3</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="3">
                            <span class="slot-number">4</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="4">
                            <span class="slot-number">5</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VS Divider -->
            <div class="vs-divider">
                <span>VS</span>
            </div>

            <!-- Team B Selection -->
            <div class="team-section team-b">
                <h3>🟡 Team B</h3>
                <div class="hero-selection">
                    <div class="selected-heroes" id="teamBSelected">
                        <div class="hero-slot" data-slot="0">
                            <span class="slot-number">1</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="1">
                            <span class="slot-number">2</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="2">
                            <span class="slot-number">3</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="3">
                            <span class="slot-number">4</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                        <div class="hero-slot" data-slot="4">
                            <span class="slot-number">5</span>
                            <span class="slot-label">Select Hero</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero Picker Modal -->
        <div class="hero-picker" id="heroPicker" style="display: none;">
            <div class="hero-picker-overlay" onclick="closeHeroPicker()"></div>
            <div class="hero-picker-content">
                <div class="hero-picker-header">
                    <h3>Select Hero</h3>
                    <button class="close-btn" onclick="closeHeroPicker()">&times;</button>
                </div>

                <div class="hero-picker-search">
                    <input type="text" id="heroSearch" class="search-input" placeholder="🔍 Search heroes by name..." oninput="filterHeroes()">
                </div>
                
                <div class="hero-picker-filters">
                    <button class="filter-btn active" data-role="all">All</button>
                    @foreach($roles as $role)
                    <button class="filter-btn" data-role="{{ $role }}">{{ $role }}</button>
                    @endforeach
                </div>

                <div class="hero-picker-grid" id="heroPickerGrid">
                    @foreach($heroes as $hero)
                    <div class="hero-card" 
                         data-slug="{{ $hero['slug'] }}" 
                         data-role="{{ $hero['role'] }}" 
                         data-name="{{ strtolower($hero['name']) }}"
                         onclick="selectHero('{{ $hero['slug'] }}', '{{ $hero['name'] }}', '{{ $hero['image'] }}', '{{ $hero['role'] }}')">
                        <img src="{{ asset('modules/mlbb-tool-management/images/heroes/' . $hero['image']) }}" alt="{{ $hero['name'] }}">
                        <span class="hero-name">{{ $hero['name'] }}</span>
                        <span class="hero-role">{{ $hero['role'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Analyze Button -->
        <div class="analyze-section">
            <button id="analyzeBtn" class="btn-large" onclick="analyzeMatchup()">
                ⚡ Analyze Matchup
            </button>
            <button id="clearBtn" class="btn-large btn-clear" onclick="clearTeams()">
                🗑️ Clear Teams
            </button>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" style="display: none;"></div>
    </div>
</div>

<script>
    // State management
    const matchupState = {
        teamA: [],
        teamB: [],
        currentTeam: null,
        currentSlot: null,
        allHeroes: @json($heroes)
    };

    // Open hero picker
    function openHeroPicker(team, slot) {
        matchupState.currentTeam = team;
        matchupState.currentSlot = slot;
        document.getElementById('heroPicker').style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Reset search and filters
        document.getElementById('heroSearch').value = '';
        filterHeroes();
        updateHeroAvailability();
    }

    // Close hero picker
    function closeHeroPicker() {
        document.getElementById('heroPicker').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Filter heroes by search and role
    function filterHeroes() {
        const searchTerm = document.getElementById('heroSearch').value.toLowerCase();
        const activeRole = document.querySelector('.filter-btn.active').dataset.role;
        
        document.querySelectorAll('.hero-card').forEach(card => {
            const heroName = card.dataset.name;
            const heroRole = card.dataset.role;
            
            const matchesSearch = heroName.includes(searchTerm);
            const matchesRole = activeRole === 'all' || heroRole === activeRole;
            
            if (matchesSearch && matchesRole && !card.classList.contains('disabled')) {
                card.style.display = 'block';
            } else if (matchesSearch && matchesRole) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Update hero availability (disable already selected heroes)
    function updateHeroAvailability() {
        const selectedHeroes = [...matchupState.teamA, ...matchupState.teamB].filter(h => h);
        
        document.querySelectorAll('.hero-card').forEach(card => {
            const slug = card.dataset.slug;
            if (selectedHeroes.includes(slug)) {
                card.classList.add('disabled');
            } else {
                card.classList.remove('disabled');
            }
        });
    }

    // Select hero
    function selectHero(slug, name, image, role) {
        const team = matchupState.currentTeam;
        const slot = matchupState.currentSlot;

        // Check if hero already selected
        if (matchupState.teamA.includes(slug) || matchupState.teamB.includes(slug)) {
            return; // Disabled heroes won't trigger this anyway
        }

        // Update state
        if (team === 'a') {
            matchupState.teamA[slot] = slug;
        } else {
            matchupState.teamB[slot] = slug;
        }

        // Update UI
        const slotElement = document.querySelector(`#team${team.toUpperCase()}Selected .hero-slot[data-slot="${slot}"]`);
        slotElement.classList.add('filled');
        slotElement.innerHTML = `
            <img src="${window.location.origin}/modules/mlbb-tool-management/images/heroes/${image}" alt="${name}">
            <div class="hero-info">
                <span class="hero-name">${name}</span>
                <span class="hero-role">${role}</span>
            </div>
            <button class="remove-hero" onclick="removeHero('${team}', ${slot}); event.stopPropagation();">&times;</button>
        `;

        closeHeroPicker();
    }

    // Remove hero
    function removeHero(team, slot) {
        if (team === 'a') {
            matchupState.teamA[slot] = null;
        } else {
            matchupState.teamB[slot] = null;
        }

        const slotElement = document.querySelector(`#team${team.toUpperCase()}Selected .hero-slot[data-slot="${slot}"]`);
        slotElement.classList.remove('filled');
        slotElement.innerHTML = `
            <span class="slot-number">${slot + 1}</span>
            <span class="slot-label">Select Hero</span>
        `;
    }

    // Clear all teams
    function clearTeams() {
        if (confirm('Are you sure you want to clear both teams?')) {
            // Clear state
            matchupState.teamA = [];
            matchupState.teamB = [];

            // Clear Team A UI
            for (let i = 0; i < 5; i++) {
                const slotElement = document.querySelector(`#teamASelected .hero-slot[data-slot="${i}"]`);
                slotElement.classList.remove('filled');
                slotElement.innerHTML = `
                    <span class="slot-number">${i + 1}</span>
                    <span class="slot-label">Select Hero</span>
                `;
            }

            // Clear Team B UI
            for (let i = 0; i < 5; i++) {
                const slotElement = document.querySelector(`#teamBSelected .hero-slot[data-slot="${i}"]`);
                slotElement.classList.remove('filled');
                slotElement.innerHTML = `
                    <span class="slot-number">${i + 1}</span>
                    <span class="slot-label">Select Hero</span>
                `;
            }

            // Hide results if shown
            const resultsSection = document.getElementById('resultsSection');
            if (resultsSection) {
                resultsSection.style.display = 'none';
                resultsSection.innerHTML = '';
            }

            // Show success feedback
            const clearBtn = document.getElementById('clearBtn');
            const originalText = clearBtn.innerHTML;
            clearBtn.innerHTML = '✅ Cleared!';
            setTimeout(() => {
                clearBtn.innerHTML = originalText;
            }, 1500);
        }
    }

    // Filter heroes by role
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Apply filters
            filterHeroes();
        });
    });

    // Add click handlers to hero slots
    document.querySelectorAll('.hero-slot').forEach(slot => {
        slot.addEventListener('click', function() {
            const team = this.closest('.team-a') ? 'a' : 'b';
            const slotIndex = parseInt(this.dataset.slot);
            openHeroPicker(team, slotIndex);
        });
    });

    // Analyze matchup
    async function analyzeMatchup() {
        // Validate selection
        const teamAFilled = matchupState.teamA.filter(h => h !== null && h !== undefined).length;
        const teamBFilled = matchupState.teamB.filter(h => h !== null && h !== undefined).length;

        if (teamAFilled !== 5 || teamBFilled !== 5) {
            alert('Please select 5 heroes for each team!');
            return;
        }

        // Show loading
        const btn = document.getElementById('analyzeBtn');
        btn.disabled = true;
        btn.innerHTML = '⏳ Analyzing...';

        try {
            const payload = {
                team_a: matchupState.teamA.filter(h => h),
                team_b: matchupState.teamB.filter(h => h)
            };
            
            console.log('Sending analysis request:', payload);
            
            const response = await fetch('/mlbb/matchup/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', [...response.headers.entries()]);
            
            const responseText = await response.text();
            console.log('Response text:', responseText.substring(0, 500));
            
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Response was:', responseText);
                alert('Server error: Response is not valid JSON. Check console for details.');
                return;
            }

            if (result.success) {
                displayResults(result.data);
            } else {
                console.error('Analysis failed:', result);
                alert('Analysis failed: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred during analysis: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '⚡ Analyze Matchup';
        }
    }

    // Display results
    function displayResults(analysis) {
        const resultsSection = document.getElementById('resultsSection');
        
        const html = `
            <div class="results-container">
                <h2 class="results-title">Matchup Analysis Results</h2>
                
                ${analysis.ai_powered ? '<div class="ai-badge">✨ AI-Powered Analysis</div>' : ''}
                
                <!-- Win Probability -->
                <div class="win-probability">
                    <div class="team-prob team-a-prob">
                        <h3>Team A</h3>
                        <div class="probability-bar">
                            <div class="probability-fill" style="width: ${analysis.team_a.win_probability}%">
                                <span>${analysis.team_a.win_probability}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="team-prob team-b-prob">
                        <h3>Team B</h3>
                        <div class="probability-bar">
                            <div class="probability-fill" style="width: ${analysis.team_b.win_probability}%">
                                <span>${analysis.team_b.win_probability}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Insights Section -->
                ${analysis.ai_insights ? `
                    <div class="ai-insights-section">
                        <h3>🤖 AI Strategic Analysis</h3>
                        
                        <div class="ai-insights-card">
                            <h4>Key Matchup Insights</h4>
                            <ul class="ai-insights-list">
                                ${analysis.ai_insights.key_insights.map(insight => `<li>${insight}</li>`).join('')}
                            </ul>
                        </div>

                        <div class="ai-strategy-grid">
                            <div class="ai-strategy-card team-a-card">
                                <h4>Team A Strategy</h4>
                                <p>${analysis.ai_insights.team_a_strategy}</p>
                            </div>

                            <div class="ai-strategy-card team-b-card">
                                <h4>Team B Strategy</h4>
                                <p>${analysis.ai_insights.team_b_strategy}</p>
                            </div>
                        </div>

                        <div class="ai-phase-card">
                            <h4>Phase Advantage Analysis</h4>
                            <p>${analysis.ai_insights.phase_advantage}</p>
                        </div>
                    </div>
                ` : ''}

                <!-- Team Analysis -->
                <div class="team-analysis-grid">
                    <div class="team-analysis team-a-analysis">
                        <h3>Team A Analysis</h3>
                        
                        <div class="analysis-section">
                            <h4>Strengths</h4>
                            <ul>
                                ${analysis.team_a.analysis.strengths.map(s => `<li>${s}</li>`).join('')}
                            </ul>
                        </div>

                        <div class="analysis-section">
                            <h4>Weaknesses</h4>
                            <ul>
                                ${analysis.team_a.analysis.weaknesses.map(w => `<li>${w}</li>`).join('')}
                            </ul>
                        </div>

                        <div class="analysis-section">
                            <h4>Winning Strategies</h4>
                            ${analysis.team_a.strategy.map(s => `
                                <div class="strategy-item priority-${s.priority}">
                                    <strong>${s.phase}:</strong> ${s.strategy}
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="team-analysis team-b-analysis">
                        <h3>Team B Analysis</h3>
                        
                        <div class="analysis-section">
                            <h4>Strengths</h4>
                            <ul>
                                ${analysis.team_b.analysis.strengths.map(s => `<li>${s}</li>`).join('')}
                            </ul>
                        </div>

                        <div class="analysis-section">
                            <h4>Weaknesses</h4>
                            <ul>
                                ${analysis.team_b.analysis.weaknesses.map(w => `<li>${w}</li>`).join('')}
                            </ul>
                        </div>

                        <div class="analysis-section">
                            <h4>Winning Strategies</h4>
                            ${analysis.team_b.strategy.map(s => `
                                <div class="strategy-item priority-${s.priority}">
                                    <strong>${s.phase}:</strong> ${s.strategy}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>

                <!-- Game Phase Analysis -->
                <div class="phase-analysis">
                    <h3>Game Phase Breakdown</h3>
                    <div class="phase-grid">
                        <div class="phase-item">
                            <h4>Early Game</h4>
                            <div class="phase-comparison">
                                <div class="team-phase">Team A: ${analysis.phase_analysis.early_game.team_a}/10</div>
                                <div class="team-phase">Team B: ${analysis.phase_analysis.early_game.team_b}/10</div>
                            </div>
                            <p class="advantage">Advantage: ${analysis.phase_analysis.early_game.advantage === 'team_a' ? 'Team A' : 'Team B'}</p>
                        </div>

                        <div class="phase-item">
                            <h4>Mid Game</h4>
                            <div class="phase-comparison">
                                <div class="team-phase">Team A: ${analysis.phase_analysis.mid_game.team_a}/10</div>
                                <div class="team-phase">Team B: ${analysis.phase_analysis.mid_game.team_b}/10</div>
                            </div>
                            <p class="advantage">Advantage: ${analysis.phase_analysis.mid_game.advantage === 'team_a' ? 'Team A' : 'Team B'}</p>
                        </div>

                        <div class="phase-item">
                            <h4>Late Game</h4>
                            <div class="phase-comparison">
                                <div class="team-phase">Team A: ${analysis.phase_analysis.late_game.team_a}/10</div>
                                <div class="team-phase">Team B: ${analysis.phase_analysis.late_game.team_b}/10</div>
                            </div>
                            <p class="advantage">Advantage: ${analysis.phase_analysis.late_game.advantage === 'team_a' ? 'Team A' : 'Team B'}</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Details Section -->
                <div class="hero-details-section">
                    <h3>📊 Detailed Hero Analysis</h3>
                    <p style="text-align: center; color: #94a3b8; margin-bottom: 1.5rem;">Click on any hero to view detailed stats, strengths, weaknesses, and strategic insights</p>
                    
                    <div class="hero-details-teams">
                        <div class="hero-details-team hero-details-team-a">
                            <h4>🔵 Team A Heroes</h4>
                            <div class="hero-details-grid">
                                ${matchupState.teamA.filter(h => h).map(slug => {
                                    const hero = matchupState.allHeroes.find(h => h.slug === slug);
                                    return hero ? `
                                        <div class="hero-detail-card" onclick="showHeroDetails('${hero.slug}')">
                                            <img src="${window.location.origin}/modules/mlbb-tool-management/images/heroes/${hero.image}" alt="${hero.name}">
                                            <div class="hero-name-small">${hero.name}</div>
                                        </div>
                                    ` : '';
                                }).join('')}
                            </div>
                        </div>

                        <div class="hero-details-team hero-details-team-b">
                            <h4>🟡 Team B Heroes</h4>
                            <div class="hero-details-grid">
                                ${matchupState.teamB.filter(h => h).map(slug => {
                                    const hero = matchupState.allHeroes.find(h => h.slug === slug);
                                    return hero ? `
                                        <div class="hero-detail-card" onclick="showHeroDetails('${hero.slug}')">
                                            <img src="${window.location.origin}/modules/mlbb-tool-management/images/heroes/${hero.image}" alt="${hero.name}">
                                            <div class="hero-name-small">${hero.name}</div>
                                        </div>
                                    ` : '';
                                }).join('')}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Disclaimer -->
                <div class="ai-disclaimer">
                    <div class="ai-disclaimer-icon">🤖⚠️</div>
                    <h4>AI-Powered Analysis Disclaimer</h4>
                    <p>
                        This matchup analysis is generated using <strong>artificial intelligence</strong> and statistical calculations. 
                        While our AI provides valuable insights based on hero data, meta trends, and strategic patterns, 
                        <strong>the results are not 100% accurate</strong> and should be used as a <strong>reference tool</strong> for strategic game analysis.
                        <br><br>
                        Actual match outcomes depend on many factors including player skill, team coordination, real-time decision making, 
                        draft strategy, and in-game execution. Use this tool to enhance your understanding and planning, 
                        but remember that <strong>player performance is the ultimate determining factor</strong> in competitive play.
                    </p>
                </div>
            </div>
        `;

        resultsSection.innerHTML = html;
        resultsSection.style.display = 'block';
        resultsSection.scrollIntoView({ behavior: 'smooth' });
    }

    // Show hero details in overlay
    function showHeroDetails(slug) {
        const hero = matchupState.allHeroes.find(h => h.slug === slug);
        if (!hero) return;

        // Create overlay if it doesn't exist
        let overlay = document.getElementById('heroOverlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'heroOverlay';
            overlay.className = 'hero-overlay';
            document.body.appendChild(overlay);
        }

        // Map strategies based on hero characteristics
        const mapStrategies = [];
        if (hero.early_game >= 7) {
            mapStrategies.push({
                map: 'All Maps',
                strategy: 'Excel in early rotations and jungle invades. Secure first turtle and establish gold lead through aggressive early game pressure.'
            });
        }
        if (hero.role === 'Marksman' || hero.role === 'Mage') {
            mapStrategies.push({
                map: 'Lane Control',
                strategy: 'Focus on wave management and farming efficiency. Maintain vision control and safe positioning for sustained damage output.'
            });
        }
        if (hero.specialties.includes('Crowd Control') || hero.control >= 7) {
            mapStrategies.push({
                map: 'Objective Control',
                strategy: 'Utilize crowd control to secure Lord and Turtle objectives. Excel in team fight initiation around key map objectives.'
            });
        }
        if (hero.late_game >= 8) {
            mapStrategies.push({
                map: 'Late Game Focus',
                strategy: 'Prioritize safe farming and scaling. Avoid risky plays early, focus on reaching power spikes for dominant late game impact.'
            });
        }
        if (mapStrategies.length === 0) {
            mapStrategies.push({
                map: 'General Strategy',
                strategy: 'Adapt playstyle based on team composition and enemy threats. Balance between farming, objectives, and team fights.'
            });
        }

        // Build skills/specialties display
        const skillsHtml = hero.specialties && hero.specialties.length > 0 
            ? hero.specialties.map(spec => `<span class="hero-tag">${spec}</span>`).join('')
            : '<span class="hero-tag">Versatile</span>';

        // Build strong/weak against
        const strongAgainst = hero.strong_against && hero.strong_against.length > 0
            ? hero.strong_against.map(role => `<li>${role.charAt(0).toUpperCase() + role.slice(1)} heroes</li>`).join('')
            : '<li>Versatile against multiple roles</li>';

        const weakAgainst = hero.weak_against && hero.weak_against.length > 0
            ? hero.weak_against.map(role => `<li>${role.charAt(0).toUpperCase() + role.slice(1)} heroes</li>`).join('')
            : '<li>Requires careful positioning</li>';

        const synergyWith = hero.synergy_with && hero.synergy_with.length > 0
            ? hero.synergy_with.map(role => `<li>Teams with ${role.charAt(0).toUpperCase() + role.slice(1)} heroes</li>`).join('')
            : '<li>Flexible team compositions</li>';

        overlay.innerHTML = `
            <div class="hero-overlay-content">
                <button class="hero-overlay-close" onclick="closeHeroOverlay()">✕</button>
                
                <div class="hero-overlay-header">
                    <img src="${window.location.origin}/modules/mlbb-tool-management/images/heroes/${hero.image}" 
                         alt="${hero.name}" 
                         class="hero-overlay-image">
                    <div class="hero-overlay-title-section">
                        <h2 class="hero-overlay-title">${hero.name}</h2>
                        <div>
                            <span class="hero-overlay-role">${hero.role}</span>
                            <span class="hero-overlay-difficulty">Difficulty: ${hero.difficulty}/5</span>
                        </div>
                    </div>
                </div>

                <div class="hero-overlay-body">
                    <div class="hero-description">
                        ${hero.description || 'A versatile hero with unique abilities and playstyle.'}
                    </div>

                    <div class="hero-detail-section">
                        <h4>⚡ Specialties & Skills</h4>
                        <div class="hero-tags">
                            ${skillsHtml}
                        </div>
                    </div>

                    <div class="hero-detail-section">
                        <h4>📊 Combat Statistics</h4>
                        <div class="hero-stats-grid">
                            <div class="hero-stat-card">
                                <h5>Offense</h5>
                                <div class="hero-stat-bar">
                                    <div class="hero-stat-fill" style="width: ${(hero.offense / 10) * 100}%"></div>
                                </div>
                                <p style="color: #cbd5e1; margin-top: 0.5rem;">${hero.offense}/10</p>
                            </div>
                            <div class="hero-stat-card">
                                <h5>Durability</h5>
                                <div class="hero-stat-bar">
                                    <div class="hero-stat-fill" style="width: ${(hero.durability / 10) * 100}%"></div>
                                </div>
                                <p style="color: #cbd5e1; margin-top: 0.5rem;">${hero.durability}/10</p>
                            </div>
                            <div class="hero-stat-card">
                                <h5>Control</h5>
                                <div class="hero-stat-bar">
                                    <div class="hero-stat-fill" style="width: ${(hero.control / 10) * 100}%"></div>
                                </div>
                                <p style="color: #cbd5e1; margin-top: 0.5rem;">${hero.control}/10</p>
                            </div>
                        </div>
                    </div>

                    <div class="hero-detail-section">
                        <h4>⏱️ Game Phase Performance</h4>
                        <div class="hero-phase-ratings">
                            <div class="hero-phase-card">
                                <h5>Early Game</h5>
                                <div class="hero-phase-value">${hero.early_game}<span style="font-size: 1.2rem; color: #64748b;">/10</span></div>
                            </div>
                            <div class="hero-phase-card">
                                <h5>Mid Game</h5>
                                <div class="hero-phase-value">${hero.mid_game}<span style="font-size: 1.2rem; color: #64748b;">/10</span></div>
                            </div>
                            <div class="hero-phase-card">
                                <h5>Late Game</h5>
                                <div class="hero-phase-value">${hero.late_game}<span style="font-size: 1.2rem; color: #64748b;">/10</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="hero-detail-section">
                        <h4>💪 Strong Against</h4>
                        <ul class="hero-list">
                            ${strongAgainst}
                        </ul>
                    </div>

                    <div class="hero-detail-section">
                        <h4>⚠️ Weak Against</h4>
                        <ul class="hero-list weak">
                            ${weakAgainst}
                        </ul>
                    </div>

                    <div class="hero-detail-section">
                        <h4>🤝 Synergy With</h4>
                        <ul class="hero-list synergy">
                            ${synergyWith}
                        </ul>
                    </div>

                    <div class="hero-detail-section">
                        <h4>🗺️ Map & Strategic Gameplay</h4>
                        <div class="hero-map-strategies">
                            ${mapStrategies.map(strat => `
                                <div class="hero-map-card">
                                    <h5>📍 ${strat.map}</h5>
                                    <p>${strat.strategy}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close hero overlay
    function closeHeroOverlay() {
        const overlay = document.getElementById('heroOverlay');
        if (overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    // Close overlay when clicking outside
    document.addEventListener('click', function(e) {
        const overlay = document.getElementById('heroOverlay');
        if (overlay && e.target === overlay) {
            closeHeroOverlay();
        }
    });
</script>
@endsection
