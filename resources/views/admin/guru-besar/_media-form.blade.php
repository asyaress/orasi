<div class="admin-card mb-3">
    <div class="admin-card-body">
        <div class="admin-section-title"><i class="bi bi-play-btn me-1"></i> Media Orasi (per Guru Besar)</div>
        <p class="admin-section-hint">Video YouTube, file orasi, PPT, piagam, dan sertifikat milik masing-masing guru besar — bukan di level event orasi.</p>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="youtube_url">YouTube URL (video orasi)</label>
                <input id="youtube_url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $guruBesar->youtube_url ?? '') }}"
                    placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="file_orasi">File Orasi</label>
                <input type="file" id="file_orasi" name="file_orasi" class="form-control">
                @if (!empty($guruBesar?->file_orasi_path))
                    <div class="form-hint"><a href="{{ asset('storage/'.$guruBesar->file_orasi_path) }}" target="_blank" rel="noreferrer">Lihat file saat ini</a></div>
                @endif
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="ppt">PPT</label>
                <input type="file" id="ppt" name="ppt" class="form-control" accept=".ppt,.pptx">
                @if (!empty($guruBesar?->ppt_path))
                    <div class="form-hint"><a href="{{ asset('storage/'.$guruBesar->ppt_path) }}" target="_blank" rel="noreferrer">Lihat PPT saat ini</a></div>
                @endif
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="piagam">Piagam</label>
                <input type="file" id="piagam" name="piagam" class="form-control" accept="application/pdf,image/*">
                @if (!empty($guruBesar?->piagam_path))
                    <div class="form-hint"><a href="{{ asset('storage/'.$guruBesar->piagam_path) }}" target="_blank" rel="noreferrer">Lihat piagam saat ini</a></div>
                @endif
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="sertifikat">Sertifikat</label>
                <input type="file" id="sertifikat" name="sertifikat" class="form-control" accept="application/pdf,image/*">
                @if (!empty($guruBesar?->sertifikat_path))
                    <div class="form-hint"><a href="{{ asset('storage/'.$guruBesar->sertifikat_path) }}" target="_blank" rel="noreferrer">Lihat sertifikat saat ini</a></div>
                @endif
            </div>
        </div>
    </div>
</div>
