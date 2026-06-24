<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Orasi — Universitas Mulawarman')</title>
    <link rel="icon" href="{{ asset('logo/unmul-20260408145731-e033c2.png') }}" type="image/png">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --brand-yellow: #F8B803;
            --brand-yellow-hover: #e8aa03;
            --brand-yellow-soft: #FFF6D6;
            --text: #111827;
            --muted: #6b7280;
            --border: rgba(15, 23, 42, .08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--text);
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-shell {
            width: 100%;
            max-width: @yield('max_width', '420px');
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .auth-brand img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            margin-bottom: .5rem;
        }

        .auth-brand .title {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .auth-brand .subtitle {
            color: var(--muted);
            font-size: .85rem;
        }

        .auth-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(15, 23, 42, .06);
            padding: 1.75rem;
        }

        .auth-card h1 {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0 0 .25rem;
        }

        .auth-card .lead {
            color: var(--muted);
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }

        .badge-admin {
            display: inline-block;
            background: var(--brand-yellow-soft);
            color: #92400e;
            font-size: .7rem;
            font-weight: 600;
            padding: .2rem .55rem;
            border-radius: 6px;
            margin-bottom: .5rem;
            border: 1px solid rgba(146, 64, 14, .12);
        }

        .form-label {
            font-weight: 600;
            font-size: .875rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border-color: #e5e7eb;
            padding: .55rem .75rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(248, 184, 3, .2);
        }

        .btn-brand {
            background: var(--brand-yellow);
            border: 1px solid var(--brand-yellow);
            color: var(--text);
            font-weight: 600;
            border-radius: 10px;
            padding: .6rem 1rem;
        }

        .btn-brand:hover {
            background: var(--brand-yellow-hover);
            border-color: var(--brand-yellow-hover);
            color: var(--text);
        }

        .alert {
            border-radius: 10px;
            font-size: .875rem;
        }

        .recovery-box {
            background: var(--brand-yellow-soft);
            border: 1px solid rgba(146, 64, 14, .12);
            border-radius: 12px;
            padding: 1rem;
        }

        .recovery-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: .8rem;
            font-weight: 600;
        }
    </style>

    @stack('head')
</head>
<body>
    <div class="auth-shell">
        <div class="auth-brand">
            <img src="{{ asset('images/icon.png') }}" alt="Unmul">
            <div class="title">Orasi Guru Besar</div>
            <div class="subtitle">Universitas Mulawarman</div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning mb-3">{{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
