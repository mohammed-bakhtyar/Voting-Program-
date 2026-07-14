<x-guest-layout>
<form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:18px;">
    @csrf

    {{-- Name --}}
    <div>
        <label for="name">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
        @error('name')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email">Email address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
        @error('email')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
        @error('password')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Confirm Password --}}
    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password">
        @error('password_confirmation')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="auth-btn">
        <svg style="width:16px;height:16px;margin-right:8px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Create Account
    </button>

    <div class="auth-divider">
        <div class="auth-divider-line"></div>
        <span class="auth-divider-text">Already have an account?</span>
        <div class="auth-divider-line"></div>
    </div>

    <div style="text-align:center;">
        <a class="auth-link" href="{{ route('login') }}">← Sign in instead</a>
    </div>
</form>
</x-guest-layout>
