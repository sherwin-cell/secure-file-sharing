<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SecureVault') — Secure File Sharing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0a0e1a;
            --bg-card:   #111827;
            --bg-input:  #1a2236;
            --border:    #1e2d45;
            --border-hi: #2a4070;
            --accent:    #00d4ff;
            --accent-dim:#0099bb;
            --green:     #00ff88;
            --red:       #ff4d6d;
            --yellow:    #ffcc00;
            --text:      #e2e8f0;
            --text-muted:#64748b;
            --font-mono: 'IBM Plex Mono', monospace;
            --font-main: 'Space Grotesk', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-main);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(0,212,255,0.07) 0%, transparent 60%),
                repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(30,45,69,0.3) 39px, rgba(30,45,69,0.3) 40px),
                repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(30,45,69,0.3) 39px, rgba(30,45,69,0.3) 40px);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 0.875rem;
            margin-bottom: 16px;
            border-left: 3px solid;
            font-family: var(--font-mono);
        }
        .alert-success { background: rgba(0,255,136,0.08); border-color: var(--green); color: var(--green); }
        .alert-error   { background: rgba(255,77,109,0.08); border-color: var(--red);   color: var(--red); }
        .alert-warning { background: rgba(255,204,0,0.08);  border-color: var(--yellow); color: var(--yellow); }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 60px;
            background: rgba(17,24,39,0.9);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-logo {
            font-family: var(--font-mono);
            font-size: 1.1rem;
            color: var(--accent);
            letter-spacing: 0.05em;
            text-decoration: none;
        }
        .nav-logo span { color: var(--text-muted); }
        .nav-right { display: flex; align-items: center; gap: 16px; }
        .nav-user { font-size: 0.8rem; color: var(--text-muted); font-family: var(--font-mono); }
        .btn-logout {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            font-family: var(--font-mono);
            transition: all 0.15s;
        }
        .btn-logout:hover { border-color: var(--red); color: var(--red); }

        .container { max-width: 1000px; margin: 0 auto; padding: 40px 24px; }
    </style>

    {{-- Page-specific styles go here --}}
    @yield('extra-styles')

    @yield('head')
</head>
<body>

@auth
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-logo">secure<span>/</span>vault</a>
    <div class="nav-right">
        <span class="nav-user">{{ Auth::user()->email }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">logout</button>
        </form>
    </div>
</nav>
@endauth

@yield('content')

</body>
</html>