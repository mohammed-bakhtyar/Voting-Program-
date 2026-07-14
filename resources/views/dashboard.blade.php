@extends('layouts.main')

@section('content')
<style>
    .dash-hero {
        background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.08));
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 20px;
        padding: 40px 44px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .dash-hero::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #10b981);
    }
    .dash-hero-name { font-size: 30px; font-weight: 900; color: #f8fafc; letter-spacing: -0.8px; margin-bottom: 8px; }
    .dash-hero-sub { font-size: 15px; color: #94a3b8; line-height: 1.5; }
    .dash-hero-role { color: #818cf8; font-weight: 700; }

    .dash-orb {
        position: absolute; border-radius: 50%; filter: blur(50px); pointer-events: none;
        width: 200px; height: 200px; opacity: 0.12;
    }
    .dash-orb-1 { background: #6366f1; right: -40px; top: -40px; }
    .dash-orb-2 { background: #10b981; right: 80px; bottom: -40px; }

    .dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .dash-grid { grid-template-columns: 1fr; } }

    .dash-action-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 18px;
        padding: 28px 28px;
        transition: all 0.2s;
    }
    .dash-action-card:hover { border-color: rgba(255,255,255,0.15); box-shadow: 0 8px 24px rgba(0,0,0,0.2); transform: translateY(-2px); }

    .dash-action-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 18px; font-size: 20px;
    }
    .dash-action-title { font-size: 16px; font-weight: 800; color: #f8fafc; margin-bottom: 6px; letter-spacing: -0.3px; }
    .dash-action-desc { font-size: 13px; color: #64748b; line-height: 1.55; margin-bottom: 22px; }

    .dash-action-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        font-size: 13px; font-weight: 700;
        text-decoration: none; border: none; cursor: pointer;
        transition: all 0.2s; font-family: 'Inter', sans-serif;
    }
    .dash-action-btn svg { width: 15px; height: 15px; }
    .dash-action-btn-green {
        background: rgba(16,185,129,0.12); color: #34d399;
        border: 1px solid rgba(16,185,129,0.25);
    }
    .dash-action-btn-green:hover { background: rgba(16,185,129,0.2); }
    .dash-action-btn-indigo {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white; box-shadow: 0 4px 14px rgba(99,102,241,0.3);
    }
    .dash-action-btn-indigo:hover { opacity: 0.9; transform: translateY(-1px); }
    .dash-action-btn-ghost {
        background: rgba(255,255,255,0.05); color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .dash-action-btn-ghost:hover { background: rgba(255,255,255,0.08); }
</style>

{{-- Hero --}}
<div class="dash-hero">
    <div class="dash-orb dash-orb-1"></div>
    <div class="dash-orb dash-orb-2"></div>
    <div class="dash-hero-name">Welcome back, {{ auth()->user()->name }}! 👋</div>
    <p class="dash-hero-sub">
        Logged in as <span class="dash-hero-role">{{ auth()->user()->isAdmin() ? 'System Administrator' : 'Voter' }}</span>.
        Your voice helps shape the community.
    </p>
</div>

{{-- Action Cards --}}
<div class="dash-grid">
    <div class="dash-action-card">
        <div class="dash-action-icon" style="background:rgba(16,185,129,0.12);">🗳️</div>
        <div class="dash-action-title">Explore Polls</div>
        <p class="dash-action-desc">Browse all active polls, cast your votes, and see what others are thinking right now.</p>
        <a href="{{ route('topics.index') }}" class="dash-action-btn dash-action-btn-green">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            View All Polls
        </a>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="dash-action-card">
        <div class="dash-action-icon" style="background:rgba(99,102,241,0.12);">⚙️</div>
        <div class="dash-action-title">Admin Panel</div>
        <p class="dash-action-desc">Create new topics, manage existing polls, and view detailed voting statistics and charts.</p>
        <a href="{{ route('admin.dashboard') }}" class="dash-action-btn dash-action-btn-indigo">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Go to Admin Panel
        </a>
    </div>
    @else
    <div class="dash-action-card">
        <div class="dash-action-icon" style="background:rgba(139,92,246,0.12);">⭐</div>
        <div class="dash-action-title">Your Activity</div>
        <p class="dash-action-desc">Thank you for participating! Your voice matters in shaping the community's results.</p>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0;">
            @csrf
            <button type="submit" class="dash-action-btn dash-action-btn-ghost">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout / Switch Account
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
