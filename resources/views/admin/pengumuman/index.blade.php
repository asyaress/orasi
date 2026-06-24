@extends('admin.layouts.app')

@section('title', 'Pengumuman — Admin')
@section('page_title', 'Pengumuman')
@section('page_subtitle', 'Kelola pengumuman website — export Excel/PDF tersedia')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">Pencarian global &amp; export di toolbar tabel.</div>
                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Pengumuman
                    </a>
                </div>
            </div>

            <div class="admin-table-wrap mt-3">
                <table class="table table-striped admin-datatable w-100" data-order='[[3,"desc"]]'>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Pinned</th>
                            <th>Tayang</th>
                            <th class="no-sort no-export" style="width:120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->judul }}</td>
                                <td class="text-muted small">{{ $item->slug }}</td>
                                <td><span class="badge bg-secondary">{{ $item->status }}</span></td>
                                <td>
                                    @if ($item->is_pinned)
                                        <span class="badge badge-soft-yellow">Pinned</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-order="{{ optional($item->published_at)->format('Y-m-d H:i:s') ?: '' }}">
                                    {{ optional($item->published_at)->format('d-m-Y H:i') ?: '—' }}
                                </td>
                                <td class="no-export">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.pengumuman.edit', $item) }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
