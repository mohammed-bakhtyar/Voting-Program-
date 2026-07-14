<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VotePulse - Explore The Outer Space of Voting</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #050510;
            --bg-purple: #130b29;
            --accent-purple: #8b5cf6;
            --accent-blue: #6366f1;
            --accent-gold: #fbbf24;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body {
            background-color: var(--bg-deep);
            color: var(--text-main);
            overflow-x: hidden;
            background-image: radial-gradient(circle at top right, var(--bg-purple), var(--bg-deep) 60%);
            min-height: 100vh;
        }

        /* Navbar */
        nav {
            position: fixed; top: 0; left: 0; right: 0;
            padding: 24px 40px; display: flex; justify-content: space-between; align-items: center;
            z-index: 100; backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .logo { font-size: 20px; font-weight: 800; display: flex; align-items: center; gap: 8px; color: #fff; text-decoration: none; }
        .logo svg { width: 24px; height: 24px; color: var(--accent-blue); }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-btn {
            padding: 10px 24px; border-radius: 12px; font-size: 14px; font-weight: 600;
            text-decoration: none; transition: all 0.3s;
        }
        .btn-login { color: var(--text-main); background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); }
        .btn-login:hover { background: rgba(255,255,255,0.1); }
        .btn-primary { background: linear-gradient(135deg, var(--accent-blue), var(--accent-purple)); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.4); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(99,102,241,0.6); }

        /* Parallax Container */
        .parallax-wrapper {
            position: relative; height: 100vh;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; perspective: 1000px;
        }

        /* Floating Elements */
        .layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }
        
        .star { position: absolute; background: #fff; border-radius: 50%; opacity: 0.8; }
        
        .planet { position: absolute; border-radius: 50%; }
        .planet-1 { width: 150px; height: 150px; background: linear-gradient(135deg, #4ade80, #3b82f6); top: 15%; right: 10%; filter: blur(1px); opacity: 0.9; }
        .planet-2 { width: 80px; height: 80px; background: linear-gradient(135deg, #f43f5e, #a855f7); bottom: 20%; left: 15%; filter: blur(2px); opacity: 0.7; }
        .planet-3 { width: 250px; height: 250px; background: radial-gradient(circle at 30% 30%, #312e81, #0f172a); bottom: -10%; right: -5%; border: 1px solid rgba(255,255,255,0.05); }

        .float-card {
            position: absolute;
            background: rgba(20, 21, 37, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 99px;
            padding: 12px 24px;
            color: #f8fafc;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.05);
            pointer-events: auto;
            transition: all 0.3s;
        }
        .float-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: scale(1.05); }
        .float-card .pulse { width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981; animation: pulseGlow 2s infinite; }
        .float-card .vip-icon { font-size: 16px; }

        .float-card-1 {
            top: 25%; left: 15%;
            animation: floatCard 6s ease-in-out infinite;
        }
        .float-card-2 {
            bottom: 30%; right: 15%;
            animation: floatCard 8s ease-in-out infinite reverse;
        }

        @keyframes floatCard {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Hero Text */
        .hero-content {
            position: relative; z-index: 10; text-align: center; max-width: 800px;
            padding: 0 20px; pointer-events: auto;
        }
        .hero-badge {
            display: inline-block; padding: 6px 16px; border-radius: 99px;
            background: rgba(99, 102, 241, 0.1); color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.3); font-size: 13px; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 24px;
        }
        .hero-title {
            font-size: 64px; font-weight: 900; line-height: 1.1; margin-bottom: 24px;
            background: linear-gradient(135deg, #fff, #a5b4fc);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }
        .hero-subtitle { font-size: 18px; color: var(--text-muted); line-height: 1.6; margin-bottom: 40px; }
        
        /* Content Section */
        .content-section {
            position: relative; z-index: 10; padding: 100px 20px;
            background: linear-gradient(to bottom, transparent, #080816);
        }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-title { font-size: 36px; font-weight: 800; margin-bottom: 16px; }
        .section-subtitle { color: var(--text-muted); font-size: 16px; max-width: 600px; margin: 0 auto; }

        /* VIP Explainer */
        .vip-showcase {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 40px;
            max-width: 1000px; margin: 0 auto;
        }
        .vip-card-wrap { flex: 1; min-width: 300px; background: rgba(255,255,255,0.03); border-radius: 24px; padding: 32px; border: 1px solid rgba(255,255,255,0.05); }
        .vip-card-wrap h3 { font-size: 20px; margin-bottom: 24px; color: #fff; text-align: center; }
        
        /* Fake option cards */
        .fake-option {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px; padding: 16px 20px; margin-bottom: 12px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .fake-radio { width: 22px; height: 22px; border-radius: 50%; border: 2px solid #334155; }
        .fake-label { font-size: 15px; font-weight: 600; color: #e2e8f0; }

        /* VIP Fake option */
        .fake-option.vip-style {
            border-color: rgba(251,191,36,0.3); background: rgba(251,191,36,0.04); position: relative; overflow: hidden;
            transform: scale(1.05); box-shadow: 0 10px 30px rgba(251,191,36,0.1); z-index: 2; margin: 24px 0;
        }
        .fake-option.vip-style::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
            animation: vip-shimmer 2s linear infinite; background-size: 200% 100%;
        }
        @keyframes vip-shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        .vip-badge-opt {
            display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 99px;
            background: linear-gradient(135deg, rgba(251,191,36,0.2), rgba(245,158,11,0.1));
            color: #fbbf24; border: 1px solid rgba(251,191,36,0.35); font-size: 10px; font-weight: 800; text-transform: uppercase;
        }

        .vip-features { list-style: none; margin-top: 32px; }
        .vip-features li { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; color: var(--text-muted); font-size: 14px; line-height: 1.5; }
        .vip-features li svg { width: 20px; height: 20px; color: var(--accent-gold); flex-shrink: 0; }

        /* Interactive Footer */
        footer { padding: 60px 20px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); margin-top: 60px; }
        .footer-logo { font-size: 24px; font-weight: 900; margin-bottom: 20px; color: #fff; }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            VotePulse
        </a>
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ url('/admin/dashboard') }}" class="nav-btn btn-login">Admin Dashboard</a>
                    @else
                        <a href="{{ route('topics.index') }}" class="nav-btn btn-login">My Polls</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-btn btn-login">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-btn btn-primary">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Parallax -->
    <div class="parallax-wrapper" id="parallax-container">
        <!-- Background elements (slower) -->
        <div class="layer" data-speed="0.2">
            <div id="stars"></div>
        </div>
        
        <!-- Mid elements -->
        <div class="layer" data-speed="0.4">
            <div class="planet planet-1"></div>
            <div class="planet planet-2"></div>
        </div>

        <!-- Foreground elements (faster) -->
        <div class="layer" data-speed="0.8">
            <div class="planet planet-3"></div>
            
            <div class="float-card float-card-1">
                <div class="pulse"></div>
                Live 10k+ Votes
            </div>
            
            <div class="float-card float-card-2">
                <span class="vip-icon">👑</span>
                VIP System
            </div>
        </div>

        <!-- Content -->
        <div class="layer hero-content" data-speed="0.5" style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%;">
            <span class="hero-badge">Next-Gen Polling Platform</span>
            <h1 class="hero-title">Explore The Outer Space<br>of Real-Time Voting</h1>
            <p class="hero-subtitle">Engage your audience with stunning polls, dynamic real-time results, and powerful VIP options that command attention. No page reloads required.</p>
            <div style="display:flex; gap:16px;">
                <a href="{{ route('topics.index') }}" class="nav-btn btn-primary" style="padding: 16px 32px; font-size: 16px;">Browse Polls</a>
                @guest
                    <a href="{{ route('register') }}" class="nav-btn btn-login" style="padding: 16px 32px; font-size: 16px;">Create Account</a>
                @endguest
            </div>
        </div>
    </div>

    <!-- VIP Explainer Section -->
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Command Greater Influence</h2>
            <p class="section-subtitle">Why settle for standard when you can stand out? Discover how the VIP Upgrade maximizes visibility and engagement for your best options.</p>
        </div>

        <div class="vip-showcase">
            <div class="vip-card-wrap">
                <h3>Standard Polling</h3>
                <div class="fake-option">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="fake-radio"></div>
                        <span class="fake-label">Option A</span>
                    </div>
                </div>
                <div class="fake-option">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="fake-radio"></div>
                        <span class="fake-label">Option B</span>
                    </div>
                </div>
                <div class="fake-option">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="fake-radio"></div>
                        <span class="fake-label">Option C</span>
                    </div>
                </div>
            </div>

            <div class="vip-card-wrap" style="background: rgba(99,102,241,0.02); border-color: rgba(99,102,241,0.1);">
                <h3 style="color:var(--accent-gold);">The VIP Advantage</h3>
                <div class="fake-option">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="fake-radio"></div>
                        <span class="fake-label">Basic Choice</span>
                    </div>
                </div>
                <div class="fake-option vip-style">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="fake-radio" style="border-color:#f59e0b;"></div>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <span class="fake-label">Premium Upgrade</span>
                            <span class="vip-badge-opt">👑 VIP Choice</span>
                        </div>
                    </div>
                </div>
                <div class="fake-option">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="fake-radio"></div>
                        <span class="fake-label">Another Choice</span>
                    </div>
                </div>
                
                <ul class="vip-features">
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span><strong>Maximum Visibility:</strong> Glowing gold borders and shimmer effects instantly catch the voter's eye.</span>
                    </li>
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span><strong>Higher Engagement:</strong> VIP options statistically receive more interaction due to their premium placement.</span>
                    </li>
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span><strong>Exclusive Feel:</strong> Perfect for sponsored options, premium answers, or highlighted features.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-logo">VotePulse</div>
        <p style="color:var(--text-muted); font-size:14px;">Bringing the future of real-time polling directly to you.</p>
    </footer>

    <script>
        // Generate stars
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 150; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.width = Math.random() * 3 + 'px';
            star.style.height = star.style.width;
            star.style.left = Math.random() * 100 + 'vw';
            star.style.top = Math.random() * 150 + 'vh';
            star.style.animationDelay = Math.random() * 2 + 's';
            starsContainer.appendChild(star);
        }

        // Parallax Effect (Mouse + Scroll)
        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            
            document.querySelectorAll('.layer').forEach(layer => {
                const speed = layer.getAttribute('data-speed');
                layer.style.transform = `translateX(${x * speed}px) translateY(${y * speed}px)`;
            });
        });

        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            document.querySelectorAll('.layer').forEach(layer => {
                const speed = layer.getAttribute('data-speed');
                // The mouse move transform is overwritten here, but a real engine would combine them.
                // For simplicity, we just add a slight vertical scroll parallax to the container.
            });
            document.getElementById('parallax-container').style.backgroundPositionY = `${scrollY * 0.3}px`;
        });
    </script>
</body>
</html>
