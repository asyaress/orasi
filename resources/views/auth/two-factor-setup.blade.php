@extends('layouts.auth')

@section('title', 'Setup Authenticator — Admin Orasi')
@section('max_width', '960px')

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="auth-card">
                <span class="badge-admin">Security</span>
                <h1>Setup Authenticator</h1>
                <p class="lead">
                    Scan QR di aplikasi Authenticator, lalu masukkan kode 6 digit untuk konfirmasi.
                </p>

                <div class="text-center my-3">
                    <img src="{{ $qr }}" alt="QR Code" style="width:220px;height:220px;border-radius:12px;border:1px solid var(--border);padding:8px;background:#fff;">
                </div>

                <p class="text-muted small mb-1">Secret manual:</p>
                <p class="fw-semibold mb-3" style="letter-spacing:.06em;font-family:monospace;">{{ $secret }}</p>

                <form method="post" action="{{ route('two-factor.confirm') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="code">Kode 6 digit</label>
                        <input name="code" id="code" class="form-control" placeholder="123456" inputmode="numeric" autocomplete="one-time-code" required>
                        @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-brand w-100" type="submit">Konfirmasi & Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="auth-card">
                <h1 style="font-size:1.1rem;">Recovery Codes</h1>
                <p class="lead">Simpan di tempat aman. Hanya ditampilkan sekali.</p>

                <div class="recovery-box">
                    <div class="row g-2">
                        @foreach ($recovery as $code)
                            <div class="col-6">
                                <div class="recovery-code">{{ $code }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    Setelah setup, kamu akan diminta login ulang dan memasukkan kode Authenticator.
                </p>
            </div>
        </div>
    </div>
@endsection
