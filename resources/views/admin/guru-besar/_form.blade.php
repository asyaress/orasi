@php
    $selectedFakultas = old('fakultas_id', $guruBesar->fakultas_id ?? '');
    $selectedProdi = old('prodi_id', $guruBesar->prodi_id ?? '');
    $selectedOrasi = old('orasi_ilmiah_id', $preselectOrasiId ?? $guruBesar->orasi_ilmiah_id ?? '');
@endphp

@if (!empty($returnToOrasi) && $preselectOrasiId)
    <input type="hidden" name="return" value="orasi">
    <input type="hidden" name="orasi_ilmiah_id" value="{{ $preselectOrasiId }}">
@endif

<div class="admin-card mb-3">
    <div class="admin-card-body">
        <div class="admin-section-title"><i class="bi bi-person-badge me-1"></i> Identitas Guru Besar</div>
        <p class="admin-section-hint">Data master — dari API kepegawaian (pegawai ID) atau input manual. Satu orang hanya boleh masuk satu orasi per tahun.</p>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label" for="pegawai_id">Pegawai ID (API)</label>
                <input id="pegawai_id" name="pegawai_id" class="form-control" value="{{ old('pegawai_id', $guruBesar->pegawai_id ?? '') }}" placeholder="NIP / ID kepegawaian" autocomplete="off">
                <div class="form-hint">Kosongkan jika input manual saja.</div>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="sumber">Sumber data</label>
                @php($sumber = old('sumber', $guruBesar->sumber ?? 'manual'))
                <select id="sumber" name="sumber" class="form-select">
                    <option value="manual" @selected($sumber === 'manual')>Manual</option>
                    <option value="api" @selected($sumber === 'api')>API Kepegawaian</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="nama">Nama <span class="text-danger">*</span></label>
                <input id="nama" name="nama" class="form-control" value="{{ old('nama', $guruBesar->nama ?? '') }}" required>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                @php($jenisKelamin = old('jenis_kelamin', $guruBesar->jenis_kelamin ?? ''))
                <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                    <option value="">— Pilih jenis kelamin —</option>
                    @foreach (\App\Models\GuruBesar::jenisKelaminOptions() as $value => $label)
                        <option value="{{ $value }}" @selected($jenisKelamin === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="bidang_ilmu">Bidang Ilmu</label>
                <input id="bidang_ilmu" name="bidang_ilmu" class="form-control" value="{{ old('bidang_ilmu', $guruBesar->bidang_ilmu ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label" for="judul_orasi">Judul Orasi Ilmiah</label>
                <textarea id="judul_orasi" name="judul_orasi" class="form-control" rows="3" placeholder="Masukkan judul orasi ilmiah guru besar">{{ old('judul_orasi', $guruBesar->judul_orasi ?? '') }}</textarea>
                <div class="form-hint">Judul ini akan tampil di halaman detail publik dan bisa dipakai sebagai konteks agenda.</div>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="tmt">TMT</label>
                <input type="date" id="tmt" name="tmt" class="form-control" value="{{ old('tmt', optional($guruBesar->tmt ?? null)->format('Y-m-d')) }}">
            </div>
        </div>
    </div>
</div>

<div class="admin-card mb-3">
    <div class="admin-card-body">
        <div class="admin-section-title"><i class="bi bi-building me-1"></i> Fakultas &amp; Prodi</div>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label" for="fakultas_id">Fakultas</label>
                <select id="fakultas_id" name="fakultas_id" class="form-select">
                    <option value="">— Pilih fakultas —</option>
                    @foreach ($fakultas as $f)
                        <option value="{{ $f->id }}" @selected((string) $selectedFakultas === (string) $f->id)>{{ $f->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="prodi_id">Prodi</label>
                <select id="prodi_id" name="prodi_id" class="form-select" data-selected="{{ $selectedProdi }}">
                    <option value="">{{ $selectedFakultas ? '— Pilih prodi —' : 'Pilih fakultas terlebih dahulu' }}</option>
                    @foreach ($prodis as $p)
                        <option value="{{ $p->id }}" data-fakultas-id="{{ $p->fakultas_id }}" @selected((string) $selectedProdi === (string) $p->id) @if($selectedFakultas && (string) $p->fakultas_id !== (string) $selectedFakultas) hidden disabled @endif>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3 pt-2 border-top">
            <button type="button" class="btn-snapshot-toggle" id="toggle-snapshot" aria-expanded="false" aria-controls="snapshot-panel">
                <i class="bi bi-chevron-down me-1"></i> Input manual (jika belum ada di master)
            </button>
            <div id="snapshot-panel" class="mt-3 @unless(old('fakultas_snapshot') || old('prodi_snapshot') || optional($guruBesar)->fakultas_snapshot || optional($guruBesar)->prodi_snapshot) d-none @endunless">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="fakultas_snapshot">Fakultas (teks)</label>
                        <input id="fakultas_snapshot" name="fakultas_snapshot" class="form-control" value="{{ old('fakultas_snapshot', $guruBesar->fakultas_snapshot ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="prodi_snapshot">Prodi (teks)</label>
                        <input id="prodi_snapshot" name="prodi_snapshot" class="form-control" value="{{ old('prodi_snapshot', $guruBesar->prodi_snapshot ?? '') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (empty($returnToOrasi))
    <div class="admin-card mb-3">
        <div class="admin-card-body">
            <div class="admin-section-title"><i class="bi bi-calendar-event me-1"></i> Penugasan Orasi (opsional)</div>
            <p class="admin-section-hint">Bisa dikosongkan dulu — nanti ditugaskan dari halaman detail Orasi Ilmiah.</p>
            <label class="form-label" for="orasi_ilmiah_id">Orasi Ilmiah / Tahun</label>
            <select id="orasi_ilmiah_id" name="orasi_ilmiah_id" class="form-select">
                <option value="">— Belum ditugaskan —</option>
                @foreach ($orasis as $o)
                    <option value="{{ $o->id }}" @selected((string) $selectedOrasi === (string) $o->id)>
                        {{ $o->tahun }} — {{ $o->judul }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif

@include('admin.guru-besar._media-form')

<div class="admin-card">
    <div class="admin-card-body">
        <div class="admin-section-title"><i class="bi bi-image me-1"></i> Foto</div>
        @php($fotoDisplayMode = old('foto_display_mode', $guruBesar->foto_display_mode ?? 'svg_bg_photo'))
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-4">
                <label class="form-label" for="foto_display_mode">Mode foto</label>
                <select id="foto_display_mode" name="foto_display_mode" class="form-select">
                    <option value="svg_bg_photo" @selected($fotoDisplayMode === 'svg_bg_photo')>Foto saja + background SVG</option>
                    <option value="png_full_overlay" @selected($fotoDisplayMode === 'png_full_overlay')>PNG full overlay</option>
                </select>
                <div class="form-hint">Pilih apakah foto mengikuti frame SVG atau tampil full menimpa background.</div>
            </div>
            <div class="col-12 col-md-8">
                <input type="file" id="foto" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>
            @if (!empty($guruBesar?->foto_path))
                <div class="col-12 col-md-4 text-center text-md-end">
                    <img src="{{ asset('storage/'.$guruBesar->foto_path) }}" alt="" class="admin-thumb rounded">
                </div>
            @endif
        </div>
    </div>
</div>
