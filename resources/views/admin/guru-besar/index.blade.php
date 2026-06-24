@extends('admin.layouts.app')

@section('title', 'Guru Besar â€” Admin')
@section('page_title', 'Guru Besar')
@section('page_subtitle', 'Master data guru besar (API kepegawaian + manual) â€” satu guru hanya satu orasi')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">
                    Kelola data guru besar di sini. Untuk menambahkan ke orasi tahun tertentu, buka detail <strong>Orasi Ilmiah</strong> â†’ pilih dari daftar ini.
                </div>
                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.guru-besar.create') }}" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-person-plus"></i> Tambah Guru Besar
                    </a>
                </div>
            </div>

            <div class="admin-table-wrap mt-3">
                <table class="table table-striped admin-datatable w-100" data-order='[[0,"asc"]]'>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Pegawai ID</th>
                            <th>Sumber</th>
                            <th>Judul Orasi</th>
                            <th>Fakultas</th>
                            <th>Prodi</th>
                            <th>Orasi / Tahun</th>
                            <th>Media</th>
                            <th class="no-sort no-export" style="width:140px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row->nama }}</td>
                                <td>{{ $row->displayJenisKelamin() }}</td>
                                <td>{{ $row->pegawai_id ?: 'â€”' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($row->sumber) }}</span></td>
                                <td>{{ $row->judul_orasi ?: 'â€”' }}</td>
                                <td>{{ $row->displayFakultas() }}</td>
                                <td>{{ $row->displayProdi() }}</td>
                                <td>
                                    @if ($row->orasiIlmiah)
                                        <a href="{{ route('admin.orasi-ilmiah.show', $row->orasiIlmiah) }}">{{ $row->orasiIlmiah->tahun }}</a>
                                    @else
                                        <span class="text-muted">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td>@include('admin.guru-besar._media-badges', ['guruBesar' => $row])</td>
                                <td class="no-export">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.guru-besar.edit', $row) }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


