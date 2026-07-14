@extends('layouts.main')

@section('content')
<style>
    .admin-dash-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
    @media (max-width: 768px) { .admin-dash-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { border-color: rgba(255,255,255,0.15); box-shadow: 0 4px 20px rgba(0,0,0,0.2); }

    .stat-card-accent { position: absolute; top: 0; left: 0; width: 3px; height: 100%; border-radius: 0 0 0 0; }
    .stat-card-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
    }
    .stat-card-icon svg { width: 20px; height: 20px; }
    .stat-card-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 8px; }
    .stat-card-value { font-size: 32px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
    .stat-card-sub { font-size: 12px; color: #475569; margin-top: 8px; }

    .admin-content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 900px) { .admin-content-grid { grid-template-columns: 1fr; } }

    .admin-panel {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 24px;
    }
    .admin-panel-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 20px; }

    /* Leaderboard */
    .leaderboard-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .leaderboard-row:last-child { border-bottom: none; }
    .leaderboard-rank { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0; }
    .rank-1 { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .rank-2 { background: rgba(148,163,184,0.12); color: #94a3b8; }
    .rank-3 { background: rgba(180,83,9,0.15); color: #d97706; }
    .rank-other { background: rgba(255,255,255,0.05); color: #475569; }
    .leaderboard-info { flex: 1; min-width: 0; }
    .leaderboard-name { font-size: 13px; font-weight: 600; color: #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .leaderboard-bar { height: 3px; background: rgba(255,255,255,0.06); border-radius: 99px; margin-top: 5px; }
    .leaderboard-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #6366f1, #8b5cf6); transition: width 1s cubic-bezier(0.34,1.56,0.64,1); }
    .leaderboard-votes { font-size: 12px; font-weight: 600; color: #64748b; white-space: nowrap; }

    /* Chart container */
    .chart-container { position: relative; height: 200px; }

    /* Create button */
    .admin-create-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white; font-size: 13px; font-weight: 700;
        border-radius: 10px; text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .admin-create-btn:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }
    .admin-create-btn svg { width: 16px; height: 16px; }
</style>

<div>
    {{-- Header --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('dashboard') }}" class="vp-btn vp-btn-ghost" style="display:inline-flex; border: none; padding-left: 0; opacity: 0.8;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:16px; height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to User Dashboard
        </a>
    </div>
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 class="vp-section-title">Admin Dashboard</h1>
            <p class="vp-section-sub">Platform overview and voting statistics.</p>
        </div>
        <a href="{{ route('admin.topics.create') }}" class="admin-create-btn">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Create New Poll
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="admin-dash-grid">
        <div class="stat-card">
            <div class="stat-card-accent" style="background:#6366f1;"></div>
            <div class="stat-card-icon" style="background:rgba(99,102,241,0.12);">
                <svg fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div class="stat-card-label">Total Polls</div>
            <div class="stat-card-value" style="color:#818cf8;">{{ $stats['total_polls'] }}</div>
            <div class="stat-card-sub">
                <span style="color:#10b981;">{{ $stats['active_polls'] }} active</span>
                &nbsp;·&nbsp;
                <span>{{ $stats['closed_polls'] }} closed</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-accent" style="background:#10b981;"></div>
            <div class="stat-card-icon" style="background:rgba(16,185,129,0.12);">
                <svg fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="stat-card-label">Total Votes</div>
            <div class="stat-card-value" style="color:#34d399;">{{ number_format($stats['total_votes']) }}</div>
            <div class="stat-card-sub">Across all polls</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-accent" style="background:#8b5cf6;"></div>
            <div class="stat-card-icon" style="background:rgba(139,92,246,0.12);">
                <svg fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="stat-card-label">Total Users</div>
            <div class="stat-card-value" style="color:#a78bfa;">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-card-sub">Avg {{ round($stats['total_polls'] > 0 ? $stats['total_votes'] / $stats['total_polls'] : 0, 1) }} votes/poll</div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="admin-content-grid">
        {{-- Leaderboard --}}
        <div class="admin-panel">
            <p class="admin-panel-title">🔥 Most Active Polls</p>
            @forelse($stats['most_active_topics'] as $index => $topic)
                @php $maxVotes = $stats['most_active_topics']->max('total_votes') ?: 1; @endphp
                <div class="leaderboard-row">
                    <div class="leaderboard-rank {{ $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : 'rank-other')) }}">
                        {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : ($index === 2 ? '🥉' : $index + 1)) }}
                    </div>
                    <div class="leaderboard-info">
                        <div class="leaderboard-name">{{ Str::limit($topic->title, 35) }}</div>
                        <div class="leaderboard-bar">
                            <div class="leaderboard-bar-fill" data-width="{{ ($topic->total_votes / $maxVotes) * 100 }}%" style="width:{{ ($topic->total_votes / $maxVotes) * 100 }}%;"></div>
                        </div>
                    </div>
                    <div class="leaderboard-votes">{{ $topic->total_votes }}</div>
                </div>
            @empty
                <p style="color:#475569; font-size:13px;">No polls yet.</p>
            @endforelse
        </div>

        {{-- Trend Chart --}}
        <div class="admin-panel">
            <p class="admin-panel-title">📈 Voting Trend — Last 7 Days</p>
            <div class="chart-container">
                <canvas id="votingTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('votingTrendChart').getContext('2d');
        const dates = {!! json_encode($stats['trend_dates']) !!};
        const votes = {!! json_encode($stats['trend_votes']) !!};
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Votes',
                    data: votes,
                    borderColor: '#6366f1',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx: c, chartArea} = chart;
                        if (!chartArea) return 'rgba(99,102,241,0.05)';
                        const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(99,102,241,0.25)');
                        gradient.addColorStop(1, 'rgba(99,102,241,0.01)');
                        return gradient;
                    },
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#475569', font: { family: 'Inter', size: 11 } },
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        border: { color: 'transparent' }
                    },
                    x: {
                        ticks: { color: '#475569', font: { family: 'Inter', size: 11 } },
                        grid: { display: false },
                        border: { color: 'transparent' }
                    }
                }
            }
        });
    });
</script>
@endsection
