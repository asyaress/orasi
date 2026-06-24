@extends('admin.layouts.app')

@section('title', 'Security — Admin')
@section('page_title', 'Security')
@section('page_subtitle', 'Kelola device Authenticator (2FA) untuk akun admin')

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="admin-card">
                <div class="p-3 p-lg-4">
                    <div class="fw-bold mb-2">Device Authenticator</div>
                    <div class="text-muted small mb-3">Kamu bisa menambahkan beberapa device (mis. HP + tablet) untuk Authenticator.</div>

                    <div class="admin-table-wrap">
                        <table class="table table-striped admin-datatable w-100" data-order='[[1,"desc"]]'>
                            <thead>
                                <tr>
                                    <th>Nama Device</th>
                                    <th>Aktif Sejak</th>
                                    <th>Terakhir Dipakai</th>
                                    <th class="no-sort no-export" style="width:120px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($devices as $d)
                                    <tr>
                                        <td class="fw-semibold">{{ $d->name }}</td>
                                        <td data-order="{{ optional($d->confirmed_at)->format('Y-m-d H:i:s') ?: '' }}">
                                            {{ optional($d->confirmed_at)->format('d-m-Y H:i') ?: '—' }}
                                        </td>
                                        <td data-order="{{ optional($d->last_used_at)->format('Y-m-d H:i:s') ?: '' }}">
                                            {{ optional($d->last_used_at)->format('d-m-Y H:i') ?: '—' }}
                                        </td>
                                        <td class="no-export">
                                            <form method="post" action="{{ route('admin.security.devices.destroy', $d) }}" onsubmit="return confirm('Hapus device ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="admin-card">
                <div class="p-3 p-lg-4">
                    <div class="fw-bold mb-2">Tambah Device</div>
                    <div class="text-muted small mb-3">
                        Scan QR di device baru, lalu masukkan kode 6 digit untuk verifikasi.
                    </div>

                    <div class="text-center my-2">
                        <img src="{{ $qr }}" alt="QR" style="width:220px;height:220px;border-radius:12px;border:1px solid var(--admin-border);padding:8px;background:#fff;">
                    </div>

                    <div class="text-muted small mb-1">Secret manual:</div>
                    <div class="fw-semibold mb-3" style="letter-spacing:.06em;font-family:monospace;">{{ $secret }}</div>

                    <form method="post" action="{{ route('admin.security.devices.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Nama device (opsional)</label>
                            <input name="name" class="form-control" placeholder="mis. HP Kantor">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode 6 digit</label>
                            <input name="code" class="form-control" placeholder="123456" inputmode="numeric" autocomplete="one-time-code" required>
                            @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-admin btn-admin-primary w-100" type="submit">Tambah Device</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

