@extends('admin.layouts.app')

@section('title', 'Pengumuman — Admin')
@section('page_title', 'Pengumuman')
@section('page_subtitle', 'Kelola cover, konten, tag, dan jadwal tayang pengumuman')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">Pengumuman Published akan muncul di portal sesuai tanggal tayangnya.</div>
                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-plus-lg"></i> Tambah Pengumuman
                    </a>
                </div>
            </div>

            <div class="admin-table-wrap mt-3">
                <table class="table table-striped admin-datatable w-100" data-order='[[4,"desc"]]'>
                    <thead>
                        <tr>
                            <th class="no-sort no-export">Cover</th>
                            <th>Judul</th>
                            <th>Tag</th>
                            <th>Status</th>
                            <th>Tayang</th>
                            <th class="no-sort no-export" style="width:150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @php
                                $isScheduled = $item->status === 'published' && $item->published_at?->isFuture();
                                $badge = match (true) {
                                    $isScheduled => 'bg-info text-dark',
                                    $item->status === 'published' => 'bg-success',
                                    $item->status === 'archived' => 'bg-dark',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    @if ($item->cover_url)
                                        <img src="{{ $item->cover_url }}" alt="" style="width:72px;height:46px;object-fit:cover;border-radius:8px">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light border rounded-2 text-muted" style="width:72px;height:46px"><i class="bi bi-image"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                    <div class="text-muted small">/{{ $item->slug }}</div>
                                    @if ($item->is_pinned)<span class="badge badge-soft-yellow mt-1">Pinned</span>@endif
                                </td>
                                <td>
                                    @forelse ($item->tags ?? [] as $tag)
                                        <span class="badge text-bg-light border">{{ $tag }}</span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                                <td><span class="badge {{ $badge }}">{{ $isScheduled ? 'Terjadwal' : ucfirst($item->status) }}</span></td>
                                <td data-order="{{ optional($item->published_at)->format('Y-m-d H:i:s') ?: '' }}">
                                    {{ optional($item->published_at)->format('d-m-Y H:i') ?: '—' }}
                                </td>
                                <td class="no-export">
                                    <div class="d-flex gap-1">
                                        @if ($item->status === 'published' && (!$item->published_at || $item->published_at->isPast()))
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('portal.pengumuman.show', $item) }}" target="_blank" title="Lihat"><i class="bi bi-box-arrow-up-right"></i></a>
                                        @endif
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.pengumuman.edit', $item) }}">Edit</a>
                                        <form method="post" action="{{ route('admin.pengumuman.destroy', $item) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="bi bi-trash"></i></button>
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
