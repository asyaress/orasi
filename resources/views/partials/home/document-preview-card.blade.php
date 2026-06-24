@php
    $variant = $document['variant'] ?? 'manuscript';
    $coverVariant = $variant === 'presentation' ? 'presentation' : 'naskah';
@endphp

<a
    href="{{ asset('storage/' . $document['file']) }}"
    class="orasi-doc-preview orasi-doc-cover-link"
    target="_blank"
    rel="noreferrer"
    aria-label="Buka {{ $document['label'] }} {{ $document['person'] }}"
>
    @include('partials.home.document-cover-svg', [
        'variant' => $coverVariant,
        'nama' => $document['person'],
        'judul' => $document['judul'] ?? 'Orasi Ilmiah Guru Besar',
        'tahun' => $document['year'] ?? date('Y'),
        'unique' => $document['file'] ?? null,
    ])
</a>
