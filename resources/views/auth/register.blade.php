@extends('layouts.app')

@section('title', 'Register')

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
    .auth-logo { font-family: var(--font-mono); font-size: 1.4rem; color: var(--accent); margin-bottom: 8px; letter-spacing: 0.05em; }
    .auth-logo span { color: var(--text-muted); }
    .auth-subtitle { font-size: 0.85rem; color: var(--text-muted); }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 0.75rem; font-family: var(--font-mono); color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 6px; }
    .form-input { width: 100%; background: var(--bg-input); border: 1px solid var(--border); color: var(--text); padding: 10px 14px; border-radius: 6px; font-size: 0.9rem; font-family: var(--font-main); transition: border-color 0.15s; outline: none; }
    .form-input:focus { border-color: var(--accent); }
    .form-input.error { border-color: var(--red); }
    .field-error { font-size: 0.75rem; color: var(--red); font-family: var(--font-mono); margin-top: 4px; }

    .btn-primary { width: 100%; background: var(--accent); color: #0a0e1a; border: none; padding: 12px; border-radius: 6px; font-size: 0.9rem; font-weight: 600; font-family: var(--font-main); cursor: pointer; transition: background 0.15s; margin-top: 8px; }
    .btn-primary:hover { background: var(--accent-dim); }

    .auth-footer { margin-top: 24px; text-align: center; font-size: 0.82rem; color: var(--text-muted); }
    .auth-footer a { color: var(--accent); text-decoration: none; }
    .auth-footer a:hover { text-decoration: underline; }

    .pw-hint { font-size: 0.72rem; color: var(--text-muted); font-family: var(--font-mono); margin-top: 4px; }

    .security-row {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .sec-chip {
        font-size: 0.68rem;
        font-family: var(--font-mono);
        padding: 4px 8px;
        border-radius: 4px;
        background: rgba(0,212,255,0.08);
        border: 1px solid rgba(0,212,255,0.2);
        color: var(--accent);
    }
</style>
@endsection

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">secure<span>/</span>vault</div>
        <div class="auth-subtitle">Create your secure account</div>
    </div>

    <div class="security-row">
        <span class="sec-chip">bcrypt hash</span>
        <span class="sec-chip">AES-256 files</span>
        <span class="sec-chip">SHA-256 integrity</span>
    </div>

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input {{ $errors->has('name') ? 'error' : '' }}" placeholder="Juan dela Cruz" required autofocus>
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="you@example.com" required>
            @error('email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="••••••••" required>
            <div class="pw-hint">Min 8 characters · uppercase + lowercase + number</div>
            @error('password')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary">Create Account →</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>
@endsection