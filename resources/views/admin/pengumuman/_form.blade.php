@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-bs5.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame { border-color: var(--admin-border); }
        .note-editor .note-editable { min-height: 360px; background: #fff; }
        .pengumuman-cover-preview { width: 100%; max-width: 430px; aspect-ratio: 16 / 9; object-fit: cover; border-radius: 14px; border: 1px solid var(--admin-border); background: #f8fafc; }
    </style>
@endpush

<div class="admin-card">
    <div class="admin-card-body">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="judul">Judul <span class="text-danger">*</span></label>
                        <input id="judul" name="judul" class="form-control" value="{{ old('judul', $pengumuman->judul ?? '') }}" maxlength="255" required autofocus>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label fw-semibold" for="slug">Slug</label>
                        <input id="slug" name="slug" class="form-control" value="{{ old('slug', $pengumuman->slug ?? '') }}" placeholder="otomatis-dari-judul">
                        <div class="form-text">Kosongkan agar dibuat otomatis.</div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold" for="status">Status <span class="text-danger">*</span></label>
                        @php($status = old('status', $pengumuman->status ?? 'draft'))
                        <select id="status" class="form-select" name="status" required>
                            <option value="draft" @selected($status === 'draft')>Draft</option>
                            <option value="published" @selected($status === 'published')>Published</option>
                            <option value="archived" @selected($status === 'archived')>Archived</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold" for="ringkasan">Ringkasan</label>
                        <textarea id="ringkasan" name="ringkasan" class="form-control" rows="3" maxlength="500" placeholder="Ringkasan singkat untuk kartu daftar pengumuman">{{ old('ringkasan', $pengumuman->ringkasan ?? '') }}</textarea>
                        <div class="form-text">Maksimal 500 karakter. Jika kosong, cuplikan diambil dari konten.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold" for="konten">Konten <span class="text-danger">*</span></label>
                        <textarea id="konten" name="konten" class="form-control" required>{{ old('konten', $pengumuman->konten ?? '') }}</textarea>
                        <div class="form-text">Gambar dapat disisipkan lewat tombol gambar pada editor (maksimal 5 MB per gambar).</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <label class="form-label fw-semibold" for="cover">Cover</label>
                    <img
                        id="cover-preview"
                        class="pengumuman-cover-preview mb-3 {{ filled($pengumuman?->cover_url) ? '' : 'd-none' }}"
                        src="{{ $pengumuman?->cover_url ?? '' }}"
                        alt="Preview cover"
                    >
                    <input id="cover" type="file" name="cover" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG, PNG, atau WebP. Maksimal 5 MB; rasio 16:9 disarankan.</div>

                    @if (filled($pengumuman?->cover_path))
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" name="remove_cover" id="remove_cover">
                            <label class="form-check-label" for="remove_cover">Hapus cover saat ini</label>
                        </div>
                    @endif

                    <hr>

                    <label class="form-label fw-semibold" for="tags">Tag</label>
                    <input
                        id="tags"
                        name="tags"
                        class="form-control"
                        value="{{ old('tags', implode(', ', $pengumuman?->tags ?? [])) }}"
                        placeholder="Akademik, Agenda, Guru Besar"
                    >
                    <div class="form-text">Pisahkan tag dengan koma, maksimal 10 tag.</div>

                    <hr>

                    <label class="form-label fw-semibold" for="published_at">Tanggal tayang</label>
                    <input type="datetime-local" id="published_at" name="published_at" class="form-control"
                        value="{{ old('published_at', optional($pengumuman?->published_at)->format('Y-m-d\TH:i')) }}">
                    <div class="form-text">Kosong + Published = tayang sekarang. Tanggal mendatang = terjadwal.</div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="1" name="is_pinned" id="is_pinned"
                            @checked((bool) old('is_pinned', $pengumuman->is_pinned ?? false))>
                        <label class="form-check-label" for="is_pinned">Pin di bagian atas daftar</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/lang/summernote-id-ID.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const uploadUrl = @json(route('admin.pengumuman.upload-image'));
            const editor = $('#konten');

            editor.summernote({
                lang: 'id-ID',
                height: 360,
                dialogsInBody: true,
                placeholder: 'Tulis isi pengumuman di sini...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'table', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        Array.from(files).forEach(function (file) {
                            const body = new FormData();
                            body.append('image', file);

                            fetch(uploadUrl, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                                body: body
                            })
                                .then(async function (response) {
                                    const payload = await response.json();
                                    if (!response.ok) {
                                        throw new Error(payload.message || Object.values(payload.errors || {}).flat()[0] || 'Upload gambar gagal.');
                                    }
                                    return payload;
                                })
                                .then(function (payload) {
                                    editor.summernote('insertImage', payload.url, function (image) {
                                        image.attr('alt', file.name);
                                        image.css('max-width', '100%');
                                    });
                                })
                                .catch(function (error) {
                                    window.Swal
                                        ? Swal.fire({ icon: 'error', title: 'Gambar gagal diunggah', text: error.message })
                                        : alert(error.message);
                                });
                        });
                    }
                }
            });

            const cover = document.getElementById('cover');
            const preview = document.getElementById('cover-preview');
            cover.addEventListener('change', function () {
                const file = cover.files[0];
                if (!file) return;
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            });
        });
    </script>
@endpush
