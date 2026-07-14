<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'VotePulse') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            *, body { font-family: 'Inter', sans-serif; box-sizing: border-box; }
            .auth-bg {
                min-height: 100vh;
                background: #0c0c14;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
            .auth-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.18;
                pointer-events: none;
            }
            .auth-orb-1 { width: 500px; height: 500px; background: #6366f1; top: -120px; left: -150px; }
            .auth-orb-2 { width: 400px; height: 400px; background: #10b981; bottom: -100px; right: -100px; }
            .auth-orb-3 { width: 300px; height: 300px; background: #8b5cf6; top: 50%; left: 50%; transform: translate(-50%,-50%); }

            .auth-card {
                background: rgba(255,255,255,0.04);
                border: 1px solid rgba(255,255,255,0.08);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border-radius: 24px;
                padding: 48px 44px;
                width: 100%;
                max-width: 440px;
                position: relative;
                z-index: 10;
                box-shadow: 0 32px 64px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.06);
            }

            .auth-logo-wrap {
                text-align: center;
                margin-bottom: 32px;
            }
            .auth-logo {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 56px; height: 56px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border-radius: 16px;
                margin-bottom: 16px;
                box-shadow: 0 8px 24px rgba(99,102,241,0.4);
            }
            .auth-logo svg { width: 28px; height: 28px; color: white; }
            .auth-brand { font-size: 22px; font-weight: 800; color: #f8fafc; letter-spacing: -0.5px; }
            .auth-brand span { color: #6366f1; }
            .auth-tagline { color: #64748b; font-size: 13px; margin-top: 4px; }

            /* Input styles */
            .auth-card label { display: block; font-size: 13px; font-weight: 500; color: #94a3b8; margin-bottom: 6px; }
            .auth-card input[type=email],
            .auth-card input[type=text],
            .auth-card input[type=password] {
                width: 100%;
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 10px;
                padding: 11px 14px;
                color: #f8fafc;
                font-size: 14px;
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .auth-card input[type=email]:focus,
            .auth-card input[type=text]:focus,
            .auth-card input[type=password]:focus {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            }
            .auth-card input::placeholder { color: #475569; }

            .auth-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 12px 20px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                color: white;
                font-weight: 600;
                font-size: 14px;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.2s;
                box-shadow: 0 4px 16px rgba(99,102,241,0.35);
                letter-spacing: 0.01em;
                margin-top: 8px;
            }
            .auth-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 24px rgba(99,102,241,0.45);
                opacity: 0.95;
            }
            .auth-btn:active { transform: translateY(0); }

            .auth-divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
            .auth-divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.08); }
            .auth-divider-text { font-size: 12px; color: #475569; }

            .auth-link {
                color: #6366f1;
                font-size: 13px;
                font-weight: 500;
                text-decoration: none;
                transition: color 0.2s;
            }
            .auth-link:hover { color: #818cf8; text-decoration: underline; }

            .auth-error { color: #f87171; font-size: 12px; margin-top: 5px; }

            .auth-check-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
            .auth-check-label input[type=checkbox] {
                width: 15px; height: 15px;
                accent-color: #6366f1;
                cursor: pointer;
            }
            .auth-check-label span { font-size: 13px; color: #94a3b8; }
        </style>
    </head>
    <body>
        <div class="auth-bg">
            <div class="auth-orb auth-orb-1"></div>
            <div class="auth-orb auth-orb-2"></div>
            <div class="auth-orb auth-orb-3"></div>

            <div class="auth-card">
                <div class="auth-logo-wrap">
                    <div class="auth-logo">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="auth-brand">Vote<span>Pulse</span></div>
                    <div class="auth-tagline">Your voice. Your vote. Your platform.</div>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
