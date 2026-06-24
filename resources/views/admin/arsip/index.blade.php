@extends('admin.layouts.app')

@section('title', 'Arsip — Admin')
@section('page_title', 'Arsip')
@section('page_subtitle', 'Arsip Orasi Ilmiah — filter tahun via kolom Tahun di tabel')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">Semua event orasi ilmiah. Gunakan pencarian/export DataTables; filter tahun lewat kolom <strong>Tahun</strong>.</div>
            </div>

            <div class="admin-table-wrap mt-3">
                <table class="table table-striped admin-datatable w-100" data-order='[[1,"desc"]]'>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tahun</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th class="no-sort no-export" style="width:120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row->judul }}</td>
                                <td>{{ $row->tahun }}</td>
                                <td data-order="{{ optional($row->tanggal_pelaksanaan)->format('Y-m-d') ?: '' }}">
                                    {{ optional($row->tanggal_pelaksanaan)->format('d-m-Y') ?: '—' }}
                                </td>
                                <td>{{ $row->jenis }}</td>
                                <td><span class="badge bg-secondary">{{ $row->status }}</span></td>
                                <td class="no-export">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orasi-ilmiah.show', $row) }}">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
