@extends('admin.layouts.app')

@section('title', 'Orasi Ilmiah — Admin')
@section('page_title', 'Orasi Ilmiah')
@section('page_subtitle', 'Kelola event orasi — gunakan pencarian, filter kolom, export Excel/PDF')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">Pencarian &amp; export tersedia di toolbar tabel di bawah.</div>
                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.orasi-ilmiah.create') }}" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Orasi
                    </a>
                </div>
            </div>

            <div class="admin-table-wrap mt-3">
                <table class="table table-striped admin-datatable w-100" data-order='[[1,"desc"]]'>
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Tahun</th>
                            <th>Banner</th>
                            <th>Judul</th>
                            <th>Guru Besar</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Periode Pendaftaran</th>
                            <th class="no-sort no-export" style="width:160px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $idx => $row)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="badge badge-soft-yellow">{{ $row->tahun }}</span></td>
                                <td>
                                    @if ($row->banner_path)
                                        <img src="{{ asset('storage/'.$row->banner_path) }}" alt="" class="admin-thumb" style="max-width:80px">
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $row->judul }}</div>
                                </td>
                                <td>{{ $row->guru_besars_count }} orang</td>
                                <td data-order="{{ optional($row->tanggal_pelaksanaan)->format('Y-m-d') ?: '' }}">
                                    {{ optional($row->tanggal_pelaksanaan)->format('d-m-Y') ?: '—' }}
                                </td>
                                <td>{{ $row->jenis }}</td>
                                <td><span class="badge bg-secondary">{{ $row->status }}</span></td>
                                <td>
                                    @if ($row->pendaftaran_mulai || $row->pendaftaran_selesai)
                                        {{ optional($row->pendaftaran_mulai)->format('d-m-Y') ?: '—' }}
                                        s/d
                                        {{ optional($row->pendaftaran_selesai)->format('d-m-Y') ?: '—' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="no-export">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orasi-ilmiah.show', $row) }}">Detail</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orasi-ilmiah.edit', $row) }}">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
