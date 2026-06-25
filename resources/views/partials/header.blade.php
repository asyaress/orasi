@push('styles')
    <style>
        .orasi-brand-logos {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            max-width: 100%;
        }

        .orasi-brand-logo-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
        }

        .orasi-brand-logo-item img {
            max-height: 42px;
            width: auto;
            max-width: 98px;
            object-fit: contain;
        }

        .orasi-header-main {
            padding-top: 0;
        }

        .orasi-mobile-drawer-head {
            display: none !important;
        }

        body.orasi-public-menu-open {
            overflow: hidden;
            touch-action: none;
        }

        header.header-full.transparent .de-flex {
            align-items: center;
            min-height: 78px;
        }

        header.header-full.transparent {
            top: 0;
            left: 0;
            width: 100%;
            transition: background 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        /*
         * Navbar kuning tema guru besar
         */
        header.header-full.transparent:not(.smaller):not(.header-mobile) {
            background: linear-gradient(180deg, #f3bd42 0%, #efb12c 100%) !important;
            background-color: #efb12c !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            box-shadow: 0 8px 24px rgba(15, 19, 34, 0.14);
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        }

        header.header-full.transparent:not(.smaller):not(.header-mobile) .container-fluid,
        header.header-full.transparent:not(.smaller):not(.header-mobile) .de-flex,
        header.header-full.transparent:not(.smaller):not(.header-mobile) .row,
        header.header-full.transparent:not(.smaller):not(.header-mobile) .col-md-12 {
            background: transparent !important;
        }

        /* Menu gelap di atas navbar kuning */
        header.header-full.transparent:not(.smaller):not(.header-mobile) .orasi-nav-shift #mainmenu a,
        header.header-full.transparent:not(.smaller):not(.header-mobile) .orasi-nav-shift #mainmenu > li::before {
            color: #18213a !important;
            opacity: 0.96;
        }

        header.header-full.transparent:not(.smaller):not(.header-mobile) .orasi-nav-shift #mainmenu a:hover {
            opacity: 1;
        }

        /*
         * Saat scroll tetap kuning, sedikit lebih solid
         */
        header.header-full.transparent.smaller:not(.header-mobile) {
            background: linear-gradient(180deg, #efb12c 0%, #e6a91f 100%) !important;
            background-color: #e6a91f !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 10px 26px rgba(15, 19, 34, 0.16);
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }

        header.header-full.transparent.smaller:not(.header-mobile) .orasi-nav-shift #mainmenu a,
        header.header-full.transparent.smaller:not(.header-mobile) .orasi-nav-shift #mainmenu > li::before {
            color: #18213a !important;
            opacity: 0.96;
        }

        header.header-full.transparent.smaller:not(.header-mobile) .orasi-nav-shift #mainmenu a:hover {
            opacity: 1;
        }

        /* Mobile: panel menu kuning */
        header.header-full.transparent.header-mobile {
            background: linear-gradient(180deg, #f3bd42 0%, #efb12c 100%) !important;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }

        header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a,
        header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu > li::before {
            color: #18213a !important;
        }

        header.header-full.transparent .container-fluid {
            position: relative;
            z-index: 100;
        }

        #logo {
            display: flex;
            align-items: center;
            height: 78px;
        }

        #logo a {
            display: inline-flex;
            align-items: center;
        }

        .orasi-nav-shift {
            display: flex;
            justify-content: center;
            padding: 0 28px;
            flex: 1 1 auto;
            min-width: 0;
        }

        .orasi-nav-shift #mainmenu {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            width: auto;
            margin: 0 auto;
        }

        .orasi-nav-shift #mainmenu > li {
            display: flex;
            align-items: center;
            margin-right: 28px;
        }

        .orasi-nav-shift #mainmenu > li:last-child {
            margin-right: 0;
        }

        .orasi-nav-shift #mainmenu > li::before {
            content: none !important;
            display: none !important;
        }

        .orasi-nav-shift #mainmenu a {
            display: inline-flex;
            align-items: center;
            position: relative;
            padding-top: 0;
            padding-bottom: 0;
            padding-left: 8px;
            padding-right: 8px;
            min-height: 78px;
            white-space: nowrap;
            font-weight: 700;
            transition: color 0.25s ease, opacity 0.25s ease;
        }

        header.header-full.transparent.smaller .de-flex,
        header.header-full.transparent.header-mobile .de-flex {
            min-height: 68px;
        }

        header.header-full.transparent.smaller #logo,
        header.header-full.transparent.header-mobile #logo {
            height: 68px;
        }

        header.header-full.transparent.smaller .orasi-nav-shift #mainmenu a,
        header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a {
            min-height: 68px;
        }

        .orasi-nav-shift #mainmenu a.active {
            background: transparent;
        }

        .orasi-nav-shift #mainmenu a.active::after {
            content: "";
            position: absolute;
            left: 8px;
            right: 8px;
            bottom: 18px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #18213a 0%, rgba(24, 33, 58, 0.38) 100%);
        }

        .orasi-nav-shift #mainmenu a:hover {
            background: transparent;
            opacity: 1;
        }

        .orasi-header-actions {
            min-width: 0;
            justify-content: flex-end;
            overflow: visible;
        }

        .orasi-header-actions .menu_side_area {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            white-space: nowrap;
        }

        header:not(.header-mobile) .orasi-header-actions #menu-btn {
            display: none !important;
        }

        @media (max-width: 1919.98px) {
            .orasi-brand-logo-item {
                height: 38px;
            }

            .orasi-brand-logo-item img {
                max-height: 38px;
                max-width: 82px;
            }

            .orasi-nav-shift {
                padding: 0 16px;
            }

            .orasi-nav-shift #mainmenu > li {
                margin-right: 22px;
            }

            .orasi-nav-shift #mainmenu > li::before {
                margin-right: 4px;
                font-size: 0.92em;
            }

            .orasi-nav-shift #mainmenu a {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 1599.98px) {
            .orasi-brand-logo-item {
                height: 40px;
            }

            .orasi-brand-logo-item img {
                max-height: 40px;
                max-width: 88px;
            }

            .orasi-nav-shift {
                padding: 0 14px;
            }

            .orasi-nav-shift #mainmenu > li {
                margin-right: 18px;
            }
        }

        @media (max-width: 1399.98px) {
            .orasi-brand-logo-item {
                height: 38px;
            }

            .orasi-brand-logo-item img {
                max-height: 38px;
                max-width: 84px;
            }

            .orasi-nav-shift {
                padding: 0 18px;
            }

            .orasi-nav-shift #mainmenu > li {
                margin-right: 14px;
            }

            .orasi-nav-shift #mainmenu a {
                font-size: 0.88rem;
            }
        }

        @media (max-width: 991.98px) {
            :root {
                --orasi-mobile-header-height: 64px;
            }

            .orasi-header-main {
                padding-top: 0;
            }

            header.header-mobile,
            header.header-mobile.menu-open,
            header.header-mobile.orasi-menu-open {
                overflow: visible;
                height: auto !important;
                z-index: 1100;
            }

            header.header-full.transparent .de-flex {
                position: relative;
                min-height: 64px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            #logo {
                height: 64px;
                min-width: 0;
            }

            .orasi-brand-logos {
                gap: 6px;
                max-width: calc(100vw - 96px);
                overflow-x: auto;
                scrollbar-width: none;
            }

            .orasi-brand-logos::-webkit-scrollbar {
                display: none;
            }

            .orasi-brand-logo-item {
                flex: 0 0 auto;
                height: 32px;
            }

            .orasi-brand-logo-item img {
                max-height: 32px;
                max-width: 64px;
            }

            .orasi-nav-shift {
                position: fixed;
                top: var(--orasi-mobile-header-height);
                left: 0;
                right: 0;
                bottom: auto;
                display: none;
                width: auto;
                max-width: 100%;
                height: auto;
                padding: 0;
                flex: none;
                background: linear-gradient(180deg, #f3bd42 0%, #eeb12b 100%);
                border-top: 1px solid rgba(24, 33, 58, 0.10);
                box-shadow: 0 18px 34px rgba(15, 19, 34, 0.16);
                z-index: 1099;
                opacity: 1;
                pointer-events: auto;
                transform: none;
            }

            header.header-mobile.orasi-menu-open .orasi-nav-shift {
                display: block;
            }

            .orasi-mobile-drawer-head {
                display: none;
            }

            .orasi-nav-shift #mainmenu {
                position: static !important;
                display: block !important;
                width: 100% !important;
                height: auto !important;
                max-height: calc(100svh - var(--orasi-mobile-header-height));
                margin: 0 !important;
                padding: 6px max(16px, env(safe-area-inset-right)) 10px max(16px, env(safe-area-inset-left)) !important;
                overflow-y: auto;
                background: transparent;
                transform: none !important;
            }

            .orasi-nav-shift #mainmenu > li,
            header.header-mobile #mainmenu li {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                border-bottom: 1px solid rgba(24, 33, 58, 0.12);
            }

            .orasi-nav-shift #mainmenu > li:last-child,
            header.header-mobile #mainmenu li:last-child {
                border-bottom: 0;
            }

            .orasi-nav-shift #mainmenu > li::before {
                display: none;
            }

            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a,
            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a.menu-item {
                display: flex !important;
                align-items: center;
                justify-content: flex-start;
                width: 100% !important;
                min-height: 50px !important;
                padding: 13px 8px !important;
                color: #18213a !important;
                text-align: left !important;
                white-space: normal !important;
                line-height: 1.25;
                background: transparent !important;
                border: 0;
                border-radius: 0;
                pointer-events: auto;
                transition: background 0.2s ease, color 0.2s ease;
            }

            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a::after {
                display: none;
            }

            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a.active::after {
                display: block;
                left: 8px;
                right: auto;
                bottom: 8px;
                width: min(92px, calc(100% - 16px));
                height: 3px;
                background: rgba(24, 33, 58, 0.58);
            }

            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a.active {
                background: rgba(255, 255, 255, 0.16) !important;
                color: #18213a !important;
                box-shadow: none;
            }

            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a.active::after {
                color: #18213a;
            }

            header.header-full.transparent.header-mobile .orasi-nav-shift #mainmenu a:hover {
                background: rgba(255, 255, 255, 0.18) !important;
            }

            .orasi-header-actions {
                width: 44px;
                min-width: 44px;
                overflow: visible;
            }

            .orasi-header-actions .menu_side_area {
                min-height: 64px;
                display: flex;
                justify-content: flex-end;
            }

            header.header-mobile .orasi-header-actions #menu-btn {
                display: inline-block !important;
                margin: 0;
                color: #fff;
                position: relative;
                z-index: 1105;
                cursor: pointer;
                touch-action: manipulation;
            }

            header.header-mobile .orasi-header-actions #menu-btn.orasi-menu-open::before {
                content: "\f068";
            }
        }

        @media (max-width: 767.98px) {
            .orasi-brand-logos {
                max-width: calc(100vw - 76px);
            }

            .orasi-brand-logo-item {
                height: 28px;
            }

            .orasi-brand-logo-item img {
                max-height: 28px;
                max-width: 54px;
            }
        }

        @media (max-width: 430px) {
            :root {
                --orasi-mobile-header-height: 58px;
            }

            .orasi-header-main {
                padding-top: 0;
            }

            header.header-full.transparent .de-flex {
                min-height: 58px;
            }

            #logo {
                height: 58px;
            }

            .orasi-brand-logos {
                max-width: calc(100vw - 86px);
            }

            .orasi-brand-logo-item {
                height: 24px;
            }

            .orasi-brand-logo-item img {
                max-height: 24px;
                max-width: 46px;
            }

            .orasi-header-actions .menu_side_area {
                min-height: 58px;
            }

            .orasi-nav-shift {
                width: auto;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            var mobileQuery = window.matchMedia('(max-width: 993px)');

            function closePublicMenu() {
                var header = document.querySelector('header.header-full.transparent');
                var button = document.getElementById('menu-btn');

                if (!header || !button) {
                    return;
                }

                header.classList.remove('menu-open', 'orasi-menu-open');
                header.style.height = 'auto';
                button.classList.remove('menu-open', 'orasi-menu-open');
                button.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('orasi-public-menu-open');
            }

            function togglePublicMenu(event) {
                if (!mobileQuery.matches) {
                    return;
                }

                var header = document.querySelector('header.header-full.transparent');
                var button = document.getElementById('menu-btn');

                if (!header || !button) {
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();

                var shouldOpen = !header.classList.contains('orasi-menu-open');
                header.classList.remove('menu-open');
                header.classList.toggle('orasi-menu-open', shouldOpen);
                header.style.height = 'auto';
                button.classList.remove('menu-open');
                button.classList.toggle('orasi-menu-open', shouldOpen);
                button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                document.body.classList.toggle('orasi-public-menu-open', shouldOpen);
            }

            function bindPublicMenu(shouldClose) {
                var button = document.getElementById('menu-btn');
                var links = document.querySelectorAll('header.header-full.transparent #mainmenu a');

                if (shouldClose !== false) {
                    closePublicMenu();
                }

                if (button) {
                    if (window.jQuery) {
                        window.jQuery(button).off('click');
                    }

                    button.setAttribute('role', 'button');
                    button.setAttribute('tabindex', '0');
                    button.setAttribute('aria-label', 'Buka menu navigasi');
                    button.setAttribute('aria-expanded', 'false');
                }

                if (button && button.dataset.orasiMenuBound !== '1') {
                    button.dataset.orasiMenuBound = '1';
                    button.addEventListener('click', togglePublicMenu, true);
                    button.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter' || event.key === ' ') {
                            togglePublicMenu(event);
                        }
                    }, true);
                }

                links.forEach(function (link) {
                    if (link.dataset.orasiMenuCloseBound === '1') {
                        return;
                    }

                    link.dataset.orasiMenuCloseBound = '1';
                    link.addEventListener('click', closePublicMenu);
                });
            }

            bindPublicMenu();
            document.addEventListener('DOMContentLoaded', function () {
                bindPublicMenu();
                window.setTimeout(function () {
                    bindPublicMenu();
                    closePublicMenu();
                }, 120);
            });
            window.addEventListener('load', function () {
                bindPublicMenu(false);
            });
            window.addEventListener('pageshow', closePublicMenu);
            document.addEventListener('click', function (event) {
                if (!mobileQuery.matches || !event.target.closest('#menu-btn')) {
                    return;
                }

                togglePublicMenu(event);
            }, true);
            document.addEventListener('click', function (event) {
                var header = document.querySelector('header.header-full.transparent');

                if (!mobileQuery.matches || !header || !header.classList.contains('orasi-menu-open')) {
                    return;
                }

                if (event.target.closest('.orasi-nav-shift, #menu-btn')) {
                    return;
                }

                closePublicMenu();
            }, true);
            window.addEventListener('resize', function () {
                if (!mobileQuery.matches) {
                    closePublicMenu();
                }
            }, { passive: true });
        })();
    </script>
@endpush

<header class="header-full transparent">
    <div class="container-fluid orasi-header-main">
        <div class="row">
            <div class="col-md-12">
                <div class="de-flex sm-pt10">
                    <div class="de-flex-col">
                        <div class="de-flex-col">
                            <div id="logo">
                                <a href="{{ route('home') }}">
                                    @include('partials.brand-logos')
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="de-flex-col header-col-mid orasi-nav-shift">
                        <div class="orasi-mobile-drawer-head" aria-hidden="true">
                            <div class="orasi-mobile-drawer-kicker">Navigasi Portal</div>
                            <div class="orasi-mobile-drawer-title">Orasi Ilmiah Guru Besar Universitas Mulawarman</div>
                        </div>
                        <ul id="mainmenu">
                            <li><a class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                            <li><a class="menu-item {{ request()->routeIs('portal.guru-besar*') ? 'active' : '' }}" href="{{ route('portal.guru-besar') }}">Guru Besar</a></li>
                            <li><a class="menu-item {{ request()->routeIs('portal.daftar-orasi') ? 'active' : '' }}" href="{{ route('portal.daftar-orasi') }}">Daftar Orasi</a></li>
                            <li><a class="menu-item {{ request()->routeIs('portal.video-orasi') ? 'active' : '' }}" href="{{ route('portal.video-orasi') }}">Video Orasi</a></li>
                            <li><a class="menu-item {{ request()->routeIs('portal.dokumen-orasi') ? 'active' : '' }}" href="{{ route('portal.dokumen-orasi') }}">Dokumen Orasi</a></li>
                            <li><a class="menu-item {{ request()->routeIs('portal.statistik') ? 'active' : '' }}" href="{{ route('portal.statistik') }}">Statistik</a></li>
                        </ul>
                    </div>
                    <div class="de-flex-col orasi-header-actions">
                        <div class="menu_side_area">
                            <span id="menu-btn"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
