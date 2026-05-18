@extends('layouts.app')

@section('title', 'Login')

@section('extra-styles')
<style>
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }

    .auth-card {
        width: 100%;
        max-width: 420px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 0 60px rgba(0,212,255,0.05);
    }

    .auth-header { margin-bottom: 32px; }
    .auth-logo {
        font-family: var(--font-mono);
        font-size: 1.4rem;
        color: var(--accent);
        margin-bottom: 8px;
        letter-spacing: 0.05em;
    }
    .auth-logo span { color: var(--text-muted); }
    .auth-subtitle { font-size: 0.85rem; color: var(--text-muted); }

    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block;
        font-size: 0.75rem;
        font-family: var(--font-mono);
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 6px;
    }
    .form-input {
        width: 100%;
        background: var(--bg-input);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 10px 14px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-family: var(--font-main);
        transition: border-color 0.15s;
        outline: none;
    }
    .form-input:focus { border-color: var(--accent); }
    .form-input.error { border-color: var(--red); }

    .field-error { font-size: 0.75rem; color: var(--red); font-family: var(--font-mono); margin-top: 4px; }

    .btn-primary {
        width: 100%;
        background: var(--accent);
        color: #0a0e1a;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: var(--font-main);
        cursor: pointer;
        transition: background 0.15s;
        margin-top: 8px;
    }
    .btn-primary:hover { background: var(--accent-dim); }

    .auth-footer {
        margin-top: 24px;
        text-align: center;
        font-size: 0.82rem;
        color: var(--text-muted);
    }
    .auth-footer a { color: var(--accent); text-decoration: none; }
    .auth-footer a:hover { text-decoration: underline; }

    .security-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(0,255,136,0.04);
        border: 1px solid rgba(0,255,136,0.15);
        border-radius: 6px;
        padding: 10px 12px;
        margin-bottom: 24px;
        font-size: 0.75rem;
        font-family: var(--font-mono);
        color: var(--green);
    }
    .lock-icon { font-size: 1rem; }

    .remember-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    .remember-row input[type="checkbox"] { accent-color: var(--accent); }
    .remember-row label { font-size: 0.82rem; color: var(--text-muted); cursor: pointer; }
</style>
@endsection

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">secure<span>/</span>vault</div>
        <div class="auth-subtitle">Sign in to access your encrypted files</div>
    </div>

    <div class="security-badge">
        <span class="lock-icon">🔒</span>
        AES-256-CBC encrypted · bcrypt passwords · SHA-256 integrity
    </div>

    {{-- Global error --}}
    @if ($errors->any() && $errors->has('email') && !$errors->first('email'))
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                placeholder="you@example.com"
                required
                autofocus
            >
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                placeholder="••••••••"
                required
            >
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn-primary">Sign In →</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
    </div>
</div>
@endsection