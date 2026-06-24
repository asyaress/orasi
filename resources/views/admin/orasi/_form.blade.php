<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Judul Orasi Ilmiah</label>
                        <input name="judul" class="form-control" value="{{ old('judul', $orasi->judul ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_pelaksanaan" class="form-control" value="{{ old('tanggal_pelaksanaan', optional($orasi->tanggal_pelaksanaan ?? null)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            @php($jenis = old('jenis', $orasi->jenis ?? 'Luring'))
                            <option value="Luring" @selected($jenis === 'Luring')>Luring / Offline</option>
                            <option value="Daring" @selected($jenis === 'Daring')>Daring / Online</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Periode Pendaftaran (Mulai)</label>
                        <input type="date" name="pendaftaran_mulai" class="form-control" value="{{ old('pendaftaran_mulai', optional($orasi->pendaftaran_mulai ?? null)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Periode Pendaftaran (Selesai)</label>
                        <input type="date" name="pendaftaran_selesai" class="form-control" value="{{ old('pendaftaran_selesai', optional($orasi->pendaftaran_selesai ?? null)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">YouTube URL (Video Orasi)</label>
                        <input name="youtube_url" class="form-control" value="{{ old('youtube_url', $orasi->youtube_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">Di public site, banner Home akan menampilkan video.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold">Data Dosen (API Kepegawaian)</div>
                    <span class="badge badge-soft-yellow">Kerangka</span>
                </div>
                <div class="text-muted small mb-3">
                    Untuk sementara input manual. Nanti akan ada pencarian dosen dari API (autocomplete) dan otomatis isi fakultas/prodi/bidang/TMT.
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pegawai ID (API)</label>
                        <input name="pegawai_id" class="form-control" value="{{ old('pegawai_id', $orasi->pegawai_id ?? '') }}" placeholder="mis. NIP/NIDN/ID API">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama</label>
                        <input name="pegawai_nama" class="form-control" value="{{ old('pegawai_nama', $orasi->pegawai_nama ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bidang Ilmu</label>
                        <input name="bidang_ilmu" class="form-control" value="{{ old('bidang_ilmu', $orasi->bidang_ilmu ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fakultas</label>
                        <input name="fakultas" class="form-control" value="{{ old('fakultas', $orasi->fakultas ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prodi</label>
                        <input name="prodi" class="form-control" value="{{ old('prodi', $orasi->prodi ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">TMT</label>
                        <input type="date" name="tmt" class="form-control" value="{{ old('tmt', optional($orasi->tmt ?? null)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Status</label>
                        @php($status = old('status', $orasi->status ?? 'draft'))
                        <select class="form-select" name="status" required>
                            <option value="draft" @selected($status === 'draft')>Draft</option>
                            <option value="published" @selected($status === 'published')>Published</option>
                            <option value="archived" @selected($status === 'archived')>Archived</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" min="1" name="urutan" class="form-control" value="{{ old('urutan', $orasi->urutan ?? '') }}" placeholder="1,2,3...">
                    </div>

                    <hr class="my-2">

                    <div class="col-12">
                        <label class="form-label fw-semibold">Banner</label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                        @if (!empty($orasi?->banner_path))
                            <div class="form-text">Saat ini: {{ $orasi->banner_path }}</div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        @if (!empty($orasi?->foto_path))
                            <div class="form-text">Saat ini: {{ $orasi->foto_path }}</div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">File Orasi</label>
                        <input type="file" name="file_orasi" class="form-control">
                        @if (!empty($orasi?->file_orasi_path))
                            <div class="form-text">Saat ini: {{ $orasi->file_orasi_path }}</div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">PPT</label>
                        <input type="file" name="ppt" class="form-control" accept=".ppt,.pptx,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation">
                        @if (!empty($orasi?->ppt_path))
                            <div class="form-text">Saat ini: {{ $orasi->ppt_path }}</div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Piagam</label>
                        <input type="file" name="piagam" class="form-control" accept="application/pdf,image/*">
                        @if (!empty($orasi?->piagam_path))
                            <div class="form-text">Saat ini: {{ $orasi->piagam_path }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

