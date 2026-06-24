<div class="d-flex flex-wrap gap-1">
    @if ($guruBesar->hasVideo())
        <a href="{{ $guruBesar->youtube_url }}" class="badge badge-soft-yellow text-decoration-none" target="_blank" rel="noreferrer" title="YouTube">YT</a>
    @endif
    @if ($guruBesar->hasFileOrasi())
        <a href="{{ asset('storage/'.$guruBesar->file_orasi_path) }}" class="badge bg-light text-dark border text-decoration-none" target="_blank" rel="noreferrer" title="File Orasi">File</a>
    @endif
    @if ($guruBesar->hasPpt())
        <a href="{{ asset('storage/'.$guruBesar->ppt_path) }}" class="badge bg-light text-dark border text-decoration-none" target="_blank" rel="noreferrer" title="PPT">PPT</a>
    @endif
    @if ($guruBesar->hasPiagam())
        <a href="{{ asset('storage/'.$guruBesar->piagam_path) }}" class="badge bg-light text-dark border text-decoration-none" target="_blank" rel="noreferrer" title="Piagam">Piagam</a>
    @endif
    @if ($guruBesar->hasSertifikat())
        <a href="{{ asset('storage/'.$guruBesar->sertifikat_path) }}" class="badge bg-light text-dark border text-decoration-none" target="_blank" rel="noreferrer" title="Sertifikat">Sertifikat</a>
    @endif
    @if (! $guruBesar->hasVideo() && ! $guruBesar->hasFileOrasi() && ! $guruBesar->hasPpt() && ! $guruBesar->hasPiagam() && ! $guruBesar->hasSertifikat())
        <span class="text-muted small">—</span>
    @endif
</div>
