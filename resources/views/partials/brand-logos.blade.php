@php
    $brandLogos = $brandLogos ?? [
        ['file' => 'tut-wuri-20260408145730-756785.png', 'alt' => 'Tut Wuri Handayani'],
        ['file' => 'unmul-20260408145731-e033c2.png', 'alt' => 'Universitas Mulawarman'],
        ['file' => 'blu-20260408145731-85748a.png', 'alt' => 'BLU'],
        ['file' => 'dies-natalis-20260408145732-edfb3f.png', 'alt' => 'Dies Natalis'],
        ['file' => 'diktisaintek-20260408145732-367e09.png', 'alt' => 'Diktisaintek Berdampak'],
        ['file' => 'logo-unggul-20260408145732-41a84d.png', 'alt' => 'Unggul Universitas Mulawarman'],
    ];
    $wrapperClass = trim('orasi-brand-logos ' . ($wrapperClass ?? ''));
    $itemClass = trim('orasi-brand-logo-item ' . ($itemClass ?? ''));
@endphp

<span class="{{ $wrapperClass }}">
    @foreach ($brandLogos as $logo)
        <span class="{{ $itemClass }}">
            <img src="{{ asset('logo/' . $logo['file']) }}" alt="{{ $logo['alt'] }}" width="98" height="42" decoding="async">
        </span>
    @endforeach
</span>
