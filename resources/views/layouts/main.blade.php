<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VotePulse — {{ config('app.name', 'Voting Platform') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg: #0c0c14;
            --bg-card: rgba(255,255,255,0.04);
            --bg-card-hover: rgba(255,255,255,0.07);
            --border: rgba(255,255,255,0.08);
            --border-hover: rgba(99,102,241,0.4);
            --primary: #6366f1;
            --primary-hover: #818cf8;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text: #f8fafc;
            --text-muted: #64748b;
            --text-secondary: #94a3b8;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ---- Scrollbar ---- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0c0c14; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.4); border-radius: 3px; }

        /* ---- Navbar ---- */
        .vp-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(12,12,20,0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }
        .vp-nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .vp-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .vp-logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(99,102,241,0.35);
        }
        .vp-logo-icon svg { width: 18px; height: 18px; color: white; }
        .vp-logo-text { font-size: 18px; font-weight: 800; color: var(--text); letter-spacing: -0.5px; }
        .vp-logo-text span { color: var(--primary); }

        .vp-nav-actions { display: flex; align-items: center; gap: 8px; }

        .vp-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .vp-btn svg { width: 15px; height: 15px; }

        .vp-btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .vp-btn-ghost:hover { background: var(--bg-card); color: var(--text); border-color: rgba(255,255,255,0.15); }

        .vp-btn-primary {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            box-shadow: 0 4px 14px rgba(99,102,241,0.3);
        }
        .vp-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); }

        .vp-btn-admin {
            background: rgba(124,58,237,0.15);
            color: #a78bfa;
            border: 1px solid rgba(124,58,237,0.25);
        }
        .vp-btn-admin:hover { background: rgba(124,58,237,0.25); color: #c4b5fd; }

        .vp-btn-danger {
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
        }
        .vp-btn-danger:hover { background: rgba(239,68,68,0.2); color: #fca5a5; }

        /* ---- Main Content ---- */
        .vp-main {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ---- Toast Notifications ---- */
        .vp-toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            animation: toastIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-10px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .vp-toast-success {
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.25);
            color: #34d399;
        }
        .vp-toast-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #f87171;
        }
        .vp-toast-icon { flex-shrink: 0; }
        .vp-toast-icon svg { width: 18px; height: 18px; }

        /* ---- Utility Cards ---- */
        .vp-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .vp-card:hover { border-color: var(--border-hover); }

        /* ---- Progress Bars ---- */
        .vp-progress-track {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.08);
            border-radius: 99px;
            overflow: hidden;
        }
        .vp-progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* ---- Status Badges ---- */
        .vp-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .vp-badge-success { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
        .vp-badge-danger  { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .vp-badge-warning { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
        .vp-badge-draft   { background: rgba(100,116,139,0.15); color: #94a3b8; border: 1px solid rgba(100,116,139,0.2); }
        .vp-badge-vip     { background: linear-gradient(135deg,rgba(251,191,36,0.2),rgba(245,158,11,0.1)); color: #fbbf24; border: 1px solid rgba(251,191,36,0.3); }

        .vp-pulse-dot {
            display: inline-block;
            width: 7px; height: 7px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* ---- Section Headers ---- */
        .vp-section-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
        }
        .vp-section-sub { color: var(--text-muted); font-size: 14px; margin-top: 4px; }

        /* ---- Footer ---- */
        .vp-footer {
            margin-top: 64px;
            border-top: 1px solid var(--border);
            padding: 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }
        .vp-footer span { color: var(--primary); }
    </style>
</head>
<body>
    <nav class="vp-nav">
        <div class="vp-nav-inner">
            <a href="{{ route('topics.index') }}" class="vp-logo">
                <div class="vp-logo-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="vp-logo-text">Vote<span>Pulse</span></span>
            </a>

            <div class="vp-nav-actions">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="vp-btn vp-btn-admin">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Admin
                        </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}" class="vp-btn vp-btn-ghost">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('topics.index') }}" class="vp-btn vp-btn-ghost">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        My Polls
                    </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;margin:0;">
                        @csrf
                        <button type="submit" class="vp-btn vp-btn-danger">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="vp-btn vp-btn-ghost">Sign In</a>
                    <a href="{{ route('register') }}" class="vp-btn vp-btn-primary">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="vp-main">
        @if(session('success'))
            <div class="vp-toast vp-toast-success" role="alert">
                <span class="vp-toast-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="vp-toast vp-toast-error" role="alert">
                <span class="vp-toast-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="vp-footer">
        Built with <span>♥</span> — VotePulse © {{ date('Y') }}
    </footer>

    <script>
        // 1. Force refresh on Back button (bfcache workaround)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) { window.location.reload(); }
        });



        // 3. Animate progress bars on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.vp-progress-fill').forEach(bar => {
                const target = bar.getAttribute('data-width') || bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = target; }, 100);
            });
        });
    </script>
</body>
</html>
