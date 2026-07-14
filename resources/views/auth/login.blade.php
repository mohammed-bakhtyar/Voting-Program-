<x-guest-layout>
{{-- Session Status --}}
<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf

    {{-- Email --}}
    <div>
        <label for="email">Email address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
        @error('email')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
        @error('password')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Remember + Forgot --}}
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <label class="auth-check-label" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember">
            <span>Remember me</span>
        </label>
        @if (Route::has('password.request'))
            <a class="auth-link" href="{{ route('password.request') }}">Forgot password?</a>
        @endif
    </div>

    <button type="submit" class="auth-btn">
        <svg style="width:16px;height:16px;margin-right:8px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Sign In
    </button>

    <div class="auth-divider">
        <div class="auth-divider-line"></div>
        <span class="auth-divider-text">New here?</span>
        <div class="auth-divider-line"></div>
    </div>

    <div style="text-align:center;">
        <a class="auth-link" href="{{ route('register') }}">Create an account →</a>
    </div>
</form>
</x-guest-layout>
