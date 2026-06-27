<head>
    <title>@yield('title', 'Orasi Ilmiah Guru Besar Universitas Mulawarman')</title>
    <link rel="icon" href="{{ asset('logo/unmul-20260408145731-e033c2.png') }}" type="image/png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta
        name="description"
        content="@yield('meta_description', 'Portal Orasi Ilmiah Guru Besar Universitas Mulawarman berisi agenda, video YouTube, dokumen, dan arsip orasi ilmiah.')"
    >
    <meta name="keywords" content="orasi ilmiah, guru besar, universitas mulawarman, unmul">
    <meta name="author" content="Universitas Mulawarman">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="Orasi Ilmiah Guru Besar Universitas Mulawarman">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Orasi Ilmiah Guru Besar Universitas Mulawarman')">
    <meta property="og:description" content="@yield('og_description', 'Portal Orasi Ilmiah Guru Besar Universitas Mulawarman.')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('ChatGPT Image 2 Jun 2026, 15.52.54.png'))">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Orasi Ilmiah Guru Besar Universitas Mulawarman')">
    @hasSection('article_published_time')
        <meta property="article:published_time" content="@yield('article_published_time')">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Orasi Ilmiah Guru Besar Universitas Mulawarman')">
    <meta name="twitter:description" content="@yield('og_description', 'Portal Orasi Ilmiah Guru Besar Universitas Mulawarman.')">
    <meta name="twitter:image" content="@yield('og_image', asset('ChatGPT Image 2 Jun 2026, 15.52.54.png'))">
    <!-- CSS Files
    ================================================== -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="{{ asset('css/plugins.css') }}" rel="stylesheet" type="text/css">
    @if (request()->routeIs('portal.guru-besar.show'))
        <link href="{{ asset('css/swiper.css') }}" rel="stylesheet" type="text/css">
    @endif
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/coloring.css') }}" rel="stylesheet" type="text/css">
    <link id="colors" href="{{ asset('css/colors/scheme-01.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/orasi-public.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/orasi-document-card.css') }}" rel="stylesheet" type="text/css">
    @if (request()->routeIs('home', 'portal.pengumuman.*'))
        <link href="{{ asset('css/orasi-pengumuman.css') }}" rel="stylesheet" type="text/css">
    @endif
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background: #0f1322;
            overflow-x: hidden;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            touch-action: pan-y pinch-zoom;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        body {
            overscroll-behavior-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        #wrapper {
            background: #0f1322;
        }

        body.page-home,
        body.page-home #wrapper {
            background: transparent;
        }

        /* Cegah logo membesar sebelum CSS header selesai dimuat */
        .orasi-brand-logos {
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 100%;
        }

        .orasi-brand-logo-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            flex: 0 0 auto;
        }

        .orasi-brand-logo-item img {
            display: block;
            max-height: 42px;
            max-width: 98px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        #logo {
            display: flex;
            align-items: center;
            height: 78px;
            max-width: min(100%, 620px);
            overflow: hidden;
        }

        #logo a {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
        }
    </style>
</head>
