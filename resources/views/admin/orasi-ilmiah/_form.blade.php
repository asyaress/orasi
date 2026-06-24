<div class="row g-3">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tahun Orasi <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control" min="2000" max="2100"
                            value="{{ old('tahun', $orasi->tahun ?? now()->year) }}" required>
                        <div class="form-hint">Satu orasi per tahun (mis. 2025).</div>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Judul Orasi Ilmiah</label>
                        <input name="judul" class="form-control" value="{{ old('judul', $orasi->judul ?? 'Orasi Guru Besar') }}" required
                            placeholder="Orasi Guru Besar Universitas Mulawarman">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_pelaksanaan" class="form-control"
                            value="{{ old('tanggal_pelaksanaan', optional($orasi->tanggal_pelaksanaan ?? null)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        @php($jenis = old('jenis', $orasi->jenis ?? 'Luring'))
                        <select name="jenis" class="form-select" required>
                            <option value="Luring" @selected($jenis === 'Luring')>Luring / Offline</option>
                            <option value="Daring" @selected($jenis === 'Daring')>Daring / Online</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Periode Pendaftaran (Mulai)</label>
                        <input type="date" name="pendaftaran_mulai" class="form-control"
                            value="{{ old('pendaftaran_mulai', optional($orasi->pendaftaran_mulai ?? null)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Periode Pendaftaran (Selesai)</label>
                        <input type="date" name="pendaftaran_selesai" class="form-control"
                            value="{{ old('pendaftaran_selesai', optional($orasi->pendaftaran_selesai ?? null)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Status</label>
                        @php($status = old('status', $orasi->status ?? 'draft'))
                        <select class="form-select" name="status" required>
                            <option value="draft" @selected($status === 'draft')>Draft</option>
                            <option value="published" @selected($status === 'published')>Published</option>
                            <option value="archived" @selected($status === 'archived')>Archived</option>
                        </select>
                    </div>

                    <hr class="my-1">

                    <div class="col-12">
                        <label class="form-label">Banner Event</label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                        <div class="form-hint">Banner untuk halaman event orasi tahun ini.</div>
                        @if (!empty($orasi?->banner_path))
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$orasi->banner_path) }}" alt="" class="admin-thumb rounded">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <p class="text-muted small mt-2 mb-0">
            <i class="bi bi-info-circle"></i> Video, file orasi, PPT, dan piagam diisi per <strong>Guru Besar</strong>, bukan di sini.
        </p>
    </div>
</div>
