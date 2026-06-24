<div class="admin-card">
    <div class="p-3 p-lg-4">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Fakultas</label>
                <input name="nama" class="form-control" value="{{ old('nama', $fakultas->nama ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kode (opsional)</label>
                <input name="kode" class="form-control" value="{{ old('kode', $fakultas->kode ?? '') }}" maxlength="20">
            </div>
            <div class="col-md-8">
                <label class="form-label">Slug (opsional)</label>
                <input name="slug" class="form-control" value="{{ old('slug', $fakultas->slug ?? '') }}" placeholder="otomatis dari nama jika kosong">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active"
                        @checked((bool) old('is_active', $fakultas->is_active ?? true))>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>
    </div>
</div>

