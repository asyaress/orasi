@extends('admin.layouts.app')

@section('title', 'Orasi {{ $orasi->tahun }} — Admin')
@section('page_title', 'Orasi Ilmiah {{ $orasi->tahun }}')
@section('page_subtitle', 'Seret guru besar ke kolom kanan untuk menugaskan')

@section('content')
    <div class="admin-page-header">
        <div class="min-w-0">
            <div class="fw-bold" style="font-size:1.1rem;">{{ $orasi->judul }}</div>
            <div class="text-muted small">
                <span class="badge badge-soft-yellow me-1">Tahun {{ $orasi->tahun }}</span>
                {{ optional($orasi->tanggal_pelaksanaan)->format('d-m-Y') }} · {{ $orasi->jenis }} · <span class="badge bg-secondary">{{ $orasi->status }}</span>
            </div>
        </div>
        <div class="admin-page-header-actions">
            <a class="btn btn-outline-secondary btn-admin" href="{{ route('admin.orasi-ilmiah.edit', $orasi) }}"><i class="bi bi-pencil"></i> Edit Orasi</a>
            <a class="btn btn-admin btn-admin-primary" href="{{ route('admin.guru-besar.create', ['orasi_ilmiah_id' => $orasi->id, 'return' => 'orasi']) }}">
                <i class="bi bi-person-plus"></i> Guru Besar Baru
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="admin-card h-100">
                <div class="admin-card-body py-3">
                    <div class="text-muted small">Tahun</div>
                    <div class="fw-bold">{{ $orasi->tahun }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card h-100">
                <div class="admin-card-body py-3">
                    <div class="text-muted small">Pendaftaran</div>
                    <div class="fw-semibold small">
                        @if ($orasi->pendaftaran_mulai || $orasi->pendaftaran_selesai)
                            {{ optional($orasi->pendaftaran_mulai)->format('d-m-Y') ?: '—' }} — {{ optional($orasi->pendaftaran_selesai)->format('d-m-Y') ?: '—' }}
                        @else — @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card h-100">
                <div class="admin-card-body py-3">
                    <div class="text-muted small">Banner</div>
                    <div class="fw-semibold small">
                        @if ($orasi->banner_path)
                            <a href="{{ asset('storage/'.$orasi->banner_path) }}" target="_blank" rel="noreferrer">Lihat banner</a>
                        @else — @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        id="orasi-assign-app"
        class="admin-card"
        data-attach-url="{{ route('admin.orasi-ilmiah.guru-besar.attach', $orasi) }}"
    >
        <div class="admin-card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="admin-section-title mb-1"><i class="bi bi-arrows-move me-1"></i> Penugasan Guru Besar</div>
                    <p class="admin-section-hint mb-0">
                        <strong>Seret</strong> kartu ke kanan, <strong>klik</strong> untuk pilih lalu «Tugaskan terpilih», atau <strong>double-klik</strong> untuk langsung menugaskan.
                        Seret kembali ke kiri untuk melepas.
                    </p>
                </div>
                <span class="badge badge-soft-yellow fs-6" id="assigned-count">{{ $orasi->guruBesars->count() }} orang</span>
            </div>

            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="guru-assign-panel">
                        <div class="guru-assign-panel-head">
                            <span class="fw-semibold"><i class="bi bi-inbox me-1"></i> Tersedia</span>
                            <span class="text-muted small">belum punya orasi</span>
                        </div>
                        <div class="p-2 border-bottom">
                            <input type="search" id="guru-search" class="form-control form-control-sm" placeholder="Cari nama, NIP, fakultas…" autocomplete="off">
                        </div>
                        <div class="guru-assign-panel-actions px-2 py-2 border-bottom d-flex gap-2">
                            <button type="button" class="btn btn-admin btn-admin-primary btn-admin-sm flex-grow-1" id="btn-move-selected">
                                <i class="bi bi-arrow-right"></i> Tugaskan terpilih
                            </button>
                            <a href="{{ route('admin.guru-besar.create', ['orasi_ilmiah_id' => $orasi->id, 'return' => 'orasi']) }}" class="btn btn-outline-secondary btn-admin btn-admin-sm" title="Buat baru">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                        <ul id="list-available" class="guru-assign-list">
                            @foreach ($guruBesarTersedia as $gb)
                                @include('admin.guru-besar._assign-card', ['guruBesar' => $gb, 'list' => 'available', 'orasi' => $orasi])
                            @endforeach
                        </ul>
                        <div id="empty-available" class="guru-assign-empty @if($guruBesarTersedia->isNotEmpty()) d-none @endif">
                            <i class="bi bi-person-check"></i>
                            <div>Semua guru besar sudah ditugaskan atau belum ada data.</div>
                            <a href="{{ route('admin.guru-besar.create', ['orasi_ilmiah_id' => $orasi->id, 'return' => 'orasi']) }}" class="btn btn-outline-secondary btn-admin btn-admin-sm mt-2">Buat Guru Besar</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="guru-assign-panel guru-assign-panel-target">
                        <div class="guru-assign-panel-head">
                            <span class="fw-semibold"><i class="bi bi-calendar-event me-1"></i> Orasi {{ $orasi->tahun }}</span>
                            <span class="text-muted small">sudah ditugaskan</span>
                        </div>
                        <ul id="list-assigned" class="guru-assign-list">
                            @foreach ($orasi->guruBesars as $gb)
                                @include('admin.guru-besar._assign-card', ['guruBesar' => $gb, 'list' => 'assigned', 'orasi' => $orasi])
                            @endforeach
                        </ul>
                        <div id="empty-assigned" class="guru-assign-empty @if($orasi->guruBesars->isNotEmpty()) d-none @endif">
                            <i class="bi bi-arrow-left"></i>
                            <div>Seret guru besar dari kolom kiri ke sini.</div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-muted small mt-3 mb-0">
                <i class="bi bi-info-circle"></i> Media (YouTube, file orasi, PPT, piagam) diatur per guru — tombol <strong>Edit</strong> pada kartu di kolom kanan.
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script src="{{ asset('js/admin-orasi-assign.js') }}"></script>
@endpush
