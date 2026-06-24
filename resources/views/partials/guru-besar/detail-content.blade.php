@push('styles')
    <style>
        .orasi-detail-page {
            background:
                radial-gradient(circle at top left, rgba(246, 178, 52, 0.14), transparent 28%),
                linear-gradient(180deg, #f7f8fb 0%, #eef2f8 100%);
        }

        .orasi-detail-hero {
            position: relative;
            overflow: hidden;
            padding: calc(var(--orasi-header-height, 78px) + 56px) 0 84px;
            background:
                linear-gradient(180deg, rgba(8, 12, 24, 0.9) 0%, rgba(8, 12, 24, 0.86) 42%, rgba(6, 10, 20, 0.96) 100%),
                var(--orasi-detail-hero-bg) center / cover no-repeat;
            color: #fff;
        }

        .orasi-detail-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                rgba(6, 10, 20, 0.42),
                radial-gradient(circle at 20% 18%, rgba(255, 255, 255, 0.06), transparent 22%),
                radial-gradient(circle at 82% 20%, rgba(246, 178, 52, 0.08), transparent 18%);
        }

        .orasi-detail-shell,
        .orasi-detail-section {
            position: relative;
            z-index: 1;
        }

        .orasi-detail-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
        }

        .orasi-detail-kicker::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #f6b234;
        }

        .orasi-detail-title {
            margin: 22px 0 18px;
            color: #fff;
            font-size: clamp(2.5rem, 4vw, 4.6rem);
            font-weight: 800;
            line-height: 0.98;
            letter-spacing: -0.05em;
        }

        .orasi-detail-summary {
            max-width: 720px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 1.02rem;
            line-height: 1.8;
        }

        .orasi-detail-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 26px;
        }

        .orasi-detail-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .orasi-detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 30px;
        }

        .orasi-detail-visual {
            display: flex;
            justify-content: center;
        }

        .orasi-detail-poster {
            width: min(100%, 360px);
            padding: 22px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.22);
            backdrop-filter: blur(18px);
        }

        .orasi-detail-poster-frame {
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            aspect-ratio: 9 / 16;
            background:
                linear-gradient(180deg, rgba(255, 204, 83, 0.4), transparent 28%),
                #f9aa28;
        }

        .orasi-detail-poster-frame.is-full-overlay::before {
            opacity: 0;
        }

        .orasi-detail-poster-frame.is-full-overlay .orasi-detail-poster-title,
        .orasi-detail-poster-frame.is-full-overlay .orasi-detail-poster-footer {
            display: none;
        }

        .orasi-detail-poster-frame::before {
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

        .orasi-detail-poster-title {
            position: absolute;
            top: 4.7%;
            left: 6%;
            right: 6%;
            z-index: 4;
            color: #fff;
            font-size: 2.45rem;
            font-weight: 800;
            line-height: 0.95;
            text-align: center;
        }

        .orasi-detail-poster-campus {
            display: block;
            margin-top: 8px;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .orasi-detail-photo-wrap {
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

        .orasi-detail-photo-wrap.is-full-overlay {
            inset: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height: auto;
            align-items: stretch;
            justify-content: stretch;
        }

        .orasi-detail-photo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center bottom;
            filter: drop-shadow(0 18px 20px rgba(0, 0, 0, 0.18));
        }

        .orasi-detail-photo.is-full-overlay {
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

        .orasi-detail-placeholder {
            width: 72%;
            aspect-ratio: 1;
            border-radius: 999px;
            display: grid;
            place-items: center;
            margin-bottom: 12%;
            background: rgba(255, 255, 255, 0.24);
            color: #fff;
            font-size: 3.1rem;
            font-weight: 800;
        }

        .orasi-detail-poster-footer {
            position: absolute;
            left: 4.5%;
            right: 4.5%;
            bottom: 7.8%;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 11%;
            padding: 8px 12px;
            text-align: center;
            color: #fff;
            background: linear-gradient(180deg, #287ed9 0%, #08335f 100%);
            box-shadow: 0 -6px 18px rgba(0, 0, 0, 0.12) inset;
        }

        .orasi-detail-poster-footer strong {
            font-size: 0.86rem;
            line-height: 1.08;
            font-weight: 800;
        }

        .orasi-detail-poster-footer span {
            display: block;
            font-size: 0.6rem;
            line-height: 1.08;
            font-weight: 800;
        }

        .orasi-detail-section {
            padding: 84px 0;
        }

        .orasi-detail-card {
            height: 100%;
            padding: 28px;
            border-radius: 28px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: 0 18px 50px rgba(31, 41, 55, 0.08);
        }

        .orasi-detail-card h3,
        .orasi-detail-card h4 {
            margin: 0 0 18px;
            color: #18213a;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .orasi-detail-data-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .orasi-detail-data-item {
            padding: 18px 20px;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-detail-data-item span {
            display: block;
            color: #667085;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-detail-data-item strong {
            display: block;
            margin-top: 8px;
            color: #18213a;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.45;
        }

        .orasi-detail-doc-list {
            display: grid;
            gap: 14px;
        }

        .orasi-detail-doc-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 20px;
            border-radius: 20px;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
            color: #18213a;
            transition: transform 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
        }

        .orasi-detail-doc-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(31, 41, 55, 0.08);
            color: #18213a;
            background: #fff;
        }

        .orasi-detail-doc-link span {
            display: block;
            color: #667085;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .orasi-detail-doc-link strong {
            display: block;
            margin-top: 6px;
            color: #18213a;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.4;
        }

        .orasi-detail-video-frame {
            overflow: hidden;
            border-radius: 28px;
            background: #0f1322;
            box-shadow: 0 18px 50px rgba(31, 41, 55, 0.12);
            aspect-ratio: 16 / 9;
        }

        .orasi-detail-video-frame iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        .orasi-detail-related-section {
            overflow: hidden;
            position: relative;
        }

        .orasi-detail-related-section h3 {
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }

        .orasi-detail-related-swiper {
            position: relative;
            width: 100%;
            height: auto !important;
            overflow: hidden;
            padding: 4px 2px 16px;
            isolation: isolate;
        }

        .orasi-detail-related-swiper .swiper-wrapper {
            align-items: stretch;
        }

        .orasi-detail-related-swiper .swiper-slide {
            position: relative;
            height: auto;
            background: transparent;
        }

        .orasi-detail-related-link {
            display: block;
            width: 100%;
            color: inherit;
            text-decoration: none;
        }

        .orasi-detail-related-link:hover {
            color: inherit;
        }

        .orasi-detail-related-poster {
            position: relative;
            isolation: isolate;
            width: 100%;
            aspect-ratio: 9 / 16;
            overflow: hidden;
            border-radius: 8px;
            background:
                linear-gradient(180deg, rgba(255, 204, 83, 0.38), transparent 28%),
                #f9aa28;
            box-shadow: 0 12px 32px rgba(31, 41, 55, 0.12);
            transition: transform 0.28s ease, box-shadow 0.28s ease;
        }

        .orasi-detail-related-link:hover .orasi-detail-related-poster {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(31, 41, 55, 0.16);
        }

        .orasi-detail-related-poster.is-full-overlay::before {
            opacity: 0;
        }

        .orasi-detail-related-poster.is-full-overlay .orasi-detail-related-poster-title {
            display: none;
        }

        .orasi-detail-related-poster::before {
            content: "";
            position: absolute;
            left: 4.5%;
            right: 4.5%;
            bottom: 8%;
            height: 43%;
            background: #32b70c;
            clip-path: polygon(0 38%, 100% 0, 100% 100%, 0 100%);
            z-index: 1;
        }

        .orasi-detail-related-poster-title {
            position: absolute;
            top: 4.7%;
            left: 6%;
            right: 6%;
            z-index: 4;
            color: #fff;
            font-size: clamp(1rem, 2.2vw, 1.45rem);
            font-weight: 800;
            line-height: 0.95;
            text-align: center;
        }

        .orasi-detail-related-poster-title span {
            display: block;
            margin-top: 6px;
            font-size: clamp(0.58rem, 1.2vw, 0.72rem);
            font-weight: 800;
            line-height: 1.1;
        }

        .orasi-detail-related-photo-wrap {
            position: absolute;
            left: 5%;
            right: 5%;
            bottom: 6%;
            height: 72%;
            z-index: 3;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .orasi-detail-related-photo-wrap.is-full-overlay {
            inset: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height: auto;
            align-items: stretch;
            justify-content: stretch;
        }

        .orasi-detail-related-photo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center bottom;
            filter: drop-shadow(0 16px 18px rgba(0, 0, 0, 0.16));
        }

        .orasi-detail-related-photo.is-full-overlay {
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

        .orasi-detail-related-placeholder {
            width: 72%;
            aspect-ratio: 1;
            border-radius: 999px;
            display: grid;
            place-items: center;
            margin-bottom: 12%;
            background: rgba(255, 255, 255, 0.24);
            color: #fff;
            font-size: 2rem;
            font-weight: 800;
        }

        .orasi-detail-pdf-section {
            margin-top: 28px;
        }

        .orasi-detail-pdf-panel {
            overflow: hidden;
            padding: 0;
        }

        .orasi-detail-pdf-head {
            padding: 28px 28px 22px;
            background:
                linear-gradient(135deg, rgba(24, 33, 58, 0.04) 0%, rgba(246, 178, 52, 0.08) 100%);
            border-bottom: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-detail-pdf-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: #9b6700;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .orasi-detail-pdf-kicker::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(135deg, #f6b234 0%, #2f8cf6 100%);
        }

        .orasi-detail-pdf-head h3 {
            margin: 0;
            color: #18213a;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .orasi-detail-pdf-head p {
            margin: 10px 0 0;
            max-width: 760px;
            color: #667085;
            font-size: 0.96rem;
            line-height: 1.7;
        }

        .orasi-detail-pdf-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            padding: 24px 28px 28px;
        }

        .orasi-detail-pdf-grid.is-single {
            grid-template-columns: minmax(0, 1fr);
        }

        .orasi-detail-pdf-item {
            display: flex;
            flex-direction: column;
            min-width: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid rgba(31, 41, 55, 0.08);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .orasi-detail-pdf-item.is-naskah {
            --orasi-pdf-accent: #123a72;
            --orasi-pdf-accent-soft: rgba(18, 58, 114, 0.12);
        }

        .orasi-detail-pdf-item.is-presentasi {
            --orasi-pdf-accent: #4f2d7a;
            --orasi-pdf-accent-soft: rgba(79, 45, 122, 0.12);
        }

        .orasi-detail-pdf-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 16px 18px;
            background: #fff;
            border-bottom: 1px solid rgba(31, 41, 55, 0.08);
        }

        .orasi-detail-pdf-toolbar-copy {
            min-width: 0;
        }

        .orasi-detail-pdf-toolbar-copy strong {
            display: block;
            color: var(--orasi-pdf-accent, #18213a);
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .orasi-detail-pdf-toolbar-copy span {
            display: block;
            margin-top: 4px;
            color: #667085;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .orasi-detail-pdf-open {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 999px;
            background: var(--orasi-pdf-accent-soft, rgba(24, 33, 58, 0.08));
            color: var(--orasi-pdf-accent, #18213a);
            font-size: 0.88rem;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .orasi-detail-pdf-open:hover {
            color: var(--orasi-pdf-accent, #18213a);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(31, 41, 55, 0.08);
        }

        .orasi-detail-pdf-frame-wrap {
            position: relative;
            min-height: 720px;
            background:
                linear-gradient(180deg, rgba(24, 33, 58, 0.03) 0%, rgba(24, 33, 58, 0.06) 100%);
        }

        .orasi-detail-pdf-frame-wrap iframe {
            display: block;
            width: 100%;
            min-height: 720px;
            border: 0;
            background: #fff;
        }

        .orasi-detail-pdf-fallback {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            min-height: 280px;
            padding: 28px;
            text-align: center;
            color: #667085;
        }

        .orasi-detail-pdf-fallback i {
            font-size: 2rem;
            color: var(--orasi-pdf-accent, #18213a);
        }

        .orasi-detail-pdf-item.is-piagam {
            --orasi-pdf-accent: #9a7420;
            --orasi-pdf-accent-soft: rgba(201, 162, 39, 0.14);
        }

        .orasi-detail-pdf-image-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 720px;
            padding: 24px;
            background:
                linear-gradient(180deg, rgba(24, 33, 58, 0.03) 0%, rgba(201, 162, 39, 0.06) 100%);
        }

        .orasi-detail-pdf-image-wrap img {
            display: block;
            width: 100%;
            max-width: 100%;
            max-height: 900px;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 18px 50px rgba(31, 41, 55, 0.12);
            background: #fff;
        }

        @media (max-width: 991.98px) {
            .orasi-detail-hero {
                padding: calc(var(--orasi-header-height, 78px) + 42px) 0 64px;
            }

            .orasi-detail-visual {
                margin-top: 18px;
            }

            .orasi-detail-data-grid {
                grid-template-columns: 1fr;
            }

            .orasi-detail-pdf-grid {
                grid-template-columns: 1fr;
            }

            .orasi-detail-pdf-frame-wrap,
            .orasi-detail-pdf-frame-wrap iframe,
            .orasi-detail-pdf-image-wrap {
                min-height: 560px;
            }
        }

        @media (max-width: 575.98px) {
            .orasi-detail-section {
                padding: 64px 0;
            }

            .orasi-detail-card {
                padding: 22px;
            }

            .orasi-detail-actions {
                flex-direction: column;
            }

            .orasi-detail-poster {
                padding: 16px;
            }

            .orasi-detail-poster-title {
                font-size: 2.05rem;
            }
        }
    </style>
@endpush

@php
    use Illuminate\Support\Str;

    $cleanName = preg_replace('/\b(prof|dr|ir|mt|mpd|msi|mh|mp|phd|sh|st|msc)\b\.?/i', ' ', $guruBesar->nama);
    $initials = collect(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', ' ', $cleanName) ?: ''))
        ->filter()
        ->take(2)
        ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))
        ->implode('');
    $posterFaculty = Str::upper($guruBesar->displayFakultas() !== '-' ? $guruBesar->displayFakultas() : 'UNIVERSITAS MULAWARMAN');
    $posterField = $guruBesar->bidang_ilmu ?: 'Bidang ilmu belum diisi';
    $useFullOverlay = $guruBesar->usesFullPngOverlay();
    $orasi = $guruBesar->orasiIlmiah;
    $archiveYear = $guruBesar->archiveYear();
    $judulOrasi = $guruBesar->judul_orasi ?: $orasi?->judul;
    $detailBadges = collect([
        $guruBesar->displayFakultas() !== '-' ? $guruBesar->displayFakultas() : null,
    ])->filter()->values();
    $hasNaskah = filled($guruBesar->file_orasi_path);
    $hasPresentasi = filled($guruBesar->ppt_path);
    $hasOrasiDocuments = $hasNaskah || $hasPresentasi;
    $hasPiagam = filled($guruBesar->piagam_path);
    $piagamUrl = $hasPiagam ? asset('storage/' . $guruBesar->piagam_path) : null;
    $piagamExtension = $hasPiagam ? strtolower(pathinfo($guruBesar->piagam_path, PATHINFO_EXTENSION) ?: '') : '';
    $piagamIsPdf = $piagamExtension === 'pdf';
    $piagamIsImage = in_array($piagamExtension, ['jpg', 'jpeg', 'png', 'webp'], true);
    $hasPiagamPreview = $hasPiagam && ($piagamIsPdf || $piagamIsImage);
@endphp

<div class="orasi-detail-page">
    <section class="orasi-detail-hero" style="--orasi-detail-hero-bg: url('{{ $heroBackground }}');">
        <div class="container orasi-detail-shell">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <a href="{{ route('portal.guru-besar') }}" class="orasi-detail-kicker">Kembali ke Arsip Guru Besar</a>
                    <h1 class="orasi-detail-title">{{ $guruBesar->nama }}</h1>
                    @if ($judulOrasi)
                        <div class="orasi-detail-badges" style="margin-top:18px;">
                            <span class="orasi-detail-badge" style="border-radius:20px; max-width: 100%; white-space: normal; line-height:1.6; align-items:flex-start;">
                                {{ $judulOrasi }}
                            </span>
                        </div>
                    @endif
                    <div class="orasi-detail-badges">
                        @foreach ($detailBadges as $badge)
                            <span class="orasi-detail-badge">{{ $badge }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="orasi-detail-visual">
                        <div class="orasi-detail-poster">
                            <div class="orasi-detail-poster-frame{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                <div class="orasi-detail-poster-title">
                                    Orasi Ilmiah<br>Guru Besar
                                    <span class="orasi-detail-poster-campus">UNIVERSITAS MULAWARMAN</span>
                                </div>
                                <div class="orasi-detail-photo-wrap{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                    @if ($guruBesar->foto_path)
                                        <img src="{{ asset('storage/' . $guruBesar->foto_path) }}" class="orasi-detail-photo{{ $useFullOverlay ? ' is-full-overlay' : '' }}" alt="{{ $guruBesar->nama }}">
                                    @else
                                        <div class="orasi-detail-placeholder">{{ $initials ?: 'GB' }}</div>
                                    @endif
                                </div>
                                <div class="orasi-detail-poster-footer">
                                    <strong>{{ $guruBesar->nama }}</strong>
                                    <span>GURU BESAR</span>
                                    <span>{{ $posterField }}</span>
                                    <span>{{ $posterFaculty }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="orasi-detail-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-12">
                    <div class="orasi-detail-card">
                        <h3>Data Guru Besar</h3>
                        <div class="orasi-detail-data-grid">
                            <div class="orasi-detail-data-item">
                                <span>Nama Lengkap</span>
                                <strong>{{ $guruBesar->nama }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Bidang Ilmu</span>
                                <strong>{{ $guruBesar->bidang_ilmu ?: '-' }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Judul Orasi Ilmiah</span>
                                <strong>{{ $judulOrasi ?: '-' }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Fakultas</span>
                                <strong>{{ $guruBesar->displayFakultas() }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Program Studi</span>
                                <strong>{{ $guruBesar->displayProdi() }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>TMT Jabatan</span>
                                <strong>{{ $guruBesar->tmt?->translatedFormat('d F Y') ?: '-' }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Tahun Pengukuhan</span>
                                <strong>{{ $archiveYear ?: '-' }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Tanggal Pelaksanaan</span>
                                <strong>{{ $orasi?->tanggal_pelaksanaan?->translatedFormat('d F Y') ?: '-' }}</strong>
                            </div>
                            <div class="orasi-detail-data-item">
                                <span>Format Pelaksanaan</span>
                                <strong>{{ $orasi?->jenis ? Str::title($orasi->jenis) : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($hasPiagamPreview)
                <div class="row g-4 orasi-detail-pdf-section" id="pratinjau-piagam-orasi">
                    <div class="col-12">
                        <div class="orasi-detail-card orasi-detail-pdf-panel">
                            <div class="orasi-detail-pdf-head">
                                <div class="orasi-detail-pdf-kicker">Piagam Orasi</div>
                                <h3>Pratinjau Piagam Orasi Ilmiah</h3>
                                <p>Lihat piagam pengukuhan guru besar yang telah dipublikasikan melalui portal Orasi Ilmiah Universitas Mulawarman.</p>
                            </div>
                            <div class="orasi-detail-pdf-grid is-single">
                                <div class="orasi-detail-pdf-item is-piagam">
                                    <div class="orasi-detail-pdf-toolbar">
                                        <div class="orasi-detail-pdf-toolbar-copy">
                                            <strong>Piagam Orasi Ilmiah Guru Besar</strong>
                                            <span>{{ basename($guruBesar->piagam_path) }}</span>
                                        </div>
                                        <a href="{{ $piagamUrl }}" target="_blank" rel="noopener noreferrer" class="orasi-detail-pdf-open">
                                            <i class="fa-solid fa-up-right-from-square" aria-hidden="true"></i>
                                            Buka {{ $piagamIsPdf ? 'PDF' : 'File' }}
                                        </a>
                                    </div>
                                    @if ($piagamIsPdf)
                                        <div class="orasi-detail-pdf-frame-wrap">
                                            <iframe
                                                src="{{ $piagamUrl }}#view=FitH&toolbar=1"
                                                title="Pratinjau piagam orasi {{ $guruBesar->nama }}"
                                                loading="lazy"
                                            ></iframe>
                                        </div>
                                    @else
                                        <div class="orasi-detail-pdf-image-wrap">
                                            <img
                                                src="{{ $piagamUrl }}"
                                                alt="Piagam orasi ilmiah {{ $guruBesar->nama }}"
                                                loading="lazy"
                                            >
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($hasOrasiDocuments)
                <div class="row g-4 mt-1" id="dokumen-orasi-detail">
                    <div class="col-12">
                        <div class="orasi-detail-card">
                            <h3>Dokumen Orasi Ilmiah</h3>
                            <div class="orasi-archive-prof-preview-row{{ ($hasNaskah && $hasPresentasi) ? '' : ' is-single' }}">
                                <div class="orasi-archive-prof-preview-slot">
                                    @if ($hasNaskah)
                                        @include('partials.home.document-preview-card', [
                                            'document' => [
                                                'variant' => 'manuscript',
                                                'person' => $guruBesar->nama,
                                                'judul' => $judulOrasi ?: 'Orasi Ilmiah Guru Besar',
                                                'year' => $archiveYear ?? $orasi?->tahun ?? date('Y'),
                                                'file' => $guruBesar->file_orasi_path,
                                                'filename' => basename($guruBesar->file_orasi_path),
                                                'extension' => strtoupper(pathinfo($guruBesar->file_orasi_path, PATHINFO_EXTENSION) ?: 'FILE'),
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
                                                'person' => $guruBesar->nama,
                                                'judul' => $judulOrasi ?: 'Orasi Ilmiah Guru Besar',
                                                'year' => $archiveYear ?? $orasi?->tahun ?? date('Y'),
                                                'file' => $guruBesar->ppt_path,
                                                'filename' => basename($guruBesar->ppt_path),
                                                'extension' => strtoupper(pathinfo($guruBesar->ppt_path, PATHINFO_EXTENSION) ?: 'FILE'),
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
                </div>
            @else
                <div class="row g-4 mt-1" id="dokumen-orasi-detail">
                    <div class="col-12">
                        <div class="orasi-detail-card">
                            <h3>Dokumen Orasi Ilmiah</h3>
                            <div class="orasi-empty">
                                Dokumen orasi belum tersedia untuk profil guru besar ini.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($youtubeEmbedUrl)
                <div class="row g-4 mt-1" id="video-orasi-detail">
                    <div class="col-12">
                        <div class="orasi-detail-card">
                            <h3>Video Orasi Ilmiah</h3>
                            <div class="orasi-detail-video-frame">
                                <iframe
                                    src="{{ $youtubeEmbedUrl }}"
                                    title="Video orasi {{ $guruBesar->nama }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                    referrerpolicy="strict-origin-when-cross-origin"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($relatedByYear->isNotEmpty())
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="orasi-detail-card orasi-detail-related-section">
                            <h3>Guru Besar Lain pada Tahun {{ $orasi?->tahun }}</h3>
                            <div
                                class="orasi-detail-related-swiper"
                                data-related-count="{{ $relatedByYear->count() }}"
                            >
                                <div class="swiper-wrapper">
                                    @foreach ($relatedByYear as $relatedGuru)
                                        @php
                                            $cleanName = preg_replace('/\b(prof|dr|ir|mt|mpd|msi|mh|mp|phd|sh|st)\b\.?/i', ' ', $relatedGuru->nama);
                                            $initials = collect(preg_split('/\s+/', preg_replace('/[^A-Za-z ]/', ' ', $cleanName) ?: ''))
                                                ->filter()
                                                ->take(2)
                                                ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))
                                                ->implode('');
                                            $useFullOverlay = $relatedGuru->usesFullPngOverlay();
                                        @endphp
                                        <div class="swiper-slide">
                                            <a
                                                href="{{ route('portal.guru-besar.show', $relatedGuru) }}"
                                                class="orasi-detail-related-link"
                                                aria-label="Profil {{ $relatedGuru->nama }}"
                                            >
                                                <div class="orasi-detail-related-poster{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                                    <div class="orasi-detail-related-poster-title">
                                                        Orasi Ilmiah<br>Guru Besar
                                                        <span>UNIVERSITAS MULAWARMAN</span>
                                                    </div>
                                                    <div class="orasi-detail-related-photo-wrap{{ $useFullOverlay ? ' is-full-overlay' : '' }}">
                                                        @if ($relatedGuru->foto_path)
                                                            <img
                                                                src="{{ asset('storage/' . $relatedGuru->foto_path) }}"
                                                                class="orasi-detail-related-photo{{ $useFullOverlay ? ' is-full-overlay' : '' }}"
                                                                alt=""
                                                            >
                                                        @else
                                                            <div class="orasi-detail-related-placeholder">{{ $initials ?: 'GB' }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
    <script src="{{ asset('js/swiper.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const relatedSwiperEl = document.querySelector('.orasi-detail-related-swiper');

            if (!relatedSwiperEl || typeof Swiper === 'undefined') {
                return;
            }

            const relatedCount = Number(relatedSwiperEl.dataset.relatedCount || 0);
            const shouldAutoplay = relatedCount > 1;
            const shouldLoop = relatedCount > 4;

            new Swiper(relatedSwiperEl, {
                spaceBetween: 12,
                speed: 900,
                loop: shouldLoop,
                rewind: shouldAutoplay && !shouldLoop,
                autoplay: shouldAutoplay ? {
                    delay: 3200,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                } : false,
                grabCursor: true,
                watchOverflow: true,
                observer: true,
                observeParents: true,
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    576: {
                        slidesPerView: 3,
                        spaceBetween: 12,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 12,
                    },
                },
            });
        });
    </script>
@endpush
