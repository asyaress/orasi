<header class="admin-topbar">
    <div class="container-fluid py-2 py-md-3 d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                <i class="bi bi-list"></i>
            </button>
            <div class="min-w-0">
                <h1 class="page-title text-truncate">@yield('page_title', 'Dashboard')</h1>
                <p class="page-subtitle text-truncate mb-0 d-none d-sm-block">@yield('page_subtitle', 'Kelola konten Orasi secara cepat dan rapi')</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-admin btn-admin-sm d-none d-md-inline-flex">Lihat Website</a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-admin btn-admin-sm d-md-none" title="Lihat Website" aria-label="Lihat Website"><i class="bi bi-box-arrow-up-right"></i></a>
        </div>
    </div>
</header>

