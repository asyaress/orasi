@extends('admin.layouts.app')

@section('title', 'Daftar Orasi — Admin')
@section('page_title', 'Daftar Orasi')
@section('page_subtitle', 'Kelola Orasi Ilmiah (banner, video YouTube, file, dan data dosen)')

@section('content')
    <div class="admin-card">
        <div class="p-3 p-lg-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <form class="d-flex flex-wrap gap-2" method="get" action="{{ route('admin.orasi.index') }}">
                    <div class="input-group" style="min-width:280px">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input class="form-control" name="q" value="{{ $q }}" placeholder="Cari judul / nama / fakultas">
                    </div>
                    <select class="form-select" name="status" style="min-width:200px">
                        <option value="">Semua status</option>
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                        <option value="published" @selected($status === 'published')>Published</option>
                        <option value="archived" @selected($status === 'archived')>Archived</option>
                    </select>
                    <button class="btn btn-outline-secondary btn-admin" type="submit">Filter</button>
                </form>

                <a href="{{ route('admin.orasi.create') }}" class="btn btn-admin btn-admin-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Orasi
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">No.</th>
                            <th>Banner</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Periode Pendaftaran</th>
                            <th style="width:180px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orasis as $idx => $orasi)
                            <tr>
                                <td>{{ $orasis->firstItem() + $idx }}</td>
                                <td>
                                    @if ($orasi->banner_path)
                                        <img src="{{ asset('storage/'.$orasi->banner_path) }}" alt="banner" style="width:120px;height:64px;object-fit:cover;border-radius:12px;">
                                    @else
                                        <div class="text-muted small">—</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $orasi->judul }}</div>
                                    <div class="text-muted small">
                                        {{ $orasi->pegawai_nama ?: 'Nama (API/Manual)' }}
                                        @if ($orasi->fakultas)
                                            · {{ $orasi->fakultas }}
                                        @endif
                                    </div>
                                    <div class="mt-1 d-flex gap-1 flex-wrap">
                                        <span class="badge bg-secondary">{{ $orasi->status }}</span>
                                        @if ($orasi->youtube_url)
                                            <span class="badge badge-soft-yellow">YouTube</span>
                                        @endif
                                        @if ($orasi->ppt_path)
                                            <span class="badge bg-light text-dark border">PPT</span>
                                        @endif
                                        @if ($orasi->piagam_path)
                                            <span class="badge bg-light text-dark border">Piagam</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ optional($orasi->tanggal_pelaksanaan)->format('d-m-Y') }}</td>
                                <td>{{ $orasi->jenis }}</td>
                                <td class="text-muted small">
                                    @if ($orasi->pendaftaran_mulai || $orasi->pendaftaran_selesai)
                                        {{ optional($orasi->pendaftaran_mulai)->format('d-m-Y') ?: '—' }}
                                        s/d
                                        {{ optional($orasi->pendaftaran_selesai)->format('d-m-Y') ?: '—' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orasi.show', $orasi) }}">Detail</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orasi.edit', $orasi) }}">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="admin-empty">
                                        Belum ada data orasi.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orasis->links() }}
            </div>
        </div>
    </div>
@endsection

