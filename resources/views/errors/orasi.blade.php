<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Portal Orasi Ilmiah — Universitas Mulawarman')</title>
    <link rel="icon" href="{{ asset('logo/unmul-20260408145731-e033c2.png') }}" type="image/png">
    <style>
        :root {
            --orasi-ink: #18213a;
            --orasi-navy: #0f1322;
            --orasi-gold: #efb12c;
            --orasi-gold-soft: #f6c85c;
            --orasi-blue: #2f8cf6;
            --orasi-muted: rgba(24, 33, 58, 0.72);
            --orasi-card: rgba(255, 255, 255, 0.94);
            --orasi-border: rgba(24, 33, 58, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--orasi-ink);
            background:
                radial-gradient(circle at 12% 18%, rgba(47, 140, 246, 0.18), transparent 34%),
                radial-gradient(circle at 88% 12%, rgba(246, 200, 92, 0.22), transparent 30%),
                linear-gradient(165deg, #10172b 0%, #0f1322 42%, #18213a 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .orasi-error-topbar {
            height: 4px;
            background: linear-gradient(90deg, var(--orasi-gold) 0%, var(--orasi-gold-soft) 48%, var(--orasi-blue) 100%);
            flex: 0 0 auto;
        }

        .orasi-error-shell {
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.25rem 3rem;
        }

        .orasi-error-card {
            width: min(100%, 720px);
            background: var(--orasi-card);
            border: 1px solid var(--orasi-border);
            border-radius: 28px;
            box-shadow:
                0 28px 80px rgba(8, 12, 24, 0.28),
                0 8px 24px rgba(15, 19, 34, 0.12);
            overflow: hidden;
            animation: orasi-error-rise 0.7s cubic-bezier(0.22, 1, 0.36, 1);
        }

        @keyframes orasi-error-rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .orasi-error-card__hero {
            padding: 1.35rem 1.5rem 1.1rem;
            background: linear-gradient(180deg, #f3bd42 0%, #efb12c 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.22);
        }

        .orasi-error-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .orasi-error-brand img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            flex: 0 0 auto;
        }

        .orasi-error-brand__text strong {
            display: block;
            font-size: 0.98rem;
            line-height: 1.35;
            color: var(--orasi-ink);
        }

        .orasi-error-brand__text span {
            display: block;
            margin-top: 0.15rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(24, 33, 58, 0.72);
        }

        .orasi-error-card__body {
            padding: 2rem 1.75rem 1.85rem;
            text-align: center;
        }

        .orasi-error-code {
            margin: 0;
            font-size: clamp(4.5rem, 18vw, 6.5rem);
            line-height: 0.95;
            font-weight: 800;
            letter-spacing: -0.06em;
            background: linear-gradient(135deg, var(--orasi-gold) 0%, #e6a91f 42%, var(--orasi-blue) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .orasi-error-title {
            margin: 0.35rem 0 0;
            font-size: clamp(1.35rem, 4vw, 1.85rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--orasi-ink);
        }

        .orasi-error-copy {
            margin: 1rem auto 0;
            max-width: 34rem;
            font-size: 1rem;
            line-height: 1.7;
            color: var(--orasi-muted);
        }

        .orasi-error-note {
            margin: 1.25rem auto 0;
            max-width: 34rem;
            padding: 0.9rem 1rem;
            border-radius: 16px;
            background: rgba(239, 177, 44, 0.12);
            border: 1px solid rgba(239, 177, 44, 0.28);
            color: #6b4d08;
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .orasi-error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
            margin-top: 1.65rem;
        }

        .orasi-error-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0.72rem 1.25rem;
            border-radius: 999px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .orasi-error-btn:hover {
            transform: translateY(-1px);
        }

        .orasi-error-btn--primary {
            background: linear-gradient(180deg, #f3bd42 0%, #efb12c 100%);
            color: var(--orasi-ink);
            box-shadow: 0 10px 24px rgba(239, 177, 44, 0.28);
        }

        .orasi-error-btn--ghost {
            background: #fff;
            color: var(--orasi-ink);
            border-color: rgba(24, 33, 58, 0.14);
        }

        .orasi-error-footer {
            padding: 0 1.75rem 1.5rem;
            text-align: center;
            font-size: 0.82rem;
            color: rgba(24, 33, 58, 0.55);
        }

        @media (max-width: 540px) {
            .orasi-error-card__body {
                padding: 1.65rem 1.25rem 1.5rem;
            }

            .orasi-error-actions {
                flex-direction: column;
            }

            .orasi-error-btn {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .orasi-error-card {
                animation: none;
            }

            .orasi-error-btn {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div class="orasi-error-topbar" aria-hidden="true"></div>

    <main class="orasi-error-shell" role="main">
        <article class="orasi-error-card">
            <header class="orasi-error-card__hero">
                <div class="orasi-error-brand">
                    <img src="{{ asset('logo/unmul-20260408145731-e033c2.png') }}" alt="Universitas Mulawarman">
                    <div class="orasi-error-brand__text">
                        <strong>Portal Orasi Ilmiah Guru Besar</strong>
                        <span>Universitas Mulawarman</span>
                    </div>
                </div>
            </header>

            <div class="orasi-error-card__body">
                <p class="orasi-error-code" aria-hidden="true">@yield('code')</p>
                <h1 class="orasi-error-title">@yield('heading')</h1>
                <p class="orasi-error-copy">@yield('copy')</p>

                @php($errorNote = trim($__env->yieldContent('note')))
                @if ($errorNote !== '')
                    <div class="orasi-error-note">{{ $errorNote }}</div>
                @endif

                <div class="orasi-error-actions">
                    @yield('actions')
                </div>
            </div>

            <footer class="orasi-error-footer">
                @yield('footer', '© ' . date('Y') . ' Universitas Mulawarman — Portal Orasi Ilmiah Guru Besar')
            </footer>
        </article>
    </main>
</body>
</html>
