<div class="offcanvas-lg offcanvas-start admin-sidebar" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
    <div class="offcanvas-header d-lg-none">
        <h5 class="offcanvas-title" id="adminSidebarLabel">Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3 p-lg-4">
        <div class="admin-brand mb-4">
            <img src="{{ asset('logo/unmul-20260408145731-e033c2.png') }}" alt="Universitas Mulawarman">
            <div class="lh-sm">
                <div class="fw-bold">Admin Orasi</div>
                <div class="text-muted small">Universitas Mulawarman</div>
            </div>
        </div>

        <nav class="nav flex-column nav-admin gap-1">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.orasi-ilmiah.*') ? 'active' : '' }}" href="{{ route('admin.orasi-ilmiah.index') }}">
                <i class="bi bi-megaphone"></i> Orasi Ilmiah
            </a>
            <a class="nav-link {{ request()->routeIs('admin.guru-besar.*') ? 'active' : '' }}" href="{{ route('admin.guru-besar.index') }}">
                <i class="bi bi-person-badge"></i> Guru Besar
            </a>
            <a class="nav-link {{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}" href="{{ route('admin.statistics.index') }}">
                <i class="bi bi-bar-chart"></i> Statistic
            </a>
            <a class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}" href="{{ route('admin.pengumuman.index') }}">
                <i class="bi bi-bell"></i> Pengumuman
            </a>
            <a class="nav-link {{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}" href="{{ route('admin.arsip.index') }}">
                <i class="bi bi-archive"></i> Arsip
            </a>
            <a class="nav-link {{ request()->routeIs('admin.security.*') ? 'active' : '' }}" href="{{ route('admin.security.index') }}">
                <i class="bi bi-shield-lock"></i> Security
            </a>

            <div class="nav-section-label">Master Data</div>
            <a class="nav-link {{ request()->routeIs('admin.fakultas.*') ? 'active' : '' }}" href="{{ route('admin.fakultas.index') }}">
                <i class="bi bi-building"></i> Fakultas
            </a>
            <a class="nav-link {{ request()->routeIs('admin.prodi.*') ? 'active' : '' }}" href="{{ route('admin.prodi.index') }}">
                <i class="bi bi-diagram-3"></i> Prodi
            </a>
        </nav>

        <div class="mt-4 p-3 rounded-3" style="background: var(--admin-yellow-soft); border: 1px solid rgba(146, 64, 14, .12);">
            <div class="fw-semibold mb-1">Catatan</div>
            <div class="small text-muted">
                <strong>Orasi</strong> = event per tahun.<br>
                <strong>Guru Besar</strong> = master data (API/manual), ditugaskan ke satu orasi.
            </div>
        </div>
    </div>
</div>

