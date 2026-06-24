@extends('layouts.auth')

@section('title', 'Login — Admin Orasi')

@section('content')
    <div class="auth-card">
        <span class="badge-admin">Admin</span>
        <h1>Login</h1>
        <p class="lead">Masuk ke panel pengelolaan Orasi Ilmiah.</p>

        <form method="post" action="{{ route('login.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                @if ($recaptchaEnabled && $recaptchaSiteKey)
                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                    @error('g-recaptcha-response')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @else
                    <div class="text-muted small">reCAPTCHA nonaktif (mode local).</div>
                @endif
            </div>

            <button class="btn btn-brand w-100" type="submit">Login</button>
        </form>
    </div>
@endsection

@push('scripts')
    @if ($recaptchaEnabled && $recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush
