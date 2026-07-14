@extends('layouts.main')

@section('content')
<style>
    .admin-show-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        overflow: hidden;
        max-width: 860px;
        margin: 0 auto;
    }
    .admin-show-hero {
        padding: 32px 36px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        background: rgba(99,102,241,0.04);
        position: relative;
    }
    .admin-show-hero::before {
        content:''; position:absolute; top:0; left:0; right:0; height:2px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #10b981);
    }
    .admin-show-title { font-size: 22px; font-weight: 800; color: #f8fafc; margin-bottom: 16px; letter-spacing: -0.4px; }

    .admin-show-meta-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;
    }
    @media (max-width: 600px) { .admin-show-meta-grid { grid-template-columns: 1fr; } }

    .admin-show-meta-chip {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px; padding: 12px 16px;
    }
    .admin-show-meta-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #475569; margin-bottom: 4px; }
    .admin-show-meta-value { font-size: 14px; font-weight: 600; color: #cbd5e1; }

    .admin-show-body { padding: 32px 36px; }
    .admin-show-section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 20px; }

    .result-row { margin-bottom: 16px; }
    .result-row-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; }
    .result-label { font-size: 14px; font-weight: 600; color: #e2e8f0; }
    .result-stat { font-size: 12px; font-weight: 600; color: #64748b; }
    .result-bar { height: 6px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; }
    .result-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #6366f1, #8b5cf6); transition: width 1s cubic-bezier(0.34,1.56,0.64,1); }

    .admin-show-desc { color: #94a3b8; font-size: 14px; line-height: 1.7; white-space: pre-wrap; margin-bottom: 24px; }
</style>

<div style="max-width:860px; margin:0 auto;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <a href="{{ route('admin.topics.index') }}" class="show-back-btn" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:500; color:#64748b; text-decoration:none; transition:color 0.2s;">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Topics
        </a>
    </div>

    <div class="admin-show-card">
        {{-- Hero --}}
        <div class="admin-show-hero">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:20px;">
                <h1 class="admin-show-title" style="margin-bottom:0;">{{ $topic->title }}</h1>
                @if($topic->closed_at || now()->isAfter($topic->closes_at))
                    <span class="vp-badge vp-badge-danger">Closed</span>
                @elseif(now()->isBefore($topic->opens_at))
                    <span class="vp-badge vp-badge-warning">Upcoming</span>
                @else
                    <span class="vp-badge vp-badge-success"><span class="vp-pulse-dot"></span>Open</span>
                @endif
            </div>

            <div class="admin-show-meta-grid">
                <div class="admin-show-meta-chip">
                    <div class="admin-show-meta-label">Opens At</div>
                    <div class="admin-show-meta-value">{{ $topic->opens_at->format('M d, Y · H:i') }}</div>
                </div>
                <div class="admin-show-meta-chip">
                    <div class="admin-show-meta-label">Closes At</div>
                    <div class="admin-show-meta-value">{{ $topic->closes_at->format('M d, Y · H:i') }}</div>
                </div>
                <div class="admin-show-meta-chip">
                    <div class="admin-show-meta-label">Total Votes</div>
                    <div class="admin-show-meta-value" style="color:#818cf8;">{{ $topic->votes_count ?? 0 }}</div>
                </div>
                <div class="admin-show-meta-chip">
                    <div class="admin-show-meta-label">Options</div>
                    <div class="admin-show-meta-value">{{ $topic->options->count() }}</div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="admin-show-body">
            @if($topic->description)
                <p class="admin-show-desc">{{ $topic->description }}</p>
            @endif

            <p class="admin-show-section-title">Results Breakdown</p>
            @foreach($topic->options as $option)
                @php
                    $percentage = ($topic->votes_count ?? 0) > 0 ? round(($option->votes_count / $topic->votes_count) * 100) : 0;
                @endphp
                <div class="result-row">
                    <div class="result-row-top">
                        <span class="result-label">{{ $option->label }}</span>
                        <span class="result-stat">{{ $option->votes_count }} votes · {{ $percentage }}%</span>
                    </div>
                    <div class="result-bar">
                        <div class="result-bar-fill" data-width="{{ $percentage }}%" style="width:{{ $percentage }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
