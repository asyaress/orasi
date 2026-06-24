<head>
    <title>@yield('title', 'Orasi Ilmiah Guru Besar Universitas Mulawarman')</title>
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/gif" sizes="16x16">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta
        name="description"
        content="@yield('meta_description', 'Portal Orasi Ilmiah Guru Besar Universitas Mulawarman berisi agenda, video YouTube, dokumen, dan arsip orasi ilmiah.')"
    >
    <meta name="keywords" content="orasi ilmiah, guru besar, universitas mulawarman, unmul">
    <meta name="author" content="Universitas Mulawarman">
    <!-- CSS Files
    ================================================== -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="{{ asset('css/plugins.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/swiper.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/coloring.css') }}" rel="stylesheet" type="text/css">
    <link id="colors" href="{{ asset('css/colors/scheme-01.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/orasi-public.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/orasi-document-card.css') }}" rel="stylesheet" type="text/css">
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
    </style>
</head>
