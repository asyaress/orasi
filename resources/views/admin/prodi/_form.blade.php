<div class="admin-card">
    <div class="p-3 p-lg-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Fakultas</label>
                <select name="fakultas_id" class="form-select" required>
                    <option value="">Pilih fakultas</option>
                    @foreach ($fakultas as $f)
                        <option value="{{ $f->id }}" @selected((string) old('fakultas_id', $prodi->fakultas_id ?? '') === (string) $f->id)>{{ $f->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenjang</label>
                <input name="jenjang" class="form-control" value="{{ old('jenjang', $prodi->jenjang ?? '') }}" placeholder="S1/S2/S3">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kode (opsional)</label>
                <input name="kode" class="form-control" value="{{ old('kode', $prodi->kode ?? '') }}" maxlength="20">
            </div>
            <div class="col-md-8">
                <label class="form-label">Nama Prodi</label>
                <input name="nama" class="form-control" value="{{ old('nama', $prodi->nama ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug (opsional)</label>
                <input name="slug" class="form-control" value="{{ old('slug', $prodi->slug ?? '') }}" placeholder="otomatis dari nama jika kosong">
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active"
                        @checked((bool) old('is_active', $prodi->is_active ?? true))>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>
    </div>
</div>

