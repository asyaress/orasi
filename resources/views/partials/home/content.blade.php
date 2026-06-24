@push('styles')
    <style>
        .orasi-home {
            --orasi-header-height: 78px;
            background: transparent;
            overflow-x: hidden;
        }

        .orasi-hero {
            min-height: 100vh;
            min-height: 100svh;
            padding: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            touch-action: pan-y;
            background: transparent;
        }

        .orasi-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(8, 12, 22, 0.56) 0%, rgba(8, 12, 22, 0.44) 38%, rgba(8, 12, 22, 0.62) 100%),
                radial-gradient(circle at 22% 38%, rgba(8, 12, 22, 0.12), transparent 28%),
                radial-gradient(circle at 78% 52%, rgba(8, 12, 22, 0.18), transparent 30%);
        }

        .orasi-hero-backdrop,
        .orasi-hero-video {
            position: absolute;
            inset: 0;
            pointer-events: none;
            touch-action: pan-y;
        }

        .orasi-hero-backdrop {
            background-position: center;
            background-size: cover;
        }

        .orasi-hero-video {
            overflow: hidden;
            opacity: 1;
        }

        .orasi-hero-video iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100vw;
            height: 56.25vw;
            min-width: 177.78vh;
            min-width: 177.78svh;
            min-height: 100vh;
            min-height: 100svh;
            transform: translate(-50%, -50%) scale(1.08);
            pointer-events: none;
            border: 0;
            touch-action: pan-y;
        }

        .orasi-hero-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            touch-action: pan-y;
            background: transparent;
        }

        .orasi-hero-shell {
            width: 100%;
            min-height: 100vh;
            min-height: 100svh;
            padding: calc(var(--orasi-header-height) + 88px) 0 64px;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            touch-action: pan-y;
        }

        .orasi-hero-title {
            max-width: 820px;
            font-size: clamp(3.2rem, 4.2vw, 4.875rem);
            line-height: 0.98;
            margin: 0;
            color: #fff;
            letter-spacing: -0.05em;
        }

        .orasi-hero-aside {
            max-width: 360px;
            margin-left: auto;
        }

        .orasi-hero-counter {
            padding: 28px 30px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            max-width: 360px;
        }

        .orasi-hero-counter-label {
            margin-bottom: 12px;
            color: rgba(255, 255, 255, 0.62);
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-hero-counter-value {
            margin: 0;
            font-size: clamp(4rem, 7vw, 6rem);
            line-height: 0.9;
            color: #fff;
        }

        .orasi-hero-counter-copy {
            margin: 12px 0 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 1rem;
            line-height: 1.65;
        }

        .orasi-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 22px;
        }

        .orasi-page-banner {
            position: relative;
            min-height: 360px;
            padding: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            background: #0f1322;
        }

        .orasi-page-banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--orasi-page-banner-bg);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.34;
            transform: scale(1.03);
        }

        .orasi-page-banner.has-video::before {
            opacity: 0;
        }

        .orasi-page-banner-video {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: #0f1322;
            pointer-events: none;
        }

        .orasi-page-banner-video iframe,
        .orasi-page-banner-video video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100vw;
            height: 56.25vw;
            min-width: 177.78vh;
            min-height: 100%;
            border: 0;
            object-fit: cover;
            opacity: 0.72;
            filter: brightness(0.58) saturate(0.95) contrast(1.03);
            transform: translate(-50%, -50%) scale(1.08);
        }

        .orasi-page-banner.has-video .orasi-page-banner-shell {
            z-index: 2;
        }

        .orasi-page-banner::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(15, 19, 34, 0.84) 0%, rgba(15, 19, 34, 0.7) 45%, rgba(15, 19, 34, 0.9) 100%),
                radial-gradient(circle at top left, rgba(246, 178, 52, 0.12), transparent 28%);
        }

        .orasi-page-banner-shell {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: calc(var(--orasi-header-height) + 78px) 0 78px;
        }

        .orasi-page-banner-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
        }

        .orasi-page-banner-title {
            margin: 0;
            color: #fff;
            font-size: clamp(2.6rem, 4.4vw, 4.2rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
        }

        .orasi-page-banner-copy {
            max-width: 760px;
            margin: 18px auto 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 1.05rem;
            line-height: 1.75;
        }

        .orasi-section-page-intro {
            margin-bottom: 28px;
        }

        .orasi-section-page-copy {
            max-width: 760px;
            margin: 12px auto 0;
            color: #667085;
            font-size: 1.02rem;
            line-height: 1.75;
        }

        .orasi-section-page-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 28px;
        }

        .orasi-section-page-metric {
            padding: 22px 20px 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(247, 250, 255, 0.96) 100%);
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 16px 44px rgba(15, 23, 42, 0.08);
            text-align: left;
            height: 100%;
        }

        .orasi-section-page-metric-value {
            margin: 0;
            color: #18213a;
            font-size: 2rem;
            font-weight: 900;
            line-height: 0.95;
        }

        .orasi-section-page-metric-label {
            margin-top: 12px;
            color: #18213a;
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .orasi-section-page-metric-copy {
            margin: 10px 0 0;
            color: #667085;
            font-size: 0.92rem;
            line-height: 1.6;
        }

        @media (min-width: 2200px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 96px) 0 84px;
            }

            .orasi-hero-title {
                max-width: 920px;
                font-size: clamp(4rem, 4vw, 5.4rem);
            }

            .orasi-hero-aside {
                max-width: 460px;
            }
        }

        @media (max-width: 1919.98px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 74px) 0 56px;
            }

            .orasi-hero-title {
                max-width: 760px;
                font-size: clamp(3rem, 3.9vw, 4.5rem);
            }

            .orasi-hero-aside {
                max-width: 340px;
            }
        }

        @media (max-width: 1399.98px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 68px) 0 56px;
            }

            .orasi-hero-title {
                max-width: 700px;
                font-size: clamp(2.9rem, 3.6vw, 4.15rem);
            }

            .orasi-hero-lead {
                font-size: 1.04rem;
            }
        }

        @media (max-width: 1199.98px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 60px) 0 52px;
            }

            .orasi-hero-title {
                max-width: 100%;
                font-size: clamp(2.7rem, 4.6vw, 3.9rem);
            }

            .orasi-hero-aside {
                max-width: 360px;
            }

            .orasi-page-banner-shell {
                padding: calc(var(--orasi-header-height) + 64px) 0 64px;
            }

            .orasi-section-page-metrics {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 1200px) and (max-height: 980px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 58px) 0 42px;
            }

            .orasi-hero-title {
                max-width: 700px;
                font-size: clamp(2.8rem, 3.6vw, 4rem);
            }

            .orasi-hero-counter {
                padding: 24px 26px;
            }

            .orasi-hero-actions {
                margin-top: 16px;
            }
        }

        @media (min-width: 1200px) and (max-height: 900px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 48px) 0 30px;
            }

            .orasi-hero-title {
                max-width: 640px;
                font-size: clamp(2.6rem, 3.3vw, 3.7rem);
            }

            .orasi-hero-aside {
                max-width: 360px;
            }

            .orasi-hero-counter-value {
                font-size: clamp(3.2rem, 5.2vw, 4.6rem);
            }

            .orasi-hero-counter-copy {
                font-size: 0.95rem;
                line-height: 1.55;
            }
        }

        @media (min-width: 1200px) and (max-height: 860px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 38px) 0 24px;
            }

            .orasi-hero-title {
                max-width: 600px;
                font-size: clamp(2.4rem, 3vw, 3.35rem);
                line-height: 0.96;
            }

            .orasi-hero-counter {
                padding: 22px 24px;
            }

            .orasi-hero-counter-value {
                font-size: clamp(3rem, 5vw, 4.1rem);
            }

            .orasi-hero-counter-copy {
                font-size: 0.9rem;
                line-height: 1.45;
            }

            .orasi-hero-actions .btn-main,
            .orasi-hero-actions .btn-line {
                padding-top: 10px;
                padding-bottom: 10px;
            }
        }

        .orasi-card,
        .orasi-surface {
            border-radius: 24px;
            background: #fff;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 60px rgba(31, 41, 55, 0.08);
        }

        .orasi-card {
            height: 100%;
            overflow: hidden;
        }

        .orasi-video-card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 20px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 60px rgba(31, 41, 55, 0.08);
            height: 100%;
            transition: transform 0.32s ease, box-shadow 0.32s ease;
        }

        .orasi-video-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 28px 84px rgba(31, 41, 55, 0.14);
        }

        .orasi-video-thumb {
            position: relative;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #0f1322;
        }

        .orasi-video-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1), filter 0.55s ease;
        }

        .orasi-video-card:hover .orasi-video-thumb img {
            transform: scale(1.08);
            filter: saturate(1.06) contrast(1.02);
        }

        .orasi-video-thumb::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(15, 19, 34, 0.08) 0%, rgba(15, 19, 34, 0.14) 32%, rgba(15, 19, 34, 0.8) 100%);
            transition: opacity 0.32s ease;
        }

        .orasi-video-play {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 2;
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            color: #18213a;
            box-shadow: 0 14px 34px rgba(15, 19, 34, 0.16);
            transition: transform 0.32s ease, box-shadow 0.32s ease, opacity 0.32s ease;
        }

        .orasi-video-play i {
            margin-left: 2px;
        }

        .orasi-video-card:hover .orasi-video-play {
            transform: scale(1.06);
            box-shadow: 0 18px 42px rgba(15, 19, 34, 0.2);
        }

        .orasi-video-thumb-overlay {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 16px 18px 18px;
            color: #fff;
            transform: translate3d(0, 4px, 0);
            transition: transform 0.32s ease, opacity 0.32s ease;
        }

        .orasi-video-card:hover .orasi-video-thumb-overlay {
            transform: translate3d(0, 0, 0);
        }

        .orasi-video-thumb-topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .orasi-video-thumb-copy {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .orasi-video-thumb-title {
            margin: 0;
            max-width: 100%;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
            text-shadow: 0 6px 18px rgba(15, 19, 34, 0.4);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orasi-video-thumb-meta {
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.84rem;
            line-height: 1.35;
            text-shadow: 0 4px 12px rgba(15, 19, 34, 0.34);
        }

        .orasi-video-slider {
            position: relative;
            overflow: hidden;
        }

        .orasi-video-slider.is-home-slider {
            max-width: 1120px;
            margin: 0 auto;
        }

        .orasi-video-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translate3d(0, 26px, 0) scale(0.985);
            transition:
                opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                visibility 0s linear 0.8s;
        }

        .orasi-video-slide.is-active {
            position: relative;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translate3d(0, 0, 0) scale(1);
            transition-delay: 0s, 0s, 0s;
            z-index: 2;
        }

        .orasi-video-slide.is-leaving {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: visible;
            pointer-events: none;
            transform: translate3d(0, -18px, 0) scale(1.012);
            z-index: 1;
        }

        .orasi-video-slide-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 26px;
        }

        .orasi-video-slide-year {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #18213a;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
            text-align: center;
            opacity: 0;
            transform: translate3d(0, 18px, 0);
        }

        .orasi-video-slide.is-active .orasi-video-slide-year {
            animation: orasiYearReveal 0.95s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .orasi-video-slide .orasi-video-card {
            opacity: 0;
            transform: translate3d(0, 34px, 0) scale(0.97);
        }

        .orasi-video-slider.is-home-slider .orasi-video-thumb img {
            filter: brightness(0.72) saturate(0.9) contrast(1.02);
        }

        .orasi-video-slider.is-home-slider .orasi-video-thumb::after {
            background:
                linear-gradient(180deg, rgba(8, 10, 20, 0.34) 0%, rgba(8, 10, 20, 0.44) 34%, rgba(8, 10, 20, 0.9) 100%);
        }

        .orasi-video-slider.is-home-slider .orasi-video-card:hover .orasi-video-thumb img {
            filter: brightness(0.8) saturate(1) contrast(1.04);
        }

        .orasi-video-slide .orasi-video-thumb img {
            transform: translate3d(0, 22px, 0) scale(0.985);
            will-change: transform;
        }

        .orasi-video-slide.is-active .orasi-video-card {
            animation: orasiProfessorCardIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .orasi-video-slide.is-active .orasi-video-thumb img {
            animation: orasiPosterParallax 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .orasi-video-slide.is-active .col-xl-3:nth-child(1) .orasi-video-card,
        .orasi-video-slide.is-active .col-md-6:nth-child(1) .orasi-video-card {
            animation-delay: 0.06s;
        }

        .orasi-video-slide.is-active .col-xl-3:nth-child(2) .orasi-video-card,
        .orasi-video-slide.is-active .col-md-6:nth-child(2) .orasi-video-card {
            animation-delay: 0.16s;
        }

        .orasi-video-slide.is-active .col-xl-3:nth-child(3) .orasi-video-card,
        .orasi-video-slide.is-active .col-md-6:nth-child(3) .orasi-video-card {
            animation-delay: 0.26s;
        }

        .orasi-video-slide.is-active .col-xl-3:nth-child(4) .orasi-video-card,
        .orasi-video-slide.is-active .col-md-6:nth-child(4) .orasi-video-card {
            animation-delay: 0.36s;
        }

        .orasi-video-body {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 14px 16px 16px;
            min-height: 94px;
        }

        .orasi-video-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(47, 140, 246, 0.08);
            color: #205bb0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            width: fit-content;
            display: none;
        }

        .orasi-video-title {
            margin: 0;
            color: #18213a;
            font-size: 1.08rem;
            line-height: 1.35;
            display: none;
        }

        .orasi-video-meta {
            color: #667085;
            font-size: 0.86rem;
            line-height: 1.45;
        }

        .orasi-video-meta-line {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orasi-professor-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 55px rgba(31, 41, 55, 0.1);
        }

        .orasi-professor-poster {
            position: relative;
            isolation: isolate;
            width: 100%;
            aspect-ratio: 9 / 16;
            overflow: hidden;
            border-radius: 4px;
            background:
                linear-gradient(180deg, rgba(255, 204, 83, 0.38), transparent 28%),
                #f9aa28;
        }

        .orasi-professor-poster.is-full-overlay::before {
            opacity: 0;
        }

        .orasi-professor-poster.is-full-overlay .orasi-professor-poster-title,
        .orasi-professor-poster.is-full-overlay .orasi-professor-poster-footer {
            display: none;
        }

        .orasi-professor-poster::before {
            content: "";
            position: absolute;
            left: 4.5%;
            right: 4.5%;
            bottom: 12.2%;
            height: 43%;
            background: #32b70c;
            clip-path: polygon(0 38%, 100% 0, 100% 100%, 0 100%);
            z-index: 1;
        }

        .orasi-professor-poster-title {
            position: absolute;
            top: 4.7%;
            left: 6%;
            right: 6%;
            z-index: 4;
            color: #fff;
            font-size: 2.35rem;
            font-weight: 800;
            line-height: 0.95;
            letter-spacing: 0;
            text-align: center;
        }

        .orasi-professor-poster-campus {
            display: block;
            margin-top: 8px;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .orasi-professor-photo-wrap {
            position: absolute;
            left: 5%;
            right: 5%;
            bottom: 12.2%;
            height: 68%;
            z-index: 3;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .orasi-professor-photo-wrap.is-full-overlay {
            inset: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height: auto;
            align-items: stretch;
            justify-content: stretch;
        }

        .orasi-professor-photo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center bottom;
            filter: drop-shadow(0 16px 18px rgba(0, 0, 0, 0.16));
        }

        .orasi-professor-photo.is-full-overlay {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            max-width: none;
            max-height: none;
            object-fit: contain;
            object-position: center center;
            filter: none;
            z-index: 6;
        }

        .orasi-professor-placeholder {
            width: 72%;
            aspect-ratio: 1;
            border-radius: 999px;
            display: grid;
            place-items: center;
            margin-bottom: 12%;
            background: rgba(255, 255, 255, 0.24);
            color: #fff;
            font-size: 3rem;
            font-weight: 800;
        }

        .orasi-professor-poster-footer {
            position: absolute;
            left: 4.5%;
            right: 4.5%;
            bottom: 7.8%;
            min-height: 11%;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            text-align: center;
            color: #fff;
            background: linear-gradient(180deg, #287ed9 0%, #08335f 100%);
            box-shadow: 0 -6px 18px rgba(0, 0, 0, 0.12) inset;
        }

        .orasi-professor-footer-name {
            max-width: 100%;
            font-size: 0.82rem;
            line-height: 1.08;
            font-weight: 800;
        }

        .orasi-professor-footer-role {
            margin-top: 3px;
            font-size: 0.62rem;
            line-height: 1;
            font-weight: 800;
        }

        .orasi-professor-footer-field,
        .orasi-professor-footer-faculty {
            max-width: 100%;
            font-size: 0.54rem;
            line-height: 1.08;
            font-weight: 800;
        }

        .orasi-professor-footer-field {
            margin-top: 5px;
        }

        .orasi-professor-name {
            margin: 28px 0 0;
            color: #20242c;
            text-align: center;
            font-size: 1.65rem;
            line-height: 0.98;
            font-weight: 800;
            letter-spacing: 0;
            min-height: 2.94em;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orasi-professor-link {
            display: block;
            color: inherit;
            height: 100%;
        }

        .orasi-professor-link:hover {
            color: inherit;
        }

        .orasi-professor-card-meta {
            margin-top: 14px;
            display: grid;
            gap: 8px;
            min-height: 7.8em;
        }

        .orasi-professor-card-meta span {
            display: block;
            color: #667085;
            font-size: 0.92rem;
            line-height: 1.55;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orasi-professor-card-title {
            margin-top: 12px;
            color: #18213a;
            font-size: 0.96rem;
            line-height: 1.52;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 4.56em;
        }

        .orasi-professor-card-cta {
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 46px;
            border-radius: 14px;
            background: #eef4fb;
            border: 1px solid rgba(47, 92, 176, 0.12);
            color: #1f4f9e;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: transform 0.24s ease, background-color 0.24s ease, border-color 0.24s ease;
        }

        .orasi-professor-link:hover .orasi-professor-card-cta {
            transform: translateY(-1px);
            background: #e4edf8;
            border-color: rgba(47, 92, 176, 0.18);
        }

        .orasi-professor-archive {
            display: grid;
            gap: 18px;
        }

        .orasi-professor-year {
            overflow: hidden;
            border-radius: 28px;
            background: #ffffff;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 60px rgba(31, 41, 55, 0.08);
        }

        .orasi-professor-year-toggle {
            width: 100%;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 18px;
            padding: 24px 28px;
            background: transparent;
            border: 0;
            text-align: left;
        }

        .orasi-professor-year-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 94px;
            min-height: 64px;
            padding: 10px 16px;
            border-radius: 20px;
            background: #18213a;
            color: #fff;
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .orasi-professor-year-title {
            margin: 0;
            color: #18213a;
            font-size: 1.2rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .orasi-professor-year-copy {
            margin-top: 6px;
            color: #667085;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .orasi-professor-year-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            min-height: 52px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(24, 33, 58, 0.06);
            color: #18213a;
            font-size: 0.96rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .orasi-professor-year-panel {
            display: grid;
            grid-template-rows: 0fr;
            opacity: 0;
            padding: 0 28px;
            border-top: 1px solid rgba(31, 41, 55, 0.08);
            transition:
                grid-template-rows 0.42s cubic-bezier(0.22, 1, 0.36, 1),
                opacity 0.28s ease,
                padding 0.42s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .orasi-professor-year.is-open .orasi-professor-year-panel {
            grid-template-rows: 1fr;
            opacity: 1;
            padding: 0 28px 28px;
        }

        .orasi-professor-year-panel-inner {
            min-height: 0;
            overflow: hidden;
            padding-top: 0;
            transition: padding-top 0.42s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .orasi-professor-year.is-open .orasi-professor-year-panel-inner {
            padding-top: 24px;
        }

        .orasi-year-merge-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .orasi-professor-year-head {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px 16px 28px;
        }

        .orasi-professor-year-head .orasi-professor-year-toggle {
            flex: 1 1 auto;
            width: auto;
            min-width: 0;
            padding: 8px 0;
            grid-template-columns: auto 1fr;
        }

        .orasi-professor-year-head-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        .orasi-year-merge-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 48px;
            padding: 10px 18px;
            border-radius: 999px;
            background: #18213a;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            box-shadow: 0 12px 30px rgba(24, 33, 58, 0.18);
        }

        .orasi-year-merge-btn:hover {
            color: #fff;
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(24, 33, 58, 0.22);
        }

        .orasi-year-merge-btn--compact {
            min-height: 52px;
            padding: 8px 16px;
            font-size: 0.88rem;
            box-shadow: none;
        }

        .orasi-year-merge-btn--compact:hover {
            transform: none;
        }

        .orasi-archive-doc-group + .orasi-archive-doc-group {
            margin-top: 28px;
            padding-top: 28px;
            border-top: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-archive-doc-group-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .orasi-archive-doc-group-title {
            margin: 0;
            color: #18213a;
            font-size: 1.08rem;
            font-weight: 800;
        }

        .orasi-archive-doc-group-count {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(24, 33, 58, 0.06);
            color: #18213a;
            font-size: 0.9rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .orasi-archive-prof-doc {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            height: 100%;
        }

        .orasi-archive-prof-doc-head {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .orasi-archive-prof-doc-name {
            margin: 0;
            color: #18213a;
            font-size: 0.98rem;
            font-weight: 800;
            line-height: 1.4;
            overflow-wrap: anywhere;
            word-break: normal;
        }

        .orasi-archive-prof-doc-meta {
            margin: 0;
            color: #667085;
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .orasi-archive-prof-preview-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            align-items: start;
        }

        .orasi-archive-prof-preview-slot {
            min-width: 0;
        }

        .orasi-year-merge-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .orasi-year-merge-btn--presentasi {
            background: #4f2d7a;
        }

        .orasi-year-merge-btn--presentasi:hover {
            background: #3f2361;
        }

        .orasi-orator-slider-head {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }

        .orasi-orator-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 999px;
            background: rgba(24, 33, 58, 0.08);
            color: #18213a;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .orasi-orator-label::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #f6b234 0%, #2f8cf6 100%);
            box-shadow: 0 0 0 6px rgba(47, 140, 246, 0.08);
        }

        .orasi-orator-slider {
            position: relative;
            min-height: 0;
        }

        .orasi-orator-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translate3d(0, 26px, 0) scale(0.985);
            transition:
                opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                visibility 0s linear 0.8s;
        }

        .orasi-orator-slide.is-active {
            position: relative;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translate3d(0, 0, 0) scale(1);
            transition-delay: 0s, 0s, 0s;
            z-index: 2;
        }

        .orasi-orator-slide.is-leaving {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: visible;
            pointer-events: none;
            transform: translate3d(0, -18px, 0) scale(1.012);
            z-index: 1;
        }

        .orasi-orator-slide-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 26px;
        }

        .orasi-orator-slide-year {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #18213a;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
            text-align: center;
            opacity: 0;
            transform: translate3d(0, 18px, 0);
        }

        .orasi-orator-slide-year::before {
            content: none;
        }

        .orasi-orator-slide.is-active .orasi-orator-slide-year {
            animation: orasiYearReveal 0.95s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .orasi-orator-slide .orasi-professor-card {
            opacity: 0;
            transform: translate3d(0, 34px, 0) scale(0.97);
        }

        .orasi-orator-slide .orasi-professor-poster {
            transform: translate3d(0, 22px, 0) scale(0.985);
            will-change: transform;
        }

        .orasi-orator-slide.is-active .orasi-professor-card {
            animation: orasiProfessorCardIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .orasi-orator-slide.is-active .orasi-professor-poster {
            animation: orasiPosterParallax 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .orasi-orator-slide.is-active .col-xl-3:nth-child(1) .orasi-professor-card,
        .orasi-orator-slide.is-active .col-md-6:nth-child(1) .orasi-professor-card {
            animation-delay: 0.06s;
        }

        .orasi-orator-slide.is-active .col-xl-3:nth-child(2) .orasi-professor-card,
        .orasi-orator-slide.is-active .col-md-6:nth-child(2) .orasi-professor-card {
            animation-delay: 0.16s;
        }

        .orasi-orator-slide.is-active .col-xl-3:nth-child(3) .orasi-professor-card,
        .orasi-orator-slide.is-active .col-md-6:nth-child(3) .orasi-professor-card {
            animation-delay: 0.26s;
        }

        .orasi-orator-slide.is-active .col-xl-3:nth-child(4) .orasi-professor-card,
        .orasi-orator-slide.is-active .col-md-6:nth-child(4) .orasi-professor-card {
            animation-delay: 0.36s;
        }

        @keyframes orasiProfessorCardIn {
            from {
                opacity: 0;
                transform: translate3d(0, 34px, 0) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
        }

        @keyframes orasiYearReveal {
            from {
                opacity: 0;
                transform: translate3d(0, 18px, 0);
                letter-spacing: 0.12em;
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
                letter-spacing: 0;
            }
        }

        @keyframes orasiPosterParallax {
            from {
                transform: translate3d(0, 22px, 0) scale(0.985);
            }

            to {
                transform: translate3d(0, 0, 0) scale(1);
            }
        }

        .orasi-thumb,
        .orasi-avatar-placeholder {
            height: 280px;
            width: 100%;
        }

        .orasi-thumb {
            object-fit: cover;
            background: linear-gradient(135deg, #ece8df, #cfd4dc);
        }

        .orasi-avatar-placeholder {
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(var(--secondary-color-rgb), 0.92), rgba(14, 116, 144, 0.92));
            color: #fff;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .orasi-badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .orasi-badge-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(var(--secondary-color-rgb), 0.1);
            color: #203049;
            font-size: 13px;
            font-weight: 600;
        }

        .orasi-agenda-date {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 72px;
            padding: 12px 10px;
            border-radius: 18px;
            background: rgba(var(--secondary-color-rgb), 0.12);
            color: #102038;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
        }

        .orasi-empty {
            padding: 26px 28px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px dashed rgba(31, 41, 55, 0.18);
            color: #5e6470;
        }

        .orasi-archive-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 10px 30px rgba(31, 41, 55, 0.06);
            color: #18213a;
            font-weight: 600;
        }

        .orasi-section-intro {
            max-width: 640px;
            color: #636a76;
        }

        .orasi-section-actions {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .orasi-filter-context {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 34px;
            padding: 24px 26px;
            border-radius: 26px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(247, 250, 255, 0.98) 100%);
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 48px rgba(31, 41, 55, 0.08);
        }

        .orasi-filter-context-kicker {
            margin-bottom: 10px;
            color: #8b6a22;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .orasi-filter-context-title {
            margin: 0;
            color: #18213a;
            font-size: 1.28rem;
            font-weight: 800;
            line-height: 1.35;
        }

        .orasi-filter-context-copy {
            margin: 10px 0 0;
            max-width: 760px;
            color: #667085;
            font-size: 0.96rem;
            line-height: 1.7;
        }

        .orasi-filter-context-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .orasi-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
            color: #18213a;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .orasi-filter-chip strong {
            font-weight: 800;
        }

        .orasi-section-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 50px;
            padding: 0 22px;
            border-radius: 999px;
            background: #18213a;
            border: 1px solid rgba(24, 33, 58, 0.18);
            color: #fff;
            font-size: 0.88rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: transform 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
            box-shadow: 0 16px 34px rgba(24, 33, 58, 0.16);
        }

        .orasi-section-link:hover {
            color: #fff;
            transform: translateY(-2px);
            background: #243253;
            box-shadow: 0 20px 40px rgba(24, 33, 58, 0.22);
        }

        .orasi-surface-section {
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .orasi-professor-meaning-section {
            position: relative;
            overflow: hidden;
            background: #eef1f4;
        }

        .orasi-professor-meaning-panel {
            position: relative;
            overflow: hidden;
            min-height: 430px;
            background: #18213a;
            box-shadow: 0 28px 80px rgba(15, 19, 34, 0.18);
        }

        .orasi-professor-meaning-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(8, 12, 24, 0.82) 0%, rgba(10, 15, 29, 0.72) 50%, rgba(10, 15, 29, 0.42) 100%),
                radial-gradient(circle at top left, rgba(246, 178, 52, 0.20), transparent 28%),
                radial-gradient(circle at right center, rgba(47, 140, 246, 0.16), transparent 34%);
            z-index: 1;
        }

        .orasi-professor-meaning-content {
            position: relative;
            z-index: 2;
            max-width: 980px;
        }

        .orasi-professor-meaning-kicker {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 16px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .orasi-professor-meaning-kicker i {
            color: #f6b234;
        }

        .orasi-professor-meaning-title {
            margin: 0;
            color: #fff;
            font-size: clamp(2rem, 3vw, 3.35rem);
            font-weight: 800;
            line-height: 1.05;
        }

        .orasi-professor-meaning-copy {
            margin: 20px 0 0;
            max-width: 920px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 1.02rem;
            line-height: 1.8;
        }

        a.btn-main.orasi-professor-meaning-button,
        a.btn-main.orasi-professor-meaning-button:active,
        a.btn-main.orasi-professor-meaning-button:focus,
        a.btn-main.orasi-professor-meaning-button:visited {
            background: #f6b234 !important;
            border-color: #f6b234 !important;
            color: #18213a !important;
            box-shadow: 0 16px 34px rgba(246, 178, 52, 0.28);
        }

        a.btn-main.orasi-professor-meaning-button:hover {
            background: #ffc857 !important;
            border-color: #ffc857 !important;
            color: #18213a !important;
            box-shadow: 0 18px 38px rgba(246, 178, 52, 0.34);
        }

        .orasi-orator-section {
            background: #eef1f4;
        }

        .orasi-orasi-section {
            position: relative;
            background: #0f1322;
            overflow: hidden;
        }

        .orasi-orasi-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--orasi-orasi-bg);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            opacity: 0.34;
            transform: scale(1.02);
            filter: saturate(0.88) brightness(0.72);
        }

        .orasi-orasi-section::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(8, 12, 24, 0.8) 0%, rgba(10, 15, 29, 0.72) 24%, rgba(11, 17, 32, 0.82) 100%),
                radial-gradient(circle at top left, rgba(246, 178, 52, 0.12), transparent 28%),
                radial-gradient(circle at right center, rgba(47, 140, 246, 0.12), transparent 32%);
        }

        .orasi-orasi-section > .container {
            position: relative;
            z-index: 1;
        }

        .orasi-section-lead {
            max-width: 720px;
            margin: 14px auto 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 1.02rem;
            line-height: 1.75;
        }

        .orasi-banner-card {
            position: relative;
            min-height: 0;
            overflow: hidden;
            border-radius: 30px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.02) 100%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 26px 84px rgba(4, 8, 18, 0.3);
            height: 100%;
            transition: transform 0.28s ease, box-shadow 0.28s ease;
            backdrop-filter: blur(16px);
        }

        .orasi-banner-card-link {
            display: block;
            height: 100%;
            color: inherit;
        }

        .orasi-banner-card-link:hover {
            color: inherit;
        }

        .orasi-banner-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 34px 92px rgba(4, 8, 18, 0.36);
        }

        .orasi-banner-thumb {
            position: relative;
            overflow: hidden;
            min-height: 0;
            aspect-ratio: 16 / 10;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            background:
                linear-gradient(145deg, rgba(7, 12, 28, 0.62) 0%, rgba(7, 12, 28, 0.34) 42%, rgba(7, 12, 28, 0.9) 100%),
                linear-gradient(135deg, var(--agenda-ink, #18213a) 0%, var(--agenda-accent, #f6b234) 150%);
        }

        .orasi-banner-thumb::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--agenda-image, none);
            background-position: center;
            background-size: cover;
            opacity: 0.64;
            transform: scale(1.04);
        }

        .orasi-banner-thumb::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 18% 18%, rgba(255, 255, 255, 0.14), transparent 22%),
                linear-gradient(180deg, rgba(10, 16, 30, 0.22) 0%, rgba(10, 16, 30, 0.34) 34%, rgba(10, 16, 30, 0.92) 100%);
        }

        .orasi-banner-thumb-top,
        .orasi-banner-thumb-bottom {
            position: relative;
            z-index: 1;
        }

        .orasi-banner-thumb-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .orasi-banner-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .orasi-banner-date-badge {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 68px;
            padding: 9px 11px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(10px);
        }

        .orasi-banner-date-day {
            color: #fff;
            font-size: 1.55rem;
            font-weight: 900;
            line-height: 1;
        }

        .orasi-banner-date-month {
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .orasi-banner-thumb-bottom {
            display: block;
        }

        .orasi-banner-thumb-code {
            position: relative;
            z-index: 1;
            display: inline-flex;
            flex-direction: column;
            gap: 3px;
            min-width: 110px;
            padding: 12px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
        }

        .orasi-banner-thumb-code strong {
            display: block;
            font-size: 2.3rem;
            font-weight: 900;
            line-height: 0.92;
            letter-spacing: -0.03em;
        }

        .orasi-banner-thumb-code span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-banner-thumb-caption {
            position: relative;
            z-index: 1;
            text-align: right;
            min-width: 150px;
        }

        .orasi-banner-thumb-caption span {
            display: block;
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-banner-thumb-caption strong {
            display: block;
            margin-top: 6px;
            color: #fff;
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.05;
        }

        .orasi-banner-footer-panel {
            z-index: 1;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(8, 12, 24, 0.18) 0%, rgba(8, 12, 24, 0.42) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(10px);
        }

        .orasi-banner-body {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 0;
            background: transparent;
            min-width: 0;
        }

        .orasi-banner-head {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            flex-wrap: nowrap;
        }

        .orasi-banner-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.92);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .orasi-banner-count {
            color: #fff;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
            white-space: nowrap;
        }

        .orasi-banner-title {
            margin: 0;
            color: #fff;
            font-size: 1.18rem;
            font-weight: 800;
            line-height: 1.3;
            text-align: left;
            text-shadow: 0 8px 20px rgba(0, 0, 0, 0.32);
            max-width: 100%;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orasi-banner-series {
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.4;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .orasi-banner-meta {
            display: none;
        }

        .orasi-banner-meta-label {
            color: rgba(255, 255, 255, 0.68);
            font-size: 0.78rem;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .orasi-banner-meta-item {
            display: block;
            padding-left: 14px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 0.92rem;
            font-weight: 600;
            line-height: 1.58;
            text-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
            position: relative;
        }

        .orasi-banner-meta-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.72em;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            color: #f6b234;
            background: #f6b234;
        }

        .orasi-banner-footer {
            display: none;
        }

        @media (min-width: 1200px) {
            .orasi-banner-card {
                align-items: stretch;
            }
        }

        @media (max-width: 991.98px) {
            .orasi-banner-card {
                min-height: auto;
            }

            .orasi-banner-thumb {
                aspect-ratio: 16 / 11;
            }

            .orasi-banner-footer-panel {
                grid-template-columns: auto minmax(0, 1fr);
                align-items: start;
            }

            .orasi-banner-thumb-caption {
                grid-column: 1 / -1;
                text-align: left;
                min-width: 0;
            }
        }

        @media (max-width: 575.98px) {
            .orasi-page-banner {
                min-height: 300px;
            }

            .orasi-page-banner-shell {
                padding: calc(var(--orasi-header-height) + 48px) 0 48px;
            }

            .orasi-page-banner-title {
                font-size: clamp(2rem, 10vw, 2.7rem);
            }

            .orasi-page-banner-copy {
                font-size: 0.95rem;
                line-height: 1.65;
            }

            .orasi-banner-thumb {
                padding: 18px;
                min-height: 320px;
                aspect-ratio: auto;
            }

            .orasi-banner-thumb-bottom {
                display: block;
            }

            .orasi-banner-footer-panel {
                grid-template-columns: 1fr;
                align-items: flex-start;
                gap: 14px;
            }

            .orasi-banner-head {
                flex-wrap: wrap;
            }

            .orasi-banner-title {
                font-size: 1.2rem;
                -webkit-line-clamp: 2;
            }

            .orasi-banner-thumb-caption {
                grid-column: auto;
                text-align: left;
            }
        }

        .orasi-stats-section {

            background:
                linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
        }

        .orasi-stat-kpi {
            padding: 26px 26px 22px;
            border-radius: 22px;
            background: #fff;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 16px 40px rgba(31, 41, 55, 0.08);
            height: 100%;
        }

        .orasi-stat-kpi-value {
            margin: 0;
            font-size: 3.1rem;
            line-height: 0.95;
            color: #18213a;
        }

        .orasi-stat-kpi-label {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid rgba(31, 41, 55, 0.12);
            color: #18213a;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .orasi-stat-kpi-copy {
            margin: 14px 0 0;
            color: #667085;
            font-size: 0.98rem;
            line-height: 1.6;
        }

        .orasi-stat-panel {
            padding: 28px;
            border-radius: 24px;
            background: #fff;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 50px rgba(31, 41, 55, 0.08);
            height: 100%;
        }

        .orasi-stat-bars {
            display: grid;
            gap: 16px;
        }

        .orasi-stat-bar-row {
            display: grid;
            grid-template-columns: minmax(0, 200px) minmax(0, 1fr) auto;
            gap: 14px;
            align-items: center;
        }

        .orasi-stat-bar-label {
            color: #18213a;
            font-weight: 700;
            line-height: 1.25;
        }

        .orasi-stat-bar-track {
            height: 12px;
            border-radius: 999px;
            background: #e8edf4;
            overflow: hidden;
        }

        .orasi-stat-bar-fill {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #f6b234 0%, #2f8cf6 100%);
        }

        .orasi-stat-bar-total {
            min-width: 36px;
            color: #18213a;
            font-weight: 800;
            text-align: right;
        }

        .orasi-year-grid {
            display: grid;
            gap: 14px;
        }

        .orasi-year-item {
            display: grid;
            grid-template-columns: 88px minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
            padding: 18px 20px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-year-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 54px;
            border-radius: 16px;
            background: #18213a;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .orasi-year-title {
            margin: 0 0 4px;
            color: #18213a;
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .orasi-year-meta {
            color: #667085;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .orasi-year-total {
            color: #18213a;
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
            text-align: right;
        }

        .orasi-stat-note {
            padding: 20px 22px;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.96) 0%, rgba(239, 246, 255, 0.92) 100%);
            border: 1px solid rgba(47, 140, 246, 0.08);
            color: #526072;
            line-height: 1.65;
        }

        .orasi-chart-card {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 28px;
            border-radius: 24px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 50px rgba(31, 41, 55, 0.08);
            transition: transform 0.28s ease, box-shadow 0.28s ease;
        }

        .orasi-chart-card::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #f6b234 0%, #2f8cf6 50%, #8b5cf6 100%);
        }

        .orasi-chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 60px rgba(31, 41, 55, 0.12);
        }

        .orasi-chart-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .orasi-chart-head h4 {
            margin: 0;
            color: #18213a;
            font-size: 1.2rem;
            line-height: 1.15;
        }

        .orasi-chart-subtitle {
            margin-top: 6px;
            color: #667085;
            font-size: 0.95rem;
            line-height: 1.55;
        }

        .orasi-chart-wrap {
            position: relative;
            width: 100%;
        }

        .orasi-chart-wrap-lg {
            min-height: 360px;
        }

        .orasi-chart-wrap-md {
            min-height: 320px;
        }

        .orasi-chart-wrap-donut {
            height: 320px;
            min-height: 320px;
            overflow: hidden;
        }

        .orasi-chart-wrap-xl {
            min-height: 440px;
        }

        .orasi-chart-wrap canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
        }

        .orasi-stat-filter {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .orasi-stat-filter-label {
            color: #667085;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .orasi-stat-select {
            min-width: 180px;
            height: 48px;
            padding-left: 16px;
            padding-right: 44px;
            border-radius: 14px;
            border: 1px solid rgba(31, 41, 55, 0.12);
            background-color: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .orasi-stat-select:focus {
            border-color: rgba(47, 140, 246, 0.45);
            box-shadow: 0 0 0 0.2rem rgba(47, 140, 246, 0.08);
        }

        .orasi-stat-filter-chips {
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }

        .orasi-stat-filter-chip {
            appearance: none;
            border: 1px solid rgba(31, 41, 55, 0.08);
            background: rgba(255, 255, 255, 0.92);
            color: #344054;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 0.92rem;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 10px 24px rgba(31, 41, 55, 0.06);
            transition: all 0.24s ease;
        }

        .orasi-stat-filter-chip:hover {
            transform: translateY(-1px);
            color: #18213a;
        }

        .orasi-stat-filter-chip.is-active {
            background: #18213a;
            color: #fff;
            border-color: #18213a;
            box-shadow: 0 14px 28px rgba(24, 33, 58, 0.16);
        }

        .orasi-stat-insight {
            height: 100%;
            padding: 22px 24px;
            border-radius: 22px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 50px rgba(31, 41, 55, 0.08);
        }

        .orasi-stat-insight-label {
            margin-bottom: 10px;
            color: #667085;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-stat-insight-value {
            color: #18213a;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        .orasi-stat-insight-copy {
            margin-top: 10px;
            color: #667085;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .orasi-stat-caption-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .orasi-stat-caption-card {
            padding: 18px 20px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-stat-caption-card span {
            display: block;
            color: #667085;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-stat-caption-card strong {
            display: block;
            margin-top: 8px;
            color: #18213a;
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .orasi-stat-gender-grid {
            display: grid;
            gap: 14px;
        }

        .orasi-stat-gender-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-stat-gender-label {
            color: #18213a;
            font-weight: 700;
            line-height: 1.3;
        }

        .orasi-stat-gender-value {
            color: #18213a;
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
        }

        @media (max-width: 1399.98px) {
            .orasi-professor-poster-title {
                font-size: 2rem;
            }

            .orasi-professor-poster-campus {
                font-size: 0.85rem;
            }

            .orasi-professor-footer-name {
                font-size: 0.74rem;
            }

            .orasi-professor-footer-role {
                font-size: 0.56rem;
            }

            .orasi-professor-footer-field,
            .orasi-professor-footer-faculty {
                font-size: 0.49rem;
            }

            .orasi-professor-name {
                font-size: 1.45rem;
            }
        }

        @media (max-width: 1199.98px) {
            .orasi-professor-meaning-panel {
                min-height: 390px;
            }

            .orasi-professor-poster-title {
                font-size: 2.35rem;
            }

            .orasi-professor-poster-campus {
                font-size: 1rem;
            }

            .orasi-professor-footer-name {
                font-size: 0.82rem;
            }

            .orasi-professor-footer-role {
                font-size: 0.62rem;
            }

            .orasi-professor-footer-field,
            .orasi-professor-footer-faculty {
                font-size: 0.54rem;
            }
        }

        @media (max-width: 575.98px) {
            .orasi-professor-meaning-panel {
                min-height: 460px;
                border-radius: 24px;
            }

            .orasi-professor-meaning-title {
                font-size: 2rem;
            }

            .orasi-professor-meaning-copy {
                font-size: 0.96rem;
            }

            .orasi-professor-card {
                padding: 16px;
            }

            .orasi-professor-poster-title {
                font-size: 2.05rem;
            }

            .orasi-professor-poster-campus {
                font-size: 0.86rem;
            }

            .orasi-professor-name {
                font-size: 1.35rem;
            }

            .orasi-stat-bar-row,
            .orasi-year-item,
            .orasi-stat-caption-grid {
                grid-template-columns: 1fr;
            }

            .orasi-professor-year-toggle {
                grid-template-columns: 1fr;
                justify-items: start;
                padding: 20px;
            }

            .orasi-professor-year-head {
                flex-direction: column;
                align-items: stretch;
                padding: 0;
            }

            .orasi-professor-year-head .orasi-professor-year-toggle {
                width: 100%;
                padding: 20px;
            }

            .orasi-professor-year-head-actions {
                flex-direction: column;
                align-items: stretch;
                padding: 0 20px 20px;
            }

            .orasi-year-merge-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .orasi-year-merge-btn--compact,
            .orasi-professor-year-head-actions .orasi-professor-year-count {
                width: 100%;
                justify-content: center;
            }

            .orasi-professor-year-panel {
                padding: 0 20px 20px;
            }

            .orasi-year-total,
            .orasi-stat-bar-total {
                text-align: left;
            }
        }

        @media (max-width: 991.98px) {
            .orasi-home {
                --orasi-header-height: 64px;
            }

            .orasi-hero {
                min-height: auto;
            }

            .orasi-hero-shell {
                min-height: 100vh;
                min-height: 100svh;
                padding: calc(var(--orasi-header-height) + 54px) 0 72px;
            }

            .orasi-hero-title {
                max-width: 100%;
                font-size: clamp(2.6rem, 6.8vw, 4rem);
            }

            .orasi-hero-aside {
                margin-left: 0;
                max-width: 100%;
                margin-top: 36px;
            }

            .orasi-hero-counter {
                max-width: 100%;
            }

            .orasi-hero-video iframe {
                width: 100vw;
                height: 56.25vw;
                min-width: 177.78vh;
                min-width: 177.78svh;
                min-height: 100vh;
                min-height: 100svh;
                transform: translate(-50%, -50%) scale(1.1);
            }

            .orasi-orator-slider-head,
            .orasi-orator-slide-meta {
                align-items: flex-start;
                flex-direction: column;
            }

            .orasi-filter-context {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 767.98px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 42px) 0 56px;
            }

            .orasi-hero-title {
                font-size: clamp(2.3rem, 10vw, 3.5rem);
                line-height: 1;
            }

            .orasi-hero-actions .btn-main,
            .orasi-hero-actions .btn-line {
                width: 100%;
                text-align: center;
            }

            .orasi-orator-slide-year {
                font-size: 1.2rem;
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 575.98px) {
            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 34px) 0 48px;
            }

            .orasi-hero-title {
                font-size: clamp(2.1rem, 10.5vw, 3rem);
                letter-spacing: -0.04em;
            }

            .orasi-hero-counter {
                max-width: 100%;
                padding: 20px 22px;
            }

            .orasi-hero-video iframe {
                transform: translate(-50%, -50%) scale(1.14);
            }

            .orasi-orator-label {
                padding: 10px 14px;
                font-size: 0.82rem;
            }

            .orasi-filter-context {
                padding: 20px;
            }
        }

        @media (max-width: 390px) {
            .orasi-home {
                --orasi-header-height: 58px;
            }

            .orasi-hero {
                min-height: 100vh;
                min-height: 100svh;
            }

            .orasi-hero-shell {
                padding: calc(var(--orasi-header-height) + 30px) 0 42px;
            }

            .orasi-hero-title {
                font-size: clamp(1.9rem, 10vw, 2.7rem);
            }

            .orasi-hero-actions {
                gap: 10px;
            }
        }
    </style>
@endpush

@php
    use Illuminate\Support\Str;

    $pageMode = $pageMode ?? 'home';
    $isHomePage = $pageMode === 'home';
    $heroTitle = 'Orasi Ilmiah Guru Besar Universitas Mulawarman.';
    $pageHeading = $pageTitle ?? 'Portal Orasi';
    $pageSummary = $pageDescription ?? 'Informasi publik Orasi Ilmiah Guru Besar Universitas Mulawarman.';
    $showHero = $isHomePage;
    $showGuruBesar = in_array($pageMode, ['home', 'guru-besar'], true);
    $showDaftarOrasi = in_array($pageMode, ['home', 'daftar-orasi'], true);
    $showVideoOrasi = in_array($pageMode, ['home', 'video-orasi'], true);
    $showDokumenOrasi = in_array($pageMode, ['home', 'dokumen-orasi'], true);
    $showStatistik = in_array($pageMode, ['home', 'statistik'], true);
    $sectionPageIntros = [
        'guru-besar' => [
            'copy' => 'Halaman ini menyajikan himpunan profil guru besar yang telah terhubung dengan agenda orasi ilmiah dan dipublikasikan sebagai arsip akademik Universitas Mulawarman.',
            'metrics' => [
                ['value' => $stats['guru_besar'], 'label' => 'Total Guru Besar', 'copy' => 'Jumlah guru besar yang tercatat dan ditayangkan pada arsip publik portal.'],
                ['value' => $stats['fakultas_terlibat'], 'label' => 'Fakultas Terlibat', 'copy' => 'Jumlah fakultas yang telah terwakili dalam publikasi orasi ilmiah.'],
                ['value' => $archiveYears->count(), 'label' => 'Tahun Arsip', 'copy' => 'Rentang tahun dokumentasi yang telah tersedia pada portal.'],
            ],
        ],
        'daftar-orasi' => [
            'copy' => 'Halaman ini menghimpun agenda dan arsip orasi ilmiah berdasarkan kegiatan resmi yang telah diterbitkan oleh Universitas Mulawarman.',
            'metrics' => [
                ['value' => $stats['orasi_ilmiah'], 'label' => 'Total Agenda', 'copy' => 'Jumlah agenda yang telah dipublikasikan atau diarsipkan pada portal.'],
                ['value' => ($archiveYears->first() ?? '-') , 'label' => 'Tahun Terbaru', 'copy' => 'Periode publikasi terkini yang telah masuk ke dalam arsip agenda.'],
                ['value' => ($stats['guru_besar'] ?? 0), 'label' => 'Orator Terhubung', 'copy' => 'Jumlah guru besar yang telah ditautkan dengan agenda orasi ilmiah.'],
            ],
        ],
        'video-orasi' => [
            'copy' => 'Halaman ini memuat dokumentasi audiovisual orasi ilmiah yang terhubung dari kanal resmi dan disajikan sebagai bagian dari arsip akademik.',
            'metrics' => [
                ['value' => $stats['video_orasi'], 'label' => 'Video Tersedia', 'copy' => 'Jumlah video orasi yang telah terhubung dengan data guru besar.'],
                ['value' => $archiveYears->count(), 'label' => 'Tahun Dokumentasi', 'copy' => 'Jumlah tahun arsip yang telah memiliki dokumentasi video pada portal.'],
                ['value' => $stats['fakultas_terlibat'], 'label' => 'Fakultas Terekam', 'copy' => 'Sebaran fakultas yang tercakup dalam dokumentasi video yang dipublikasikan.'],
            ],
        ],
        'dokumen-orasi' => [
            'copy' => 'Halaman ini menyediakan naskah orasi, bahan presentasi, dan dokumen pendukung lain yang telah dipublikasikan sebagai rujukan akademik.',
            'metrics' => [
                ['value' => $stats['dokumen_orasi'], 'label' => 'Dokumen Aktif', 'copy' => 'Jumlah dokumen yang telah tersedia dan dapat diakses melalui portal publik.'],
                ['value' => $stats['orasi_ilmiah'], 'label' => 'Agenda Bersumber', 'copy' => 'Jumlah agenda yang menjadi dasar sumber dokumentasi pada halaman ini.'],
                ['value' => $stats['guru_besar'], 'label' => 'Guru Besar Terkait', 'copy' => 'Jumlah orator yang telah memiliki naskah, slide, atau piagam pada portal.'],
            ],
        ],
        'statistik' => [
            'copy' => '',
            'metrics' => [
                ['value' => $activeStatSummary['total_guru_besar'] ?? 0, 'label' => 'Guru Besar Aktif', 'copy' => ''],
                ['value' => $activeStatSummary['total_orasi'] ?? 0, 'label' => 'Arsop orasi', 'copy' => ''],
                ['value' => $activeStatSummary['total_video_orasi'] ?? 0, 'label' => 'Video Aktif', 'copy' => ''],
            ],
        ],
    ];
    $currentSectionIntro = $sectionPageIntros[$pageMode] ?? null;

    if ($pageMode === 'guru-besar' && ! $isHomePage && ($activeOrasiFilter ?? null) && ($activeOrasiFilterStats ?? null)) {
        $currentSectionIntro = [
            'copy' => 'Halaman ini menampilkan guru besar yang terhubung pada agenda orasi ilmiah terpilih, sehingga penelusuran dapat langsung difokuskan pada satu konteks kegiatan akademik.',
            'metrics' => [
                ['value' => ($activeOrasiFilterStats['guru_besar'] ?? 0), 'label' => 'Guru Besar Terfilter', 'copy' => 'Jumlah guru besar yang terhubung pada agenda orasi ilmiah yang sedang dipilih.'],
                ['value' => ($activeOrasiFilterStats['fakultas_terlibat'] ?? 0), 'label' => 'Fakultas Terwakili', 'copy' => 'Jumlah fakultas yang tercakup dalam agenda orasi ilmiah yang dipilih.'],
                ['value' => $activeOrasiFilterStats['tahun'] ?? '-', 'label' => 'Tahun Agenda', 'copy' => 'Tahun pelaksanaan agenda orasi ilmiah yang sedang menjadi fokus penelusuran.'],
            ],
        ];
    }
@endphp

<div class="no-bottom no-top orasi-home" id="content">
    <div id="top"></div>

    @if ($showHero)
        <section id="beranda" class="orasi-hero text-light position-relative">
            <div class="orasi-hero-video" data-orasi-lazy-youtube="{{ $heroYoutubeEmbedUrl }}" data-orasi-youtube-title="Video hero Orasi Ilmiah">
            </div>
            <div class="container position-relative z-2 orasi-hero-shell">
                <div class="row align-items-center gx-5">
                    <div class="col-lg-12">
                        <div class="subtitle s2 mb-4">Portal Resmi Orasi Guru Besar</div>
                    </div>
                    <div class="col-lg-8 mb-sm-30">
                        <h1 class="slider-title orasi-hero-title">{{ $heroTitle }}</h1>
                    </div>
                    <div class="col-lg-4">
                        <div class="orasi-hero-aside">
                            <div class="orasi-hero-counter">
                                <div class="orasi-hero-counter-label">Total Guru Besar Universitas Mulawarman</div>
                                <h2 class="orasi-hero-counter-value">
                                    <span class="timer" data-to="{{ $stats['guru_besar'] }}" data-speed="2200"></span>
                                </h2>
                                <!-- <p class="orasi-hero-counter-copy">
                                    Total guru besar Universitas Mulawarman
                                </p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="orasi-page-banner has-video text-light" style="--orasi-page-banner-bg: url('{{ $heroBackground }}');">
            <div class="orasi-page-banner-video" aria-hidden="true">
                @if ($heroYoutubeEmbedUrl)
                    <iframe
                        src="{{ $heroYoutubeEmbedUrl }}"
                        title="Video latar Portal Orasi"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        loading="lazy"
                        tabindex="-1"
                    ></iframe>
                @else
                    <video autoplay muted loop playsinline preload="metadata">
                        <source src="{{ $heroVideoSource }}" type="video/webm">
                    </video>
                @endif
            </div>
            <div class="container position-relative text-center orasi-page-banner-shell">
                <div class="orasi-page-banner-kicker">Portal Orasi Unmul</div>
                <h1 class="orasi-page-banner-title">{{ $pageHeading }}</h1>
                <p class="orasi-page-banner-copy">{{ $pageSummary }}</p>
            </div>
        </section>
    @endif

    @if ($isHomePage)
        <section id="tentang-guru-besar" class="orasi-professor-meaning-section pt60 no-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="padding60 sm-padding40 rounded-30 text-light orasi-professor-meaning-panel orasi-professor-meaning-panel--lazy wow fadeInUp" data-orasi-lazy-bg="{{ asset('foto-gor-27.png') }}">
                            <div class="orasi-professor-meaning-content">
                                <div class="orasi-professor-meaning-kicker wow fadeInUp mb-3">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    Guru Besar Universitas Mulawarman
                                </div>
                                <h2 class="orasi-professor-meaning-title mb20 wow fadeInUp" data-wow-delay=".2s">
                                    Puncak kepakaran, kecendekiaan, dan tanggung jawab akademik.
                                </h2>
                                <p class="orasi-professor-meaning-copy wow fadeInUp" data-wow-delay=".3s">
                                    Guru Besar adalah jabatan fungsional tertinggi bagi dosen yang masih mengajar di lingkungan satuan pendidikan tinggi berdasarkan pengakuan kepakaran dan kecendikiaan dalam suatu disiplin ilmu pengetahuan, teknologi, seni, atau humaniora sebagaimana diatur dalam Pasal 1 Undang-Undang Nomor 14 Tahun 2005 tentang Guru dan Dosen.
                                </p>
                                <p class="orasi-professor-meaning-copy wow fadeInUp" data-wow-delay=".4s">
                                    Guru Besar mempunyai tanggung jawab yang ditunjukkan melalui kecendikiaan dan kepakaran di bidang keilmuannya, mengembangkan serta menjaga nilai-nilai akademik, berkontribusi dalam pengembangan keunggulan institusi, dan diharapkan memiliki kapasitas untuk mengembangkan konsep serta pemikiran keilmuan masa depan bagi penyelesaian permasalahan bangsa Indonesia dan dunia.
                                </p>
                                <a class="btn-main orasi-professor-meaning-button mt-4 wow fadeInUp" data-wow-delay=".5s" href="#guru-besar">Lihat Guru Besar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @php
    $oratorSlides = $latestOrators
            ->groupBy(fn ($guru) => (string) ($guru->archiveYear() ?: 'Tanpa Tahun'))
            ->sortKeysDesc()
            ->flatMap(function ($gurus, $year) {
                return $gurus->values()->chunk(4)->map(function ($chunk, $index) use ($year) {
                    return [
                        'year' => $year,
                        'key' => $year . '-' . $index,
                        'items' => $chunk,
                    ];
                });
            })
            ->values();

        $videoSlides = $latestVideos
            ->groupBy(fn ($video) => (string) ($video->archiveYear() ?: 'Tanpa Tahun'))
            ->sortKeysDesc()
            ->flatMap(function ($videos, $year) {
                return $videos->values()->chunk(4)->map(function ($chunk, $index) use ($year) {
                    return [
                        'year' => $year,
                        'key' => $year . '-video-' . $index,
                        'items' => $chunk,
                    ];
                });
            })
            ->values();

        $videosByYear = $latestVideos
            ->groupBy(fn ($video) => (string) ($video->archiveYear() ?: 'Tanpa Tahun'))
            ->sortKeysDesc();

        $documentsByYear = $documentItems
            ->groupBy(fn ($item) => (string) ($item->archiveYear() ?: 'Tanpa Tahun'))
            ->sortKeysDesc();

        $documentCards = $documentItems
            ->flatMap(function ($item) {
                $year = (string) ($item->archiveYear() ?: 'Tanpa Tahun');
                $faculty = $item->displayFakultas();
                $cards = collect();

                if ($item->file_orasi_path) {
                    $cards->push([
                        'year' => $year,
                        'person' => $item->nama,
                        'faculty' => $faculty,
                        'judul' => $item->judul_orasi ?: ($item->orasiIlmiah?->judul ?? 'Orasi Ilmiah Guru Besar'),
                        'file' => $item->file_orasi_path,
                        'filename' => basename($item->file_orasi_path),
                        'extension' => strtoupper(pathinfo($item->file_orasi_path, PATHINFO_EXTENSION) ?: 'FILE'),
                        'label' => 'Naskah',
                        'watermark' => 'NASKAH',
                        'chip' => 'Manuskrip',
                        'icon' => 'fa-solid fa-file-lines',
                        'chipIcon' => 'fa-regular fa-file-lines',
                        'accent' => 'open-blue',
                        'variant' => 'manuscript',
                    ]);
                }

                if ($item->ppt_path) {
                    $cards->push([
                        'year' => $year,
                        'person' => $item->nama,
                        'faculty' => $faculty,
                        'judul' => $item->judul_orasi ?: ($item->orasiIlmiah?->judul ?? 'Orasi Ilmiah Guru Besar'),
                        'file' => $item->ppt_path,
                        'filename' => basename($item->ppt_path),
                        'extension' => strtoupper(pathinfo($item->ppt_path, PATHINFO_EXTENSION) ?: 'FILE'),
                        'label' => 'Presentasi',
                        'watermark' => 'PRESENTASI',
                        'chip' => 'Slide Deck',
                        'icon' => 'fa-solid fa-file-powerpoint',
                        'chipIcon' => 'fa-solid fa-chalkboard-user',
                        'accent' => 'open-purple',
                        'variant' => 'presentation',
                    ]);
                }

                return $cards;
            })
            ->values();

        $documentSlides = $documentCards
            ->groupBy('year')
            ->sortKeysDesc()
            ->flatMap(function ($cards, $year) {
                return $cards->values()->chunk(4)->map(function ($chunk, $index) use ($year) {
                    return [
                        'year' => $year,
                        'key' => $year . '-document-' . $index,
                        'items' => $chunk,
                    ];
                });
            })
            ->values();

        $documentCountByYear = $documentCards
            ->groupBy('year')
            ->map(fn ($cards) => $cards->count());

        $documentYearSlug = static fn (string $year): string => $year === 'Tanpa Tahun' ? 'tanpa-tahun' : $year;

    @endphp

    @if ($showGuruBesar)
    <section id="guru-besar" class="orasi-orator-section pt90 pb90">
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-10 col-xl-8">
                    <h2 class="wow fadeInUp mb-0" data-wow-delay=".1s">Daftar Guru Besar Orasi Ilmiah</h2>
                </div>
            </div>

            @if ($pageMode === 'guru-besar')
                @php
                    $oratorsByYear = $latestOrators
                        ->groupBy(fn ($guru) => (string) ($guru->archiveYear() ?: 'Tanpa Tahun'))
                        ->sortKeysDesc();
                @endphp

                @if (($activeOrasiFilter ?? null) && ($activeOrasiFilterStats ?? null))
                    <div class="orasi-filter-context">
                        <div>
                            <div class="orasi-filter-context-kicker">Filter Agenda Aktif</div>
                            <h3 class="orasi-filter-context-title">{{ $activeOrasiFilter->judulLengkap }}</h3>
                            <p class="orasi-filter-context-copy">
                                Daftar guru besar di bawah ini telah difokuskan pada agenda orasi ilmiah yang dipilih dari halaman agenda, sehingga penelusuran langsung mengarah pada kelompok orator yang relevan.
                            </p>
                            <div class="orasi-filter-context-meta">
                                <span class="orasi-filter-chip"><strong>Tahun:</strong> {{ $activeOrasiFilterStats['tahun'] ?? '-' }}</span>
                                <span class="orasi-filter-chip"><strong>Orator:</strong> {{ $activeOrasiFilterStats['guru_besar'] ?? 0 }}</span>
                                <span class="orasi-filter-chip"><strong>Fakultas:</strong> {{ $activeOrasiFilterStats['fakultas_terlibat'] ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('portal.guru-besar') }}" class="orasi-section-link">Lihat Seluruh Guru Besar</a>
                    </div>
                @endif

                <div class="orasi-professor-archive" id="orasiProfessorArchive">
                    @forelse ($oratorsByYear as $year => $gurus)
                        <div class="orasi-professor-year{{ $loop->first ? ' is-open' : '' }}" data-year-accordion>
                            <button type="button" class="orasi-professor-year-toggle" data-year-toggle aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                <span class="orasi-professor-year-badge">{{ $year }}</span>
                                <span>
                                    <span class="orasi-professor-year-title">Arsip Guru Besar Tahun {{ $year }}</span>
                                </span>
                                <span class="orasi-professor-year-count">{{ $gurus->count() }} orator</span>
                            </button>
                            <div class="orasi-professor-year-panel">
                                <div class="orasi-professor-year-panel-inner">
                                    <div class="row g-4">
                                        @foreach ($gurus as $guru)
                                            @php
                                                $cleanName = preg_replace('/\b(prof|dr|ir|mt|mpd|msi|mh|mp|phd|sh|st)\b\.?/i', ' ', $guru->nama);
                                                $initials = collect(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', ' ', $cleanName) ?: ''))
                                                    ->filter()
                                                    ->take(2)
                                                    ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))
                                                    ->implode('');
                                                $posterFaculty = Str::upper($guru->displayFakultas() !== '-' ? $guru->displayFakultas() : 'UNIVERSITAS MULAWARMAN');
                                                $posterField = $guru->bidang_ilmu ?: 'Bidang ilmu belum diisi';
                                                $useFullOverlay = $guru->usesFullPngOverlay();
                                            @endphp
                                            <div class="col-xl-3 col-md-6">
                                                <a href="{{ route('portal.guru-besar.show', $guru) }}" class="orasi-professor-link">
                                                    <div class="orasi-professor-card">
                                                        <div class="orasi-professor-poster{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                                            <div class="orasi-professor-poster-title">
                                                                Orasi Ilmiah<br>Guru Besar
                                                                <span class="orasi-professor-poster-campus">UNIVERSITAS MULAWARMAN</span>
                                                            </div>
                                                            <div class="orasi-professor-photo-wrap{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                                                @if ($guru->foto_path)
                                                                    <img
                                                                        src="{{ asset('storage/' . $guru->foto_path) }}"
                                                                        class="orasi-professor-photo{{ $useFullOverlay ? ' is-full-overlay' : '' }}"
                                                                        alt="{{ $guru->nama }}"
                                                                        loading="lazy"
                                                                        decoding="async"
                                                                    >
                                                                @else
                                                                    <div class="orasi-professor-placeholder">{{ $initials ?: 'GB' }}</div>
                                                                @endif
                                                            </div>
                                                            <div class="orasi-professor-poster-footer">
                                                                <div class="orasi-professor-footer-name">{{ $guru->nama }}</div>
                                                                <div class="orasi-professor-footer-role">GURU BESAR</div>
                                                                <div class="orasi-professor-footer-field">{{ $posterField }}</div>
                                                                <div class="orasi-professor-footer-faculty">{{ $posterFaculty }}</div>
                                                            </div>
                                                        </div>
                                                        <h4 class="orasi-professor-name">{{ $guru->nama }}</h4>
                                                        @if ($guru->judul_orasi)
                                                            <div class="orasi-professor-card-title">{{ $guru->judul_orasi }}</div>
                                                        @endif
                                                        <div class="orasi-professor-card-meta">
                                                            <span>{{ $guru->bidang_ilmu ?: 'Bidang ilmu belum diisi.' }}</span>
                                                            <span>{{ $guru->displayFakultas() }}{{ $guru->displayProdi() !== '-' ? ' • ' . $guru->displayProdi() : '' }}</span>
                                                        </div>
                                                        <span class="orasi-professor-card-cta">Lihat Profil Lengkap</span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="orasi-empty">
                            Belum ada guru besar yang ditugaskan ke event orasi ilmiah. Setelah data diisi admin, daftar orator akan tampil di sini.
                        </div>
                    @endforelse
                </div>
            @else
                <div class="orasi-orator-slider" id="orasiOratorSlider" data-cycle-slider data-slide-selector="[data-orator-slide]" data-autoplay-ms="5200">
                    @forelse ($oratorSlides as $slide)
                        <div
                            class="orasi-orator-slide{{ $loop->first ? ' is-active' : '' }}"
                            data-orator-slide
                            data-slide-index="{{ $loop->index }}"
                            data-slide-year="{{ $slide['year'] }}"
                        >
                            <div class="orasi-orator-slide-meta">
                                <div class="orasi-orator-slide-year">{{ $slide['year'] }}</div>
                            </div>

                            <div class="row g-4">
                                @foreach ($slide['items'] as $guru)
                                    @php
                                        $cleanName = preg_replace('/\b(prof|dr|ir|mt|mpd|msi|mh|mp|phd|sh|st)\b\.?/i', ' ', $guru->nama);
                                        $initials = collect(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', ' ', $cleanName) ?: ''))
                                            ->filter()
                                            ->take(2)
                                            ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))
                                            ->implode('');
                                        $posterFaculty = Str::upper($guru->displayFakultas() !== '-' ? $guru->displayFakultas() : 'UNIVERSITAS MULAWARMAN');
                                        $posterField = $guru->bidang_ilmu ?: 'Bidang ilmu belum diisi';
                                        $useFullOverlay = $guru->usesFullPngOverlay();
                                    @endphp
                                    <div class="col-xl-3 col-md-6">
                                        <a href="{{ route('portal.guru-besar.show', $guru) }}" class="orasi-professor-link">
                                            <div class="orasi-professor-card">
                                                <div class="orasi-professor-poster{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                                    <div class="orasi-professor-poster-title">
                                                        Orasi Ilmiah<br>Guru Besar
                                                        <span class="orasi-professor-poster-campus">UNIVERSITAS MULAWARMAN</span>
                                                    </div>
                                                    <div class="orasi-professor-photo-wrap{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                                        @if ($guru->foto_path)
                                                            @php
                                                                $photoUrl = asset('storage/' . $guru->foto_path);
                                                                $deferProfessorPhoto = ! ($loop->parent->first && $loop->iteration <= 2);
                                                            @endphp
                                                            <img
                                                                @if ($deferProfessorPhoto)
                                                                    data-src="{{ $photoUrl }}"
                                                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
                                                                    loading="lazy"
                                                                @else
                                                                    src="{{ $photoUrl }}"
                                                                @endif
                                                                class="orasi-professor-photo{{ $useFullOverlay ? ' is-full-overlay' : '' }}"
                                                                alt="{{ $guru->nama }}"
                                                                decoding="async"
                                                            >
                                                        @else
                                                            <div class="orasi-professor-placeholder">{{ $initials ?: 'GB' }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="orasi-professor-poster-footer">
                                                        <div class="orasi-professor-footer-name">{{ $guru->nama }}</div>
                                                        <div class="orasi-professor-footer-role">GURU BESAR</div>
                                                        <div class="orasi-professor-footer-field">{{ $posterField }}</div>
                                                        <div class="orasi-professor-footer-faculty">{{ $posterFaculty }}</div>
                                                    </div>
                                                </div>
                                                <h4 class="orasi-professor-name">{{ $guru->nama }}</h4>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="orasi-empty">
                                Belum ada guru besar yang ditugaskan ke event orasi ilmiah. Setelah data diisi admin, daftar orator akan tampil di sini.
                            </div>
                        </div>
                    @endforelse
                </div>
            @endif

            @if ($isHomePage)
                <div class="orasi-section-actions">
                    <a href="{{ route('portal.guru-besar') }}" class="orasi-section-link">Lihat Selengkapnya</a>
                </div>
            @endif
        </div>
    </section>
    @endif

    @php
        $agendaSectionBackground = str_replace(' ', '%20', asset('ChatGPT Image 2 Jun 2026, 15.52.54.png'));
    @endphp
    @if ($showDaftarOrasi)
    <section id="daftar-orasi" class="orasi-orasi-section text-light pt90 pb90" style="--orasi-orasi-bg: url('{{ $agendaSectionBackground }}');">
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-10 col-xl-8">
                    <h2 class="wow fadeInUp mb-0" data-wow-delay=".1s">Agenda Orasi Ilmiah</h2>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($orasiHighlights as $item)
                    @php
                        $agendaThemes = [
                            ['accent' => '#f6b234', 'soft' => '#f6b234', 'ink' => '#18213a'],
                            ['accent' => '#2f8cf6', 'soft' => '#2f8cf6', 'ink' => '#18213a'],
                            ['accent' => '#8b5cf6', 'soft' => '#8b5cf6', 'ink' => '#18213a'],
                            ['accent' => '#10b981', 'soft' => '#10b981', 'ink' => '#18213a'],
                        ];
                        $agendaTheme = $agendaThemes[$loop->index % count($agendaThemes)];
                        $agendaBannerImage = $item->banner_path ? asset('storage/' . $item->banner_path) : $agendaSectionBackground;
                        $agendaDay = optional($item->tanggal_pelaksanaan)->format('d');
                        $agendaMonth = optional($item->tanggal_pelaksanaan)->translatedFormat('M');
                        $agendaAudience = ($item->guru_besars_count ?? 0) . ' Guru Besar';
                        $agendaImageStyle = $agendaBannerImage ? "url('{$agendaBannerImage}')" : 'none';
                        $agendaHeadline = Str::limit($item->judul ?: 'Agenda Orasi Ilmiah', 92);
                    @endphp
                    <div class="col-xl-6">
                        <a href="{{ route('portal.guru-besar', ['orasi' => $item->id]) }}" class="orasi-banner-card-link" aria-label="Lihat guru besar pada agenda {{ $item->judulLengkap }}">
                            <div
                                class="orasi-banner-card wow fadeInUp"
                                data-wow-delay=".{{ $loop->index }}s"
                                style="--agenda-accent: {{ $agendaTheme['accent'] }}; --agenda-accent-soft: {{ $agendaTheme['soft'] }}; --agenda-ink: {{ $agendaTheme['ink'] }}; --agenda-image: {{ $agendaImageStyle }};"
                            >
                                <div class="orasi-banner-thumb">
                                    <div class="orasi-banner-thumb-top">
                                        <span class="orasi-banner-status">{{ Str::upper($item->status) }}</span>
                                        <div class="orasi-banner-date-badge" aria-label="Tanggal pelaksanaan">
                                            <span class="orasi-banner-date-day">{{ $agendaDay ?: '--' }}</span>
                                            <span class="orasi-banner-date-month">{{ $agendaMonth ?: 'TBD' }}</span>
                                        </div>
                                    </div>

                                    <div class="orasi-banner-thumb-bottom">
                                        <div class="orasi-banner-footer-panel">
                                            <div class="orasi-banner-thumb-code">
                                                <strong>{{ $item->tahun ?: '-' }}</strong>
                                                <span>Tahun Orasi</span>
                                            </div>
                                            <div class="orasi-banner-body">
                                                <h4 class="orasi-banner-title">{{ $agendaHeadline }}</h4>
                                                <div class="orasi-banner-series">{{ $item->jenis ?: 'Pelaksanaan orasi ilmiah' }}</div>
                                            </div>
                                            <div class="orasi-banner-thumb-caption">
                                                <strong>{{ $agendaAudience }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="orasi-empty text-light" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.18); color: rgba(255,255,255,0.74);">
                            Belum ada agenda orasi yang tersedia.
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($isHomePage)
                <div class="orasi-section-actions">
                    <a href="{{ route('portal.daftar-orasi') }}" class="orasi-section-link">Lihat Selengkapnya</a>
                </div>
            @endif
        </div>
    </section>
    @endif

    @if ($showVideoOrasi)
    <section id="video-orasi" class="orasi-surface-section pt90 pb60">
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-10 col-xl-8">
                    <h2 class="wow fadeInUp mb-0" data-wow-delay=".1s">Video Orasi Ilmiah</h2>
                </div>
            </div>

            @if ($isHomePage)
                <div class="orasi-video-slider is-home-slider" id="orasiVideoSlider" data-cycle-slider data-slide-selector="[data-video-slide]" data-autoplay-ms="5200">
                    @forelse ($videoSlides as $slide)
                        <div
                            class="orasi-video-slide{{ $loop->first ? ' is-active' : '' }}"
                            data-video-slide
                            data-slide-index="{{ $loop->index }}"
                            data-slide-year="{{ $slide['year'] }}"
                        >
                            <div class="orasi-video-slide-meta">
                                <div class="orasi-video-slide-year">{{ $slide['year'] }}</div>
                            </div>

                            <div class="row g-4">
                                @foreach ($slide['items'] as $video)
                                    <div class="col-xl-3 col-md-6">
                                        <div class="orasi-video-card">
                                            <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener noreferrer" class="d-block" aria-label="Buka video {{ $video->nama }}">
                                                <div class="orasi-video-thumb">
                                                    <img
                                                        src="{{ $video->youtube_thumbnail_url ?: asset('images/background/1.webp') }}"
                                                        alt="Thumbnail YouTube {{ $video->nama }}"
                                                        loading="lazy"
                                                        decoding="async"
                                                    >
                                                    <span class="orasi-video-play" aria-hidden="true">
                                                        <i class="fa-solid fa-play"></i>
                                                    </span>
                                                    <div class="orasi-video-thumb-overlay">
                                                        <div class="orasi-video-thumb-copy">
                                                            <h4 class="orasi-video-thumb-title">{{ Str::limit($video->nama, 62) }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="orasi-video-body">
                                                <div class="orasi-video-meta">
                                                    <div class="orasi-video-meta-line">{{ $video->bidang_ilmu ?: 'Bidang ilmu belum diisi.' }}</div>
                                                    <div class="orasi-video-meta-line">{{ $video->displayFakultas() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="orasi-empty">
                                Belum ada video YouTube yang dihubungkan ke data guru besar.
                            </div>
                        </div>
                    @endforelse
                </div>
            @else
                <div class="orasi-professor-archive" data-year-archive>
                    @forelse ($videosByYear as $year => $videos)
                        <div class="orasi-professor-year{{ $loop->first ? ' is-open' : '' }}" data-year-accordion>
                            <button type="button" class="orasi-professor-year-toggle" data-year-toggle aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                <span class="orasi-professor-year-badge">{{ $year }}</span>
                                <span>
                                    <span class="orasi-professor-year-title">Arsip Video Orasi Tahun {{ $year }}</span>
                                </span>
                                <span class="orasi-professor-year-count">{{ $videos->count() }} video</span>
                            </button>
                            <div class="orasi-professor-year-panel">
                                <div class="orasi-professor-year-panel-inner">
                                    <div class="row g-4">
                                        @foreach ($videos as $video)
                                            <div class="col-xl-3 col-md-6">
                                                <div class="orasi-video-card">
                                                    <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener noreferrer" class="d-block" aria-label="Buka video {{ $video->nama }}">
                                                        <div class="orasi-video-thumb">
                                                            <img
                                                                src="{{ $video->youtube_thumbnail_url ?: asset('images/background/1.webp') }}"
                                                                alt="Thumbnail YouTube {{ $video->nama }}"
                                                                loading="lazy"
                                                                decoding="async"
                                                            >
                                                            <span class="orasi-video-play" aria-hidden="true">
                                                                <i class="fa-solid fa-play"></i>
                                                            </span>
                                                            <div class="orasi-video-thumb-overlay">
                                                                <div class="orasi-video-thumb-copy">
                                                                    <h4 class="orasi-video-thumb-title">{{ Str::limit($video->nama, 62) }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="orasi-video-body">
                                                        <div class="orasi-video-meta">
                                                            <div class="orasi-video-meta-line">{{ $video->bidang_ilmu ?: 'Bidang ilmu belum diisi.' }}</div>
                                                            <div class="orasi-video-meta-line">{{ $video->displayFakultas() }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="orasi-empty">
                            Belum ada video YouTube yang dihubungkan ke data guru besar.
                        </div>
                    @endforelse
                </div>
            @endif

            @if ($isHomePage)
                <div class="orasi-section-actions">
                    <a href="{{ route('portal.video-orasi') }}" class="orasi-section-link">Lihat Selengkapnya</a>
                </div>
            @endif
        </div>
    </section>
    @endif

    @if ($showDokumenOrasi)
    <section id="dokumen-orasi" class="orasi-surface-section pb90">
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-10 col-xl-8">
                    <h2 class="wow fadeInUp mb-0" data-wow-delay=".1s">Dokumen Orasi Ilmiah</h2>
                    @if (session('warning'))
                        <p class="orasi-empty mt-3 mb-0">{{ session('warning') }}</p>
                    @endif
                </div>
            </div>

            @if ($isHomePage)
                @if ($documentSlides->isNotEmpty())
                    <div class="orasi-document-slider is-home-slider" id="orasiDocumentSlider" data-cycle-slider data-slide-selector="[data-document-slide]" data-autoplay-ms="5200">
                        @foreach ($documentSlides as $slide)
                            <div
                                class="orasi-document-slide{{ $loop->first ? ' is-active' : '' }}"
                                data-document-slide
                                data-slide-index="{{ $loop->index }}"
                                data-slide-year="{{ $slide['year'] }}"
                            >
                                <div class="orasi-document-slide-meta">
                                    <div class="orasi-document-slide-year">{{ $slide['year'] }}</div>
                                </div>

                                <div class="row g-2 g-lg-3">
                                    @foreach ($slide['items'] as $document)
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            @include('partials.home.document-preview-card', ['document' => $document])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="orasi-empty">
                        Belum ada dokumen orasi yang tersedia.
                    </div>
                @endif
            @else
                <div class="orasi-professor-archive" data-year-archive>
                    @forelse ($documentsByYear as $year => $items)
                        @php
                            $naskahItems = $items->filter(fn ($item) => filled($item->file_orasi_path))->values();
                            $presentasiItems = $items->filter(fn ($item) => filled($item->ppt_path))->values();
                        @endphp
                        <div class="orasi-professor-year{{ $loop->first ? ' is-open' : '' }}" data-year-accordion>
                            <div class="orasi-professor-year-head">
                                <button type="button" class="orasi-professor-year-toggle" data-year-toggle aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    <span class="orasi-professor-year-badge">{{ $year }}</span>
                                    <span>
                                        <span class="orasi-professor-year-title">Arsip Dokumen Orasi Tahun {{ $year }}</span>
                                    </span>
                                </button>
                                <div class="orasi-professor-year-head-actions">
                                    <div class="orasi-year-merge-actions">
                                        @if ($naskahItems->isNotEmpty())
                                            <a
                                                href="{{ route('portal.dokumen-orasi.merge', ['year' => $documentYearSlug($year), 'type' => 'naskah']) }}"
                                                class="orasi-year-merge-btn orasi-year-merge-btn--compact"
                                            >
                                                <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
                                                <span>Unduh Gabungan Naskah</span>
                                            </a>
                                        @endif
                                        @if ($presentasiItems->isNotEmpty())
                                            <a
                                                href="{{ route('portal.dokumen-orasi.merge', ['year' => $documentYearSlug($year), 'type' => 'presentasi']) }}"
                                                class="orasi-year-merge-btn orasi-year-merge-btn--compact orasi-year-merge-btn--presentasi"
                                            >
                                                <i class="fa-solid fa-file-powerpoint" aria-hidden="true"></i>
                                                <span>Unduh Gabungan Presentasi</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="orasi-professor-year-panel">
                                <div class="orasi-professor-year-panel-inner">
                                    <div class="row g-3 g-lg-4 align-items-stretch">
                                        @foreach ($items as $item)
                                            @php
                                                $hasNaskah = filled($item->file_orasi_path);
                                                $hasPresentasi = filled($item->ppt_path);
                                                $docJudul = $item->judul_orasi ?: ($item->orasiIlmiah?->judul ?? 'Orasi Ilmiah Guru Besar');
                                                $docYear = $item->archiveYear() ?? $item->orasiIlmiah?->tahun ?? date('Y');
                                            @endphp
                                            <div class="col-lg-6 d-flex">
                                                <div class="orasi-archive-prof-doc">
                                                    <div class="orasi-archive-prof-doc-head">
                                                        <h4 class="orasi-archive-prof-doc-name">{{ $item->nama }}</h4>
                                                        <div class="orasi-archive-prof-doc-meta">{{ $item->displayFakultas() }}</div>
                                                    </div>
                                                    <div class="orasi-archive-prof-preview-row">
                                                        <div class="orasi-archive-prof-preview-slot">
                                                            @if ($hasNaskah)
                                                                @include('partials.home.document-preview-card', [
                                                                    'document' => [
                                                                        'variant' => 'manuscript',
                                                                        'person' => $item->nama,
                                                                        'judul' => $docJudul,
                                                                        'year' => $docYear,
                                                                        'file' => $item->file_orasi_path,
                                                                        'filename' => basename($item->file_orasi_path),
                                                                        'extension' => strtoupper(pathinfo($item->file_orasi_path, PATHINFO_EXTENSION) ?: 'FILE'),
                                                                        'label' => 'Naskah',
                                                                        'icon' => 'fa-solid fa-file-lines',
                                                                        'accent' => 'open-blue',
                                                                    ],
                                                                ])
                                                            @endif
                                                        </div>
                                                        <div class="orasi-archive-prof-preview-slot">
                                                            @if ($hasPresentasi)
                                                                @include('partials.home.document-preview-card', [
                                                                    'document' => [
                                                                        'variant' => 'presentation',
                                                                        'person' => $item->nama,
                                                                        'judul' => $docJudul,
                                                                        'year' => $docYear,
                                                                        'file' => $item->ppt_path,
                                                                        'filename' => basename($item->ppt_path),
                                                                        'extension' => strtoupper(pathinfo($item->ppt_path, PATHINFO_EXTENSION) ?: 'FILE'),
                                                                        'label' => 'Presentasi',
                                                                        'icon' => 'fa-solid fa-file-powerpoint',
                                                                        'accent' => 'open-purple',
                                                                    ],
                                                                ])
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="orasi-empty">
                            Belum ada dokumen orasi yang tersedia.
                        </div>
                    @endforelse
                </div>
            @endif

            @if ($isHomePage)
                <div class="orasi-section-actions">
                    <a href="{{ route('portal.dokumen-orasi') }}" class="orasi-section-link">Lihat Selengkapnya</a>
                </div>
            @endif
        </div>
    </section>
    @endif

    @if ($showStatistik)
    <section id="statistik" class="orasi-stats-section pt90 pb90">
        <div class="container">
            @if ($isHomePage)
                <div class="row justify-content-center text-center mb-4">
                    <div class="col-lg-10 col-xl-8">
                        <h2 class="wow fadeInUp mb-0" data-wow-delay=".1s">Statistik Guru Besar Aktif</h2>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-7">
                        <div class="orasi-chart-card wow fadeInUp">
                            <div class="orasi-chart-head">
                                <div>
                                    <div class="subtitle mb-2">Akumulasi Fakultas</div>
                                    <h4>Sebaran guru besar per fakultas</h4>
                                </div>
                                <span class="orasi-archive-pill">{{ $activeStatSummary['total_guru_besar'] }} guru besar aktif</span>
                            </div>
                            <div class="orasi-chart-wrap orasi-chart-wrap-lg">
                                <canvas id="facultyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="orasi-chart-card wow fadeInUp" data-wow-delay=".1s">
                            <div class="orasi-chart-head">
                                <div>
                                    <div class="subtitle mb-2">Komposisi Aktif</div>
                                    <h4>Distribusi laki-laki dan perempuan</h4>
                                </div>
                                <span class="orasi-archive-pill">{{ $activeStatSummary['period_label'] }}</span>
                            </div>
                            <div class="orasi-chart-wrap orasi-chart-wrap-lg orasi-chart-wrap-donut">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row align-items-end g-4 mb-4">
                    <div class="col-12">
                        <div class="subtitle wow fadeInUp mb-3">Statistik</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".1s">Statistik Guru Besar Universitas Mulawarman</h2>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-xl-8">
                        <div class="orasi-chart-card wow fadeInUp">
                            <div class="orasi-chart-head">
                                <div>
                                    <div class="subtitle mb-2">Akumulasi Fakultas</div>
                                    <h4>Sebaran guru besar per fakultas</h4>
                                </div>
                                <span class="orasi-archive-pill">{{ $activeStatSummary['total_fakultas'] }} fakultas aktif</span>
                            </div>
                            <div class="orasi-chart-wrap orasi-chart-wrap-lg">
                                <canvas id="facultyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="orasi-chart-card wow fadeInUp" data-wow-delay=".1s">
                            <div class="orasi-chart-head">
                                <div>
                                    <div class="subtitle mb-2">Komposisi Gender</div>
                                    <h4>Distribusi laki-laki dan perempuan</h4>
                                </div>
                            </div>
                            <div class="orasi-chart-wrap orasi-chart-wrap-md orasi-chart-wrap-donut">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-xl-5">
                        <div class="orasi-chart-card wow fadeInUp" data-wow-delay=".2s">
                            <div class="orasi-chart-head">
                                <div>
                                    <div class="subtitle mb-2">Tahun Aktif</div>
                                    <h4>Guru besar per tahun</h4>
                                </div>
                                <span class="orasi-archive-pill">{{ $activeStatSummary['period_label'] }}</span>
                            </div>
                            <div class="orasi-chart-wrap orasi-chart-wrap-lg">
                                <canvas id="yearTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="orasi-chart-card wow fadeInUp" data-wow-delay=".22s">
                            <div class="orasi-chart-head">
                                <div>
                                    <div class="subtitle mb-2">Raihan Berdasarkan SK</div>
                                    <h4>Guru besar per fakultas 2019-2025</h4>
                                    <p class="orasi-chart-subtitle mb-0">Rekap raihan guru besar Universitas Mulawarman berdasarkan SK per fakultas.</p>
                                </div>
                                <span class="orasi-archive-pill">{{ $excelStatSummary['total_achievements'] }} raihan SK</span>
                            </div>
                            <div class="orasi-chart-wrap orasi-chart-wrap-xl">
                                <canvas id="achievementFacultyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isHomePage)
                <div class="orasi-section-actions">
                    <a href="{{ route('portal.statistik') }}" class="orasi-section-link">Lihat Selengkapnya</a>
                </div>
            @endif
        </div>
    </section>
    @endif
</div>

@push('scripts')
    <script>
        window.initOrasiCharts = function() {
            if (window.initOrasiCharts._initialized) {
                return;
            }

            window.initOrasiCharts._initialized = true;

            const palette = ['#F6B234', '#2F8CF6', '#8B5CF6', '#10B981', '#F97316', '#EF4444', '#06B6D4', '#6366F1'];
            const chartFont = "'Inter', 'system-ui', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
            const activeFacultyChartData = @json($activeFacultyChartData);
            const activeGenderChartData = @json($activeGenderChartData);
            const activeYearChartData = @json($activeYearChartData);
            const activeStatSummary = @json($activeStatSummary);
            const excelAchievementData = @json($excelAchievementData);
            const excelStatSummary = @json($excelStatSummary);

            Chart.defaults.font.family = chartFont;
            Chart.defaults.color = '#667085';

            const centerTextPlugin = {
                id: 'centerText',
                beforeDraw(chart, args, options) {
                    const { ctx, chartArea } = chart;

                    if (!chartArea) {
                        return;
                    }

                    const centerX = (chartArea.left + chartArea.right) / 2;
                    const centerY = (chartArea.top + chartArea.bottom) / 2;
                    const total = options.total ?? 0;
                    const label = options.label ?? 'Total GB';
                    const scope = options.scope ?? '';

                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#18213a';
                    ctx.font = `800 ${options.totalSize || 32}px ${chartFont}`;
                    ctx.fillText(String(total), centerX, centerY - 12);
                    ctx.fillStyle = '#667085';
                    ctx.font = `700 ${options.labelSize || 12}px ${chartFont}`;
                    ctx.fillText(label, centerX, centerY + 16);

                    if (scope) {
                        ctx.fillStyle = '#98a2b3';
                        ctx.font = `600 ${options.scopeSize || 11}px ${chartFont}`;
                        ctx.fillText(scope, centerX, centerY + 34);
                    }

                    ctx.restore();
                }
            };

            const facultyCtx = document.getElementById('facultyChart');
            const genderCtx = document.getElementById('genderChart');
            const yearTrendCtx = document.getElementById('yearTrendChart');
            const achievementFacultyCtx = document.getElementById('achievementFacultyChart');

            function buildYearBarChart(canvas, labels, data, label) {
                if (!canvas) {
                    return;
                }

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: labels.map(function(_, index) {
                                return palette[index % palette.length] + 'cc';
                            }),
                            borderColor: labels.map(function(_, index) {
                                return palette[index % palette.length];
                            }),
                            borderWidth: 1.5,
                            borderRadius: 14,
                            maxBarThickness: 54,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1100,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.formattedValue + ' ' + label.toLowerCase();
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 },
                                grid: { color: 'rgba(31, 41, 55, 0.08)' },
                                border: { display: false }
                            }
                        }
                    }
                });
            }

            function buildStackedFacultyChart(canvas, payload, suffix) {
                if (!canvas) {
                    return;
                }

                const facultyLabelMap = payload.faculties.reduce(function(map, code, index) {
                    map[code] = payload.faculty_labels[index] || code;

                    return map;
                }, {});

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: payload.faculties,
                        datasets: payload.series.map(function(series) {
                            return {
                                label: series.label,
                                data: series.data,
                                backgroundColor: series.color + '33',
                                borderColor: series.color,
                                borderWidth: 2,
                                borderRadius: 6,
                                barThickness: 10,
                            };
                        }),
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        animation: {
                            duration: 1400,
                            easing: 'easeOutQuart'
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'y',
                            intersect: true,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 34,
                                    padding: 12
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                callbacks: {
                                    title: function(context) {
                                        const code = context[0]?.label || '';
                                        const label = facultyLabelMap[code] || code;

                                        return code && label ? code + ' - ' + label : (label || code);
                                    },
                                    label: function(context) {
                                        return ' ' + context.dataset.label + ': ' + context.formattedValue + ' ' + suffix;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { precision: 0 },
                                stacked: true,
                                grid: { color: 'rgba(31, 41, 55, 0.06)' },
                                border: { display: false }
                            },
                            y: {
                                stacked: true,
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            }

            if (facultyCtx) {
                new Chart(facultyCtx, {
                    type: 'bar',
                    data: {
                        labels: activeFacultyChartData.labels,
                        datasets: [{
                            data: activeFacultyChartData.data,
                            borderWidth: 0,
                            borderRadius: 12,
                            barThickness: 16,
                            backgroundColor: activeFacultyChartData.labels.map(function(_, index) {
                                return palette[index % palette.length];
                            }),
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        animation: {
                            duration: 900,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        const index = context[0]?.dataIndex ?? 0;

                                        return activeFacultyChartData.labels[index] || context[0]?.label || '';
                                    },
                                    label: function(context) {
                                        return ' ' + context.formattedValue + ' guru besar';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { precision: 0 },
                                grid: { color: 'rgba(31, 41, 55, 0.08)' },
                                border: { display: false }
                            },
                            y: {
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            }

            if (genderCtx) {
                new Chart(genderCtx, {
                    type: 'doughnut',
                    plugins: [centerTextPlugin],
                    data: {
                        labels: activeGenderChartData.labels,
                        datasets: [{
                            data: activeGenderChartData.data,
                            backgroundColor: ['#F6B234', '#2F8CF6', '#8B5CF6', '#10B981'],
                            borderWidth: 0,
                            hoverOffset: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        layout: {
                            padding: 8,
                        },
                        elements: {
                            arc: {
                                hoverOffset: 0,
                            },
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            centerText: {
                                total: activeStatSummary.total_guru_besar || 0,
                                label: 'Total GB',
                                scope: activeStatSummary.period_label || 'Data Aktif',
                                totalSize: 34,
                                labelSize: 12,
                                scopeSize: 11,
                            },
                            legend: {
                                position: 'bottom',
                                onHover: function() {},
                                onLeave: function() {},
                                onClick: function() {},
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 10,
                                    padding: 18
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ' + context.formattedValue;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            buildYearBarChart(yearTrendCtx, activeYearChartData.labels, activeYearChartData.data, 'Guru Besar');
            buildStackedFacultyChart(achievementFacultyCtx, excelAchievementData, 'SK');
        };
    </script>
@endpush
