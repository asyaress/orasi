@extends('admin.layouts.app')

@section('title', 'Detail Orasi — Admin')
@section('page_title', 'Detail Orasi')
@section('page_subtitle', 'Ringkasan data dan file terlampir')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">{{ $orasi->judul }}</div>
            <div class="text-muted small">
                {{ $orasi->pegawai_nama ?: 'Nama (API/Manual)' }}
                @if ($orasi->fakultas)
                    · {{ $orasi->fakultas }}
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.orasi.edit', $orasi) }}">Edit</a>
            <a class="btn btn-yellow" href="{{ route('admin.orasi.index') }}">Kembali</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Tanggal Pelaksanaan</div>
                            <div class="fw-semibold">{{ optional($orasi->tanggal_pelaksanaan)->format('d-m-Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Jenis</div>
                            <div class="fw-semibold">{{ $orasi->jenis }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Periode Pendaftaran</div>
                            <div class="fw-semibold">
                                @if ($orasi->pendaftaran_mulai || $orasi->pendaftaran_selesai)
                                    {{ optional($orasi->pendaftaran_mulai)->format('d-m-Y') ?: '—' }}
                                    s/d
                                    {{ optional($orasi->pendaftaran_selesai)->format('d-m-Y') ?: '—' }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold">
                                <span class="badge bg-secondary">{{ $orasi->status }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">YouTube URL</div>
                            <div class="fw-semibold">
                                @if ($orasi->youtube_url)
                                    <a href="{{ $orasi->youtube_url }}" target="_blank" rel="noreferrer">{{ $orasi->youtube_url }}</a>
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Data Dosen</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Pegawai ID</div>
                            <div class="fw-semibold">{{ $orasi->pegawai_id ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">TMT</div>
                            <div class="fw-semibold">{{ optional($orasi->tmt)->format('d-m-Y') ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Bidang Ilmu</div>
                            <div class="fw-semibold">{{ $orasi->bidang_ilmu ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Prodi</div>
                            <div class="fw-semibold">{{ $orasi->prodi ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Media & File</div>
                    <div class="d-grid gap-2">
                        @php($files = [
                            'Banner' => $orasi->banner_path,
                            'Foto' => $orasi->foto_path,
                            'File Orasi' => $orasi->file_orasi_path,
                            'PPT' => $orasi->ppt_path,
                            'Piagam' => $orasi->piagam_path,
                        ])
                        @foreach ($files as $label => $path)
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-muted small">{{ $label }}</div>
                                @if ($path)
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/'.$path) }}" target="_blank" rel="noreferrer">Buka</a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

