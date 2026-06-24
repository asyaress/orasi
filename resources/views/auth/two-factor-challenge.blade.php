@extends('layouts.auth')

@section('title', 'Verifikasi 2FA — Admin Orasi')

@section('content')
    <div class="auth-card">
        <span class="badge-admin">Security</span>
        <h1>Verifikasi 2FA</h1>
        <p class="lead">Masukkan kode Authenticator atau recovery code.</p>

        <form method="post" action="{{ route('two-factor.challenge.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="type">Metode</label>
                <select class="form-select" name="type" id="type" onchange="togglePlaceholder()">
                    <option value="totp">Authenticator (6 digit)</option>
                    <option value="recovery">Recovery code</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" for="code">Kode</label>
                <input name="code" id="code" class="form-control" placeholder="123456" autocomplete="one-time-code" required>
                @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-brand w-100" type="submit">Masuk</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePlaceholder() {
            const t = document.getElementById('type').value;
            document.getElementById('code').placeholder = t === 'recovery' ? 'RECOVERYCODE' : '123456';
        }
    </script>
@endpush
