@php
    $list = $list ?? 'available';
    $isAssigned = $list === 'assigned';
@endphp
<li
    class="guru-assign-card"
    data-id="{{ $guruBesar->id }}"
    data-nama="{{ $guruBesar->nama }}"
    @if ($isAssigned && isset($orasi))
        data-detach-url="{{ route('admin.orasi-ilmiah.guru-besar.detach', [$orasi, $guruBesar]) }}"
    @endif
>
    <div class="guru-assign-card-inner">
        <span class="guru-assign-grip" title="Seret untuk pindah"><i class="bi bi-grip-vertical"></i></span>
        <div class="guru-assign-body flex-grow-1 min-w-0">
            <div class="fw-semibold text-truncate">{{ $guruBesar->nama }}</div>
            <div class="text-muted small text-truncate">
                {{ $guruBesar->pegawai_id ?: 'Manual' }}
                @if ($guruBesar->bidang_ilmu) · {{ $guruBesar->bidang_ilmu }} @endif
            </div>
            <div class="text-muted small text-truncate">{{ $guruBesar->displayFakultas() }} — {{ $guruBesar->displayProdi() }}</div>
            @if ($isAssigned)
                <div class="mt-1">@include('admin.guru-besar._media-badges', ['guruBesar' => $guruBesar])</div>
            @endif
        </div>
        @if ($isAssigned)
            <div class="guru-assign-actions d-flex flex-column gap-1 flex-shrink-0">
                <a href="{{ route('admin.guru-besar.edit', $guruBesar) }}" class="btn btn-sm btn-outline-secondary" title="Edit">Edit</a>
            </div>
        @endif
    </div>
</li>
