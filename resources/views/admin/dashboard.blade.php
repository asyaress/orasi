@extends('admin.layouts.app')

@section('title', 'Dashboard — Admin Orasi')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan dan akses cepat')

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="admin-card h-100">
                <div class="admin-card-body text-center py-3">
                    <div class="text-muted small">Orasi Ilmiah</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['orasi_ilmiah']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="admin-card h-100">
                <div class="admin-card-body text-center py-3">
                    <div class="text-muted small">Guru Besar</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['guru_besar']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="admin-card h-100">
                <div class="admin-card-body text-center py-3">
                    <div class="text-muted small">Published</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['published']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="admin-card h-100">
                <div class="admin-card-body text-center py-3">
                    <div class="text-muted small">Fakultas</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['fakultas']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="admin-card h-100">
                <div class="admin-card-body text-center py-3">
                    <div class="text-muted small">Prodi</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['prodi']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="admin-card h-100">
                <div class="admin-card-body text-center py-3">
                    <div class="text-muted small">Pengumuman</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['pengumuman']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-6">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="admin-section-title">Mulai cepat</div>
                            <p class="admin-section-hint mb-0">Buat event orasi baru atau kelola konten website.</p>
                        </div>
                        <span class="badge badge-soft-yellow">Admin</span>
                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.orasi-ilmiah.create') }}" class="btn btn-admin btn-admin-primary">
                            <i class="bi bi-plus-lg"></i> Orasi Tahun Baru
                        </a>
                        <a href="{{ route('admin.guru-besar.index') }}" class="btn btn-outline-secondary btn-admin">Master Guru Besar</a>
                        <a href="{{ route('admin.orasi-ilmiah.index') }}" class="btn btn-outline-secondary btn-admin">Daftar Orasi</a>
                        <a href="{{ route('admin.statistics.index') }}" class="btn btn-outline-secondary btn-admin">Statistic</a>
                        <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-outline-secondary btn-admin">Tambah Pengumuman</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="admin-card h-100">
                <div class="admin-card-body">
                    <div class="admin-section-title">Master data &amp; keamanan</div>
                    <p class="admin-section-hint">Kelola fakultas, prodi, dan perangkat authenticator 2FA.</p>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('admin.fakultas.index') }}" class="btn btn-outline-secondary btn-admin btn-admin-sm">Fakultas</a>
                        <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-secondary btn-admin btn-admin-sm">Prodi</a>
                        <a href="{{ route('admin.security.index') }}" class="btn btn-outline-secondary btn-admin btn-admin-sm">Security</a>
                        <a href="{{ route('admin.arsip.index') }}" class="btn btn-outline-secondary btn-admin btn-admin-sm">Arsip</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
