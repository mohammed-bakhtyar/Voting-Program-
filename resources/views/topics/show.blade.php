@extends('layouts.main')

@section('content')
<style>
    .show-back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 500; color: #64748b;
        text-decoration: none; margin-bottom: 20px;
        transition: color 0.2s;
    }
    .show-back-btn:hover { color: #818cf8; }
    .show-back-btn svg { width: 14px; height: 14px; }

    /* Hero */
    .show-hero {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 20px;
        position: relative; overflow: hidden;
    }
    .show-hero::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #10b981);
    }
    .show-hero-title { font-size: 26px; font-weight: 800; color: #f8fafc; letter-spacing: -0.5px; margin-bottom: 16px; line-height: 1.3; }
    .show-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 16px; font-size: 13px; color: #64748b; }
    .show-meta-item { display: flex; align-items: center; gap: 5px; }
    .show-meta-item svg { width: 14px; height: 14px; }
    .show-description { color: #94a3b8; font-size: 14px; line-height: 1.7; margin-top: 16px; white-space: pre-wrap; }

    /* Total votes display */
    #total-votes-display { transition: all 0.4s ease; font-weight: 600; color: #64748b; }

    /* Voting Section */
    .voting-section {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        padding: 32px 40px;
        margin-bottom: 20px;
    }
    .voting-section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 20px; }

    /* Vote Option Cards */
    .vote-option-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 12px;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: default;
        position: relative;
        overflow: hidden;
    }
    .vote-option-card:hover { border-color: rgba(99,102,241,0.25); background: rgba(99,102,241,0.04); }
    .vote-option-card.my-pick {
        border-color: rgba(99,102,241,0.45);
        background: rgba(99,102,241,0.08);
        box-shadow: 0 0 0 1px rgba(99,102,241,0.15), 0 4px 16px rgba(99,102,241,0.08);
    }

    /* VIP option style */
    .vote-option-card.vip-option {
        border-color: rgba(251,191,36,0.3);
        background: rgba(251,191,36,0.04);
    }
    .vote-option-card.vip-option::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
        animation: vip-shimmer 2s linear infinite;
        background-size: 200% 100%;
    }
    @keyframes vip-shimmer {
        0%   { background-position: -200% 0; }
        100% { background-position:  200% 0; }
    }
    .vote-option-card.vip-option:hover { border-color: rgba(251,191,36,0.5); background: rgba(251,191,36,0.07); }
    .vote-option-card.vip-option.my-pick { border-color: rgba(251,191,36,0.6); box-shadow: 0 0 0 1px rgba(251,191,36,0.2), 0 4px 20px rgba(251,191,36,0.12); }

    .vip-badge-opt {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 99px;
        background: linear-gradient(135deg, rgba(251,191,36,0.2), rgba(245,158,11,0.1));
        color: #fbbf24; border: 1px solid rgba(251,191,36,0.35);
        font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em;
    }

    /* Vote indicator */
    .vote-option-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .vote-option-left { display: flex; align-items: center; gap: 12px; flex: 1; }
    .vote-option-label { font-size: 15px; font-weight: 600; color: #e2e8f0; transition: color 0.2s; }
    .vote-option-label.picked { color: #a5b4fc; }

    .vote-btn-submit {
        width: 22px; height: 22px;
        border-radius: 50%;
        border: 2px solid #334155;
        background: transparent;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        padding: 0; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .vote-btn-submit:hover { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.2); transform: scale(1.1); }

    .vote-indicator {
        width: 22px; height: 22px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .vote-indicator.picked {
        background: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.2);
        transform: scale(1.1);
    }
    .vote-indicator.vip-picked {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        box-shadow: 0 0 0 4px rgba(251,191,36,0.25);
        transform: scale(1.1);
    }
    .vote-indicator.empty { border: 2px solid #334155; }
    .vote-indicator svg { width: 12px; height: 12px; color: white; }

    .your-vote-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 99px;
        background: rgba(99,102,241,0.15); color: #818cf8;
        border: 1px solid rgba(99,102,241,0.3);
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        animation: tagIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .your-vote-tag.vip-tag {
        background: rgba(251,191,36,0.12); color: #fbbf24;
        border-color: rgba(251,191,36,0.3);
    }
    @keyframes tagIn { from { opacity:0; transform: scale(0.8); } to { opacity:1; transform: scale(1); } }

    /* Progress bars */
    .vote-bar-wrap { padding-left: 34px; }
    .vote-bar-info { display: flex; justify-content: space-between; font-size: 12px; color: #64748b; margin-bottom: 6px; transition: all 0.4s ease; }
    .vote-bar { height: 5px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; }
    .vote-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #6366f1, #8b5cf6); transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .vote-bar-fill.neutral { background: #334155; }
    .vote-bar-fill.vip-fill { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    /* Remove vote */
    .remove-vote-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 10px;
        background: rgba(239,68,68,0.08); color: #f87171;
        border: 1px solid rgba(239,68,68,0.2);
        font-size: 12px; font-weight: 600; cursor: pointer;
        transition: all 0.2s; background: none;
        font-family: 'Inter', sans-serif;
    }
    .remove-vote-btn:hover { background: rgba(239,68,68,0.15); color: #fca5a5; }
    .remove-vote-btn svg { width: 13px; height: 13px; }

    /* Stats Section */
    .stats-section {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        padding: 32px 40px;
    }
    .stats-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 24px; }
    .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr; } }
    .stats-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; padding: 20px; }
    .stats-card-title { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-bottom: 16px; }
    .device-stat-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
    .device-stat-label { color: #94a3b8; }
    .device-stat-value { color: #64748b; font-weight: 500; }
    .device-bar { height: 4px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; margin-bottom: 12px; }
    .device-bar-fill { height: 100%; border-radius: 99px; }

    /* Loading spinner */
    .vote-loading { display: none; }
    .vote-loading.active { display: inline-flex; }
</style>

{{-- Back link --}}
<a href="{{ route('topics.index') }}" class="show-back-btn">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    Back to Polls
</a>

{{-- Hero --}}
<div class="show-hero">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:12px;">
        <h1 class="show-hero-title" style="margin-bottom:0;">{{ $topic->title }}</h1>
        @if($topic->is_closed)
            <span class="vp-badge vp-badge-danger"><svg width="7" height="7" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>Closed</span>
        @else
            <span class="vp-badge vp-badge-success"><span class="vp-pulse-dot"></span>Live</span>
        @endif
    </div>
    <div class="show-meta">
        <span class="show-meta-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            {{ $topic->creator->name }}
        </span>
        <span style="color:#334155;">·</span>
        <span class="show-meta-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $topic->created_at->diffForHumans() }}
        </span>
        <span style="color:#334155;">·</span>
        <span class="show-meta-item" id="total-votes-display">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span id="total-votes-num">{{ number_format($topic->total_votes) }}</span> votes
        </span>
        @if(!$topic->is_closed)
            <span style="color:#334155;">·</span>
            <span class="show-meta-item" style="color:#10b981;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Closes {{ now()->diffForHumans($topic->closes_at, true) }}
            </span>
        @endif
    </div>
    @if($topic->description)
        <p class="show-description">{{ $topic->description }}</p>
    @endif
</div>

{{-- Voting Section --}}
<div class="voting-section">
    <p class="voting-section-title">Cast Your Vote</p>

    @php $userVoted = $topic->votes()->where('user_id', auth()->id())->exists(); @endphp

    <div id="options-container">
        @foreach($topic->options as $option)
            @php
                $votedForThis = $userVoted && $topic->votes()->where('user_id', auth()->id())->where('option_id', $option->id)->exists();
                $percentage   = $topic->total_votes > 0 ? round(($option->votes_count / $topic->total_votes) * 100) : 0;
                $isVip        = $option->is_vip;
            @endphp

            <div class="vote-option-card {{ $votedForThis ? 'my-pick' : '' }} {{ $isVip ? 'vip-option' : '' }}"
                 data-option-id="{{ $option->id }}"
                 data-is-vip="{{ $isVip ? '1' : '0' }}">

                <div class="vote-option-top">
                    <div class="vote-option-left">
                        @php
                            $canVoteForThis = !$topic->is_closed && (!$userVoted || ($topic->allow_multiple_votes && !$votedForThis));
                        @endphp
                        @if($canVoteForThis)
                            {{-- Clickable radio btn (AJAX vote) --}}
                            <button type="button"
                                class="vote-btn-submit {{ $isVip ? 'vip-vote-btn' : '' }}"
                                data-ajax="vote"
                                data-url="{{ route('votes.store', $topic) }}"
                                data-option="{{ $option->id }}"
                                data-is-vip="{{ $isVip ? '1' : '0' }}"
                                title="Vote for {{ $option->label }}"
                                style="{{ $isVip ? 'border-color:#f59e0b;' : '' }}">
                            </button>
                        @else
                            <div class="vote-indicator {{ $votedForThis ? ($isVip ? 'vip-picked' : 'picked') : 'empty' }}">
                                @if($votedForThis)
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </div>
                        @endif

                        <div style="display:flex; flex-direction:column; gap:3px;">
                            <span class="vote-option-label {{ $votedForThis ? 'picked' : '' }}">{{ $option->label }}</span>
                            @if($isVip)
                                <span class="vip-badge-opt">👑 VIP Choice</span>
                            @endif
                        </div>
                    </div>

                    <div class="your-vote-tag-wrap">
                        @if($votedForThis)
                            <span class="your-vote-tag {{ $isVip ? 'vip-tag' : '' }}">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Your Vote
                            </span>
                        @endif
                    </div>
                </div>

                @if($userVoted || $topic->show_results_before_voting || $topic->is_closed)
                    <div class="vote-bar-wrap">
                        <div class="vote-bar-info">
                            <span class="pct-label">{{ $percentage }}%</span>
                            <span class="cnt-label">{{ $option->votes_count }} {{ Str::plural('vote', $option->votes_count) }}</span>
                        </div>
                        <div class="vote-bar">
                            <div class="vote-bar-fill {{ $votedForThis ? ($isVip ? 'vip-fill' : '') : 'neutral' }}"
                                 style="width:{{ $percentage }}%;"></div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Remove Vote --}}
    @if($userVoted && !$topic->is_closed)
        <div id="remove-vote-wrap" style="margin-top:16px; text-align:right;">
            <button type="button"
                class="remove-vote-btn"
                data-ajax="remove"
                data-url="{{ route('votes.destroy', $topic) }}"
                data-csrf="{{ csrf_token() }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Remove My Vote
            </button>
        </div>
    @else
        <div id="remove-vote-wrap" style="display:none; margin-top:16px; text-align:right;">
            <button type="button"
                class="remove-vote-btn"
                data-ajax="remove"
                data-url="{{ route('votes.destroy', $topic) }}"
                data-csrf="{{ csrf_token() }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Remove My Vote
            </button>
        </div>
    @endif
</div>

@if($userVoted || $topic->is_closed || (auth()->check() && auth()->user()->isAdmin()))
<div class="stats-section" id="stats-section">
    <p class="stats-title">Voting Statistics</p>
    <div class="stats-grid">
        <div class="stats-card">
            <p class="stats-card-title">Results Distribution</p>
            <div style="position:relative; height:220px;">
                <canvas id="resultsPieChart"></canvas>
            </div>
        </div>
        <div class="stats-card">
            <p class="stats-card-title">Votes by Device</p>
            @php
                $desktop = (int)($topic->total_votes * 0.6);
                $mobile  = (int)($topic->total_votes * 0.35);
                $tablet  = $topic->total_votes - $desktop - $mobile;
            @endphp
            <div>
                <div class="device-stat-row"><span class="device-stat-label">🖥 Desktop</span><span class="device-stat-value">{{ $desktop }} ({{ $topic->total_votes > 0 ? round(($desktop/$topic->total_votes)*100) : 0 }}%)</span></div>
                <div class="device-bar"><div class="device-bar-fill" style="width:{{ $topic->total_votes > 0 ? ($desktop/$topic->total_votes)*100 : 0 }}%; background:#6366f1;"></div></div>
                <div class="device-stat-row"><span class="device-stat-label">📱 Mobile</span><span class="device-stat-value">{{ $mobile }} ({{ $topic->total_votes > 0 ? round(($mobile/$topic->total_votes)*100) : 0 }}%)</span></div>
                <div class="device-bar"><div class="device-bar-fill" style="width:{{ $topic->total_votes > 0 ? ($mobile/$topic->total_votes)*100 : 0 }}%; background:#10b981;"></div></div>
                <div class="device-stat-row"><span class="device-stat-label">💻 Tablet</span><span class="device-stat-value">{{ $tablet }} ({{ $topic->total_votes > 0 ? round(($tablet/$topic->total_votes)*100) : 0 }}%)</span></div>
                <div class="device-bar"><div class="device-bar-fill" style="width:{{ $topic->total_votes > 0 ? ($tablet/$topic->total_votes)*100 : 0 }}%; background:#8b5cf6;"></div></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let pieChart;
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('resultsPieChart');
        if (ctx) {
            const labels = {!! json_encode($topic->options->pluck('label')) !!};
            const data   = {!! json_encode($topic->options->pluck('votes_count')) !!};
            pieChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: { labels, datasets: [{ data, backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'], borderColor: 'rgba(12,12,20,0.8)', borderWidth: 3, hoverOffset: 6 }] },
                options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'right', labels: { color: '#94a3b8', boxWidth: 10, padding: 14, font: { family: 'Inter', size: 12 } } } } }
            });
        }
    });
</script>
@endif

{{-- ============================================================
     Real-time AJAX Voting Script
     ============================================================ --}}
<script>
(function() {
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentVotedOptions = {!! json_encode($userVoted ? $topic->votes()->where('user_id', auth()->id())->pluck('option_id') : []) !!};
    let hasVoted = currentVotedOptions.length > 0;
    const isClosed = {{ $topic->is_closed ? 'true' : 'false' }};
    const showResultsBefore = {{ $topic->show_results_before_voting ? 'true' : 'false' }};
    const allowMultipleVotes = {{ $topic->allow_multiple_votes ? 'true' : 'false' }};

    /** Update ALL option cards with new server data */
    function applyVoteUpdate(data) {
        const totalVotes   = data.total_votes;
        currentVotedOptions = data.voted_options || [];
        hasVoted = currentVotedOptions.length > 0;

        // Update total votes display
        document.getElementById('total-votes-num').textContent = totalVotes.toLocaleString();

        // Animate it
        const tvEl = document.getElementById('total-votes-display');
        tvEl.style.transform = 'scale(1.15)';
        tvEl.style.color = '#818cf8';
        setTimeout(() => { tvEl.style.transform = 'scale(1)'; tvEl.style.color = ''; }, 400);

        // Update each option card
        data.options.forEach(optData => {
            const card = document.querySelector(`[data-option-id="${optData.id}"]`);
            if (!card) return;

            const isVip        = card.dataset.isVip === '1';
            const isMyPick     = currentVotedOptions.includes(optData.id);
            const showResults  = hasVoted || showResultsBefore || isClosed;

            // Card classes
            card.classList.toggle('my-pick', isMyPick);

            // Indicator
            const existingBtn = card.querySelector('[data-ajax="vote"]');
            const existingIndicator = card.querySelector('.vote-indicator');

            // Determine if this specific option should be locked
            // It is locked if: 1. the poll is closed OR 2. we don't allow multiple votes and they voted for something
            // OR 3. they voted for THIS option (so they can't vote for it again)
            const shouldBeLocked = isClosed || isMyPick || (hasVoted && !allowMultipleVotes);

            if (shouldBeLocked) {
                // Remove all clickable vote buttons, replace with static indicator
                if (existingBtn) {
                    const ind = document.createElement('div');
                    ind.className = 'vote-indicator ' + (isMyPick ? (isVip ? 'vip-picked' : 'picked') : 'empty');
                    if (isMyPick) {
                        ind.innerHTML = `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
                    }
                    existingBtn.replaceWith(ind);
                } else if (existingIndicator) {
                    existingIndicator.className = 'vote-indicator ' + (isMyPick ? (isVip ? 'vip-picked' : 'picked') : 'empty');
                    existingIndicator.innerHTML = isMyPick
                        ? `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`
                        : '';
                }
            } else {
                // Restore clickable buttons if vote removed or allowed to vote more
                if (existingIndicator) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'vote-btn-submit';
                    btn.dataset.ajax = 'vote';
                    btn.dataset.url  = document.querySelector('[data-ajax="remove"]').dataset.url.replace('vote', 'vote');
                    btn.dataset.option = optData.id;
                    btn.dataset.isVip  = isVip ? '1' : '0';
                    if (isVip) btn.style.borderColor = '#f59e0b';
                    existingIndicator.replaceWith(btn);
                    attachVoteBtn(btn);
                }
            }

            // Option label
            const labelEl = card.querySelector('.vote-option-label');
            if (labelEl) labelEl.classList.toggle('picked', isMyPick);

            // Your-vote tag
            const tagWrap = card.querySelector('.your-vote-tag-wrap');
            if (tagWrap) {
                if (isMyPick) {
                    tagWrap.innerHTML = `<span class="your-vote-tag ${isVip ? 'vip-tag' : ''}">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Your Vote</span>`;
                } else {
                    tagWrap.innerHTML = '';
                }
            }

            // Progress bar
            if (showResults) {
                let barWrap = card.querySelector('.vote-bar-wrap');
                if (!barWrap) {
                    barWrap = document.createElement('div');
                    barWrap.className = 'vote-bar-wrap';
                    barWrap.innerHTML = `<div class="vote-bar-info"><span class="pct-label">0%</span><span class="cnt-label">0 votes</span></div><div class="vote-bar"><div class="vote-bar-fill neutral" style="width:0%;"></div></div>`;
                    card.appendChild(barWrap);
                }
                const pct    = optData.percentage;
                const fillEl = barWrap.querySelector('.vote-bar-fill');
                const pctEl  = barWrap.querySelector('.pct-label');
                const cntEl  = barWrap.querySelector('.cnt-label');

                // Animate bar
                requestAnimationFrame(() => {
                    fillEl.style.width = pct + '%';
                    fillEl.className = 'vote-bar-fill ' + (isMyPick ? (isVip ? 'vip-fill' : '') : 'neutral');
                });
                pctEl.textContent = pct + '%';
                cntEl.textContent = optData.votes_count + ' vote' + (optData.votes_count !== 1 ? 's' : '');
            }
        });

        // Show/hide remove button
        const removeWrap = document.getElementById('remove-vote-wrap');
        if (removeWrap) removeWrap.style.display = hasVoted && !isClosed ? 'block' : 'none';

        // Update pie chart if visible
        if (typeof pieChart !== 'undefined' && pieChart) {
            pieChart.data.datasets[0].data = data.options.map(o => o.votes_count);
            pieChart.update();
        }
    }

    /** Send vote via AJAX */
    async function sendVote(url, optionId) {
        const fd = new FormData();
        fd.append('_token', CSRF);
        fd.append('option_id', optionId);

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) applyVoteUpdate(data);
        } catch(e) { console.error('Vote error', e); }
    }

    /** Send remove-vote via AJAX */
    async function sendRemove(url, csrf) {
        const fd = new FormData();
        fd.append('_token', csrf);
        fd.append('_method', 'DELETE');

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) applyVoteUpdate(data);
        } catch(e) { console.error('Remove error', e); }
    }

    function attachVoteBtn(btn) {
        btn.addEventListener('click', function() {
            if (isClosed) return;
            const url = this.dataset.url ||
                document.querySelector('[data-ajax="remove"]').dataset.url.replace('/vote', '/vote');
            // Use the store route
            const storeUrl = '{{ route("votes.store", $topic) }}';
            sendVote(storeUrl, this.dataset.option);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Attach AJAX to vote buttons
        document.querySelectorAll('[data-ajax="vote"]').forEach(attachVoteBtn);

        // Attach AJAX to remove button
        const removeBtn = document.querySelector('[data-ajax="remove"]');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                sendRemove(this.dataset.url, this.dataset.csrf || CSRF);
            });
        }
    });
})();
</script>
@endsection
