@extends('admin.layouts.app')

@section('title', 'Prodi — Admin')
@section('page_title', 'Prodi')
@section('page_subtitle', 'Master data prodi — export Excel/PDF tersedia')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">Filter per fakultas lewat pencarian kolom Fakultas.</div>
                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.prodi.create') }}" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Prodi
                    </a>
                </div>
            </div>

            <div class="admin-table-wrap mt-3">
                <table class="table table-striped admin-datatable w-100" data-order='[[0,"asc"]]'>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Fakultas</th>
                            <th>Jenjang</th>
                            <th>Aktif</th>
                            <th class="no-sort no-export" style="width:160px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row->nama }}</td>
                                <td>{{ $row->kode ?: '—' }}</td>
                                <td>{{ $row->fakultas?->nama ?: '—' }}</td>
                                <td>{{ $row->jenjang ?: '—' }}</td>
                                <td>
                                    @if ($row->is_active)
                                        <span class="badge badge-soft-yellow">Aktif</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="no-export">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.prodi.edit', $row) }}">Edit</a>
                                        <form method="post" action="{{ route('admin.prodi.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Hapus prodi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                        </form>
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
