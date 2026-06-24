<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Judul</label>
                <input name="judul" class="form-control" value="{{ old('judul', $pengumuman->judul ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Slug (opsional)</label>
                <input name="slug" class="form-control" value="{{ old('slug', $pengumuman->slug ?? '') }}" placeholder="otomatis dari judul jika kosong">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                @php($status = old('status', $pengumuman->status ?? 'draft'))
                <select class="form-select" name="status" required>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                    <option value="archived" @selected($status === 'archived')>Archived</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Ringkasan (opsional)</label>
                <input name="ringkasan" class="form-control" value="{{ old('ringkasan', $pengumuman->ringkasan ?? '') }}" maxlength="500">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Konten (opsional)</label>
                <textarea name="konten" class="form-control" rows="8">{{ old('konten', $pengumuman->konten ?? '') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tayang (opsional)</label>
                <input type="datetime-local" name="published_at" class="form-control"
                    value="{{ old('published_at', optional($pengumuman->published_at ?? null)->format('Y-m-d\\TH:i')) }}">
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_pinned" id="is_pinned"
                        @checked((bool) old('is_pinned', $pengumuman->is_pinned ?? false))>
                    <label class="form-check-label" for="is_pinned">
                        Pin di bagian atas
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

