@extends('layouts.main')

@section('content')
<style>
    .polls-hero { margin-bottom: 32px; }
    .polls-grid { display: flex; flex-direction: column; gap: 20px; }

    /* Poll Card */
    .poll-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        padding: 28px 32px;
        transition: border-color 0.25s, box-shadow 0.25s, transform 0.2s;
        position: relative;
        overflow: hidden;
    }
    .poll-card:hover {
        border-color: rgba(99,102,241,0.35);
        box-shadow: 0 8px 32px rgba(99,102,241,0.1);
        transform: translateY(-2px);
    }
    .poll-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);
        opacity: 0;
        transition: opacity 0.25s;
    }
    .poll-card:hover::before { opacity: 1; }

    /* VIP badge glow */
    .poll-card.vip-card { border-color: rgba(251,191,36,0.25); }
    .poll-card.vip-card::before { background: linear-gradient(90deg, transparent, rgba(251,191,36,0.6), transparent); opacity: 1; }

    .poll-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 6px; }
    .poll-card-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .poll-title {
        font-size: 18px;
        font-weight: 700;
        color: #f8fafc;
        text-decoration: none;
        letter-spacing: -0.3px;
        line-height: 1.4;
        transition: color 0.2s;
    }
    .poll-title:hover { color: #818cf8; }

    .poll-meta { font-size: 12px; color: #64748b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .poll-meta-dot { width: 3px; height: 3px; background: #334155; border-radius: 50%; }

    /* Option rows */
    .option-row {
        padding: 10px 14px;
        border-radius: 10px;
        margin-bottom: 8px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        transition: background 0.2s, border-color 0.2s;
    }
    .option-row:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); }
    .option-row.my-vote {
        background: rgba(99,102,241,0.1);
        border-color: rgba(99,102,241,0.3);
    }

    .option-row-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 8px; }
    .option-row-left { display: flex; align-items: center; gap: 10px; }

    .vote-radio-btn {
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 2px solid #334155;
        background: transparent;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        padding: 0;
    }
    .vote-radio-btn:hover { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }

    .vote-radio-dot { width: 7px; height: 7px; border-radius: 50%; }
    .vote-radio-dot.selected { background: #6366f1; }
    .vote-radio-dot.static { background: #475569; }

    .option-label { font-size: 14px; font-weight: 500; color: #cbd5e1; }
    .option-label.voted { color: #818cf8; font-weight: 600; }

    .option-stat { font-size: 12px; color: #64748b; font-weight: 500; white-space: nowrap; }

    .option-bar { height: 4px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; }
    .option-bar-fill { height: 100%; border-radius: 99px; background: #6366f1; transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .option-bar-fill.neutral { background: #334155; }

    /* Before-vote row (no results shown) */
    .option-row-simple { display: flex; align-items: center; gap: 10px; }

    /* Poll footer */
    .poll-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.06); }
    .poll-footer-left { display: flex; align-items: center; gap: 12px; }

    .poll-votes-count { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 5px; }
    .poll-votes-count svg { width: 14px; height: 14px; }

    .view-details-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: rgba(99,102,241,0.1);
        color: #818cf8;
        border: 1px solid rgba(99,102,241,0.25);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .view-details-btn:hover { background: rgba(99,102,241,0.2); color: #a5b4fc; transform: translateX(2px); }
    .view-details-btn svg { width: 14px; height: 14px; }

    /* Sort control */
    .sort-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #94a3b8;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s;
    }
    .sort-select:focus { border-color: #6366f1; }
    .sort-select option { background: #1e1e2e; color: #f8fafc; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 80px 24px;
        color: #64748b;
    }
    .empty-state svg { width: 48px; height: 48px; margin: 0 auto 16px; color: #334155; display: block; }
    .empty-state h3 { font-size: 18px; font-weight: 600; color: #94a3b8; margin-bottom: 8px; }
    .empty-state p { font-size: 14px; }
</style>

<div>
    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 class="vp-section-title">Active Polls</h1>
            <p class="vp-section-sub">Cast your vote and see what the community thinks.</p>
        </div>
        <form action="{{ route('topics.index') }}" method="GET" style="margin:0;">
            <select name="sort" class="sort-select" onchange="this.form.submit()">
                <option value="latest" {{ (isset($sort) && $sort === 'latest') ? 'selected' : '' }}>⏱ Latest</option>
                <option value="popular" {{ (isset($sort) && $sort === 'popular') ? 'selected' : '' }}>🔥 Most Voted</option>
            </select>
        </form>
    </div>

    @if($topics->isEmpty())
        <div class="vp-card empty-state">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3>No polls available</h3>
            <p>Check back later — new polls are added regularly.</p>
        </div>
    @else
        <div class="polls-grid">
            @foreach($topics as $topic)
                @php
                    $userVoted = $topic->votes()->where('user_id', auth()->id())->exists();
                    $isVip = $loop->first && isset($sort) && $sort === 'popular' && $topic->total_votes > 0;
                @endphp

                <div class="poll-card {{ $isVip ? 'vip-card' : '' }}">
                    {{-- Card Header --}}
                    <div class="poll-card-header">
                        <h2 style="flex:1;">
                            <a href="{{ route('topics.show', $topic) }}" class="poll-title">{{ $topic->title }}</a>
                        </h2>
                        <div class="poll-card-badges">
                            @if($isVip)
                                <span class="vp-badge vp-badge-vip">👑 Most Popular</span>
                            @endif
                            @if($topic->is_closed)
                                <span class="vp-badge vp-badge-danger">
                                    <svg width="7" height="7" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                                    Closed
                                </span>
                            @else
                                <span class="vp-badge vp-badge-success">
                                    <span class="vp-pulse-dot"></span>
                                    Live
                                </span>
                            @endif
                            @if($userVoted)
                                <span class="vp-badge" style="background:rgba(99,102,241,0.12);color:#818cf8;border:1px solid rgba(99,102,241,0.25);">
                                    ✓ Voted
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Meta --}}
                    <div class="poll-meta">
                        <span>by {{ $topic->creator->name }}</span>
                        <span class="poll-meta-dot"></span>
                        <span>{{ $topic->created_at->diffForHumans() }}</span>
                        <span class="poll-meta-dot"></span>
                        @if($topic->is_closed)
                            <span style="color:#f87171;">Poll ended</span>
                        @else
                            <span style="color:#10b981;">Closes {{ now()->diffForHumans($topic->closes_at, true) }}</span>
                        @endif
                    </div>

                    {{-- Options --}}
                    <div style="margin-bottom:4px;">
                        @foreach($topic->options as $option)
                            @php
                                $votedForThis = $userVoted && $topic->votes()->where('user_id', auth()->id())->where('option_id', $option->id)->exists();
                                $percentage = $topic->total_votes > 0 ? round(($option->votes_count / $topic->total_votes) * 100) : 0;
                            @endphp

                            @if($userVoted || $topic->show_results_before_voting)
                                <div class="option-row {{ $votedForThis ? 'my-vote' : '' }}">
                                    <div class="option-row-top">
                                        <div class="option-row-left">
                                            @if(!$userVoted && !$topic->is_closed)
                                                <form action="{{ route('votes.store', $topic) }}" method="POST" style="display:inline;margin:0;">
                                                    @csrf
                                                    <input type="hidden" name="option_id" value="{{ $option->id }}">
                                                    <button type="submit" class="vote-radio-btn" title="Vote for {{ $option->label }}">
                                                        <div class="vote-radio-dot static"></div>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="vote-radio-btn" style="cursor:default; {{ $votedForThis ? 'border-color:#6366f1;background:rgba(99,102,241,0.1);' : '' }}">
                                                    <div class="vote-radio-dot {{ $votedForThis ? 'selected' : 'static' }}"></div>
                                                </div>
                                            @endif
                                            <span class="option-label {{ $votedForThis ? 'voted' : '' }}">{{ $option->label }}</span>
                                        </div>
                                        <span class="option-stat">{{ $percentage }}% &nbsp;·&nbsp; {{ $option->votes_count }}</span>
                                    </div>
                                    <div class="option-bar">
                                        <div class="option-bar-fill {{ $votedForThis ? '' : 'neutral' }}" data-width="{{ $percentage }}%" style="width:{{ $percentage }}%;"></div>
                                    </div>
                                </div>
                            @else
                                <div class="option-row">
                                    <div class="option-row-simple">
                                        @if(!$userVoted && !$topic->is_closed)
                                            <form action="{{ route('votes.store', $topic) }}" method="POST" style="display:inline;margin:0;">
                                                @csrf
                                                <input type="hidden" name="option_id" value="{{ $option->id }}">
                                                <button type="submit" class="vote-radio-btn" title="Vote for {{ $option->label }}">
                                                    <div class="vote-radio-dot static"></div>
                                                </button>
                                            </form>
                                        @else
                                            <div class="vote-radio-btn" style="cursor:default;">
                                                <div class="vote-radio-dot static"></div>
                                            </div>
                                        @endif
                                        <span class="option-label">{{ $option->label }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Poll Footer --}}
                    <div class="poll-footer">
                        <div class="poll-footer-left">
                            <span class="poll-votes-count">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ number_format($topic->total_votes) }} votes
                            </span>
                        </div>
                        <a href="{{ route('topics.show', $topic) }}" class="view-details-btn">
                            View Details
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
