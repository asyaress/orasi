@php
    $variant = $variant ?? 'naskah';
    $documentLabel = $variant === 'presentation' ? 'PRESENTASI' : 'NASKAH';
    $professorName = $nama ?? 'Guru Besar';
    $orationTitle = \Illuminate\Support\Str::upper($judul ?? 'Orasi Ilmiah Guru Besar');
    $orationYear = (string) ($tahun ?? date('Y'));
    $logoUrl = asset('logo/unmul-20260408145731-e033c2.png');
    $coverId = 'orasi-doc-cover-' . md5($documentLabel . $professorName . $orationTitle . $orationYear . ($unique ?? ''));

    $fontSerif = "Georgia, 'Times New Roman', 'Palatino Linotype', serif";
    $fontSans = "'Segoe UI', 'Helvetica Neue', Arial, sans-serif";
    $frameInset = 10;
    $canvasHeight = 1500;
    $footerHeight = 168;
    $footerCurveY = $canvasHeight - $footerHeight;
    $topPad = 44;
    $gapSize = 12;
    $logoBottomGap = 52;
    $titlePadX = 66;
    $titleSafeInset = 14;
    $titleMaxWidth = 1000 - ($titlePadX * 2) - ($titleSafeInset * 2);

    $wrapAllLines = static function (string $text, int $maxChars): array {
        $words = preg_split('/\s+/u', trim($text)) ?: [];
        $lines = [];
        $current = '';

        $flush = static function () use (&$lines, &$current): void {
            if ($current !== '') {
                $lines[] = $current;
                $current = '';
            }
        };

        foreach ($words as $word) {
            while (mb_strlen($word) > $maxChars) {
                $flush();
                $lines[] = mb_substr($word, 0, $maxChars);
                $word = mb_substr($word, $maxChars);
            }

            $candidate = $current === '' ? $word : $current . ' ' . $word;

            if (mb_strlen($candidate) > $maxChars && $current !== '') {
                $flush();
                $current = $word;
            } else {
                $current = $candidate;
            }
        }

        $flush();

        return $lines === [] ? [''] : $lines;
    };

    $charsPerLineForFont = static function (int $fontSize) use ($titleMaxWidth): int {
        $charWidth = max(12, $fontSize * 0.66);
        $estimated = (int) floor($titleMaxWidth / $charWidth);

        return max(18, min(32, $estimated));
    };

    $logoSize = 140;
    $uniFontSize = 40;
    $uniLineHeight = 44;
    $labelFontSize = 32;
    $headlineFontSize = 56;
    $headlineLineHeight = 58;
    $dividerHeight = 12;

    $nameLines = $wrapAllLines($professorName, 26);
    if (count($nameLines) > 2) {
        $nameLines = array_slice($nameLines, 0, 2);
        $nameLines[1] = rtrim(mb_substr($nameLines[1], 0, 22)) . '…';
    }

    $nameFontSize = count($nameLines) > 1 ? 36 : 40;
    $nameLineHeight = $nameFontSize + 10;

    $cursorY = $topPad;
    $logoY = $cursorY;
    $cursorY += $logoSize + $logoBottomGap;

    $uniLine1Y = $cursorY + ($uniFontSize * 0.85);
    $uniLine2Y = $uniLine1Y + $uniLineHeight;
    $cursorY += ($uniLineHeight * 2) + $gapSize;

    $divider1Y = $cursorY + ($dividerHeight / 2);
    $cursorY += $dividerHeight + $gapSize;

    $labelY = $cursorY + $labelFontSize;
    $cursorY += ($labelFontSize + 4) + $gapSize;

    $headline1Y = $cursorY + ($headlineFontSize * 0.9);
    $headline2Y = $headline1Y + $headlineLineHeight;
    $cursorY += ($headlineLineHeight * 2) + $gapSize;

    $divider2Y = $cursorY + ($dividerHeight / 2);
    $cursorY += $dividerHeight + $gapSize;

    $nameStartY = $cursorY + $nameFontSize;
    $cursorY += (count($nameLines) * $nameLineHeight) + 10;

    $titleAreaTop = $cursorY;
    $titleAreaBottom = $footerCurveY - 8;
    $titleAreaHeight = max(140, $titleAreaBottom - $titleAreaTop);

    $titleFontSize = 40;
    $titleLines = [];
    $titleLineHeight = 49;

    for ($size = 40; $size >= 19; $size--) {
        $charsPerLine = $charsPerLineForFont($size);
        $candidateLines = $wrapAllLines($orationTitle, $charsPerLine);
        $lineHeight = $size + 9;
        $blockHeight = count($candidateLines) * $lineHeight;

        if ($blockHeight <= $titleAreaHeight) {
            $titleFontSize = $size;
            $titleLines = $candidateLines;
            $titleLineHeight = $lineHeight;
            break;
        }

        $titleFontSize = $size;
        $titleLines = $candidateLines;
        $titleLineHeight = $lineHeight;
    }

    $titleBlockHeight = count($titleLines) * $titleLineHeight;
    $titleOffsetY = max(0, ($titleAreaHeight - $titleBlockHeight) / 2);
    $titleStartY = $titleAreaTop + $titleOffsetY + $titleFontSize;
    $watermarkY = $titleAreaTop + ($titleAreaHeight / 2);

    $yearFontSize = 72;
    $yearY = $footerCurveY + 56;
    $yearLineY = $footerCurveY + 126;

    $logoX = 500 - ($logoSize / 2);
    $frameSize = 1000 - ($frameInset * 2);
    $corner = $frameInset + 14;
    $cornerLen = 56;
@endphp

<svg
    class="orasi-doc-cover-svg"
    viewBox="0 0 1000 1500"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    role="img"
    aria-label="Sampul {{ $documentLabel }} {{ $professorName }}"
    preserveAspectRatio="xMidYMid meet"
>
    <defs>
        <pattern id="{{ $coverId }}-texture" width="8" height="8" patternUnits="userSpaceOnUse" patternTransform="rotate(24)">
            <rect width="8" height="8" fill="#faf8f3" />
            <circle cx="1.2" cy="1.2" r="0.5" fill="#ebe4d4" opacity="0.45" />
        </pattern>
        <linearGradient id="{{ $coverId }}-gold" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stop-color="#e2c15a" />
            <stop offset="55%" stop-color="#c9a227" />
            <stop offset="100%" stop-color="#9a7420" />
        </linearGradient>
        <linearGradient id="{{ $coverId }}-navy" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stop-color="#123a72" />
            <stop offset="100%" stop-color="#0c2d5a" />
        </linearGradient>
        <filter id="{{ $coverId }}-text-shadow" x="-8%" y="-8%" width="116%" height="116%">
            <feDropShadow dx="0" dy="1" stdDeviation="1.5" flood-color="#0c2d5a" flood-opacity="0.12" />
        </filter>
        <clipPath id="{{ $coverId }}-frame">
            <rect x="{{ $frameInset }}" y="{{ $frameInset }}" width="{{ $frameSize }}" height="{{ $canvasHeight - ($frameInset * 2) }}" rx="6" />
        </clipPath>
        <clipPath id="{{ $coverId }}-title-box">
            <rect x="{{ $titlePadX }}" y="{{ $titleAreaTop - 6 }}" width="{{ 1000 - ($titlePadX * 2) }}" height="{{ $titleAreaHeight + 12 }}" />
        </clipPath>
    </defs>

    <rect width="1000" height="1500" fill="url(#{{ $coverId }}-texture)" />

    <g opacity="0.028" transform="translate(500 {{ $watermarkY }})">
        <image href="{{ $logoUrl }}" x="-220" y="-240" width="440" height="480" preserveAspectRatio="xMidYMid meet" />
    </g>

    <rect x="{{ $frameInset }}" y="{{ $frameInset }}" width="{{ $frameSize }}" height="{{ $canvasHeight - ($frameInset * 2) }}" fill="none" stroke="url(#{{ $coverId }}-gold)" stroke-width="3" rx="6" />
    <path d="M{{ $corner }} {{ $corner }} H{{ $corner + $cornerLen }} V{{ $corner + $cornerLen }} H{{ $corner }} Z" fill="none" stroke="#c9a227" stroke-width="3" />
    <path d="M{{ 1000 - $corner }} {{ $corner }} H{{ 1000 - $corner - $cornerLen }} V{{ $corner + $cornerLen }} H{{ 1000 - $corner }} Z" fill="none" stroke="#c9a227" stroke-width="3" />
    <path d="M{{ $corner }} {{ $canvasHeight - $corner }} H{{ $corner + $cornerLen }} V{{ $canvasHeight - $corner - $cornerLen }} H{{ $corner }} Z" fill="none" stroke="#c9a227" stroke-width="3" />
    <path d="M{{ 1000 - $corner }} {{ $canvasHeight - $corner }} H{{ 1000 - $corner - $cornerLen }} V{{ $canvasHeight - $corner - $cornerLen }} H{{ 1000 - $corner }} Z" fill="none" stroke="#c9a227" stroke-width="3" />

    <g clip-path="url(#{{ $coverId }}-frame)">
        <image href="{{ $logoUrl }}" x="{{ $logoX }}" y="{{ $logoY }}" width="{{ $logoSize }}" height="{{ $logoSize }}" preserveAspectRatio="xMidYMid meet" />

        <text x="500" y="{{ $uniLine1Y }}" text-anchor="middle" fill="url(#{{ $coverId }}-navy)" font-family="{{ $fontSerif }}" font-size="{{ $uniFontSize }}" font-weight="700" letter-spacing="3">UNIVERSITAS</text>
        <text x="500" y="{{ $uniLine2Y }}" text-anchor="middle" fill="url(#{{ $coverId }}-navy)" font-family="{{ $fontSerif }}" font-size="{{ $uniFontSize }}" font-weight="700" letter-spacing="3">MULAWARMAN</text>

        <line x1="72" y1="{{ $divider1Y }}" x2="928" y2="{{ $divider1Y }}" stroke="#c9a227" stroke-width="2" opacity="0.9" />
        <polygon points="500,{{ $divider1Y - 5 }} 507,{{ $divider1Y + 4 }} 500,{{ $divider1Y + 13 }} 493,{{ $divider1Y + 4 }}" fill="#c9a227" />

        <text x="500" y="{{ $labelY }}" text-anchor="middle" fill="#0c2d5a" font-family="{{ $fontSans }}" font-size="{{ $labelFontSize }}" font-weight="700" letter-spacing="8">{{ $documentLabel }}</text>

        <text x="500" y="{{ $headline1Y }}" text-anchor="middle" fill="url(#{{ $coverId }}-gold)" font-family="{{ $fontSerif }}" font-size="{{ $headlineFontSize }}" font-weight="700" letter-spacing="1.2" filter="url(#{{ $coverId }}-text-shadow)">ORASI ILMIAH</text>
        <text x="500" y="{{ $headline2Y }}" text-anchor="middle" fill="url(#{{ $coverId }}-gold)" font-family="{{ $fontSerif }}" font-size="{{ $headlineFontSize }}" font-weight="700" letter-spacing="1.2" filter="url(#{{ $coverId }}-text-shadow)">GURU BESAR</text>

        <line x1="72" y1="{{ $divider2Y }}" x2="928" y2="{{ $divider2Y }}" stroke="#c9a227" stroke-width="2" opacity="0.9" />
        <polygon points="500,{{ $divider2Y - 5 }} 507,{{ $divider2Y + 4 }} 500,{{ $divider2Y + 13 }} 493,{{ $divider2Y + 4 }}" fill="#c9a227" />

        @foreach ($nameLines as $index => $line)
            <text x="500" y="{{ $nameStartY + ($index * $nameLineHeight) }}" text-anchor="middle" fill="#0c2d5a" font-family="{{ $fontSerif }}" font-size="{{ $nameFontSize }}" font-weight="700">{{ $line }}</text>
        @endforeach

        <g clip-path="url(#{{ $coverId }}-title-box)">
            @foreach ($titleLines as $index => $line)
                <text
                    x="500"
                    y="{{ $titleStartY + ($index * $titleLineHeight) }}"
                    text-anchor="middle"
                    fill="#123a72"
                    font-family="{{ $fontSerif }}"
                    font-size="{{ $titleFontSize }}"
                    font-weight="700"
                    letter-spacing="0.25"
                >{{ $line }}</text>
            @endforeach
        </g>

        <path d="M0 {{ $footerCurveY }} C250 {{ $footerCurveY - 28 }} 750 {{ $footerCurveY - 28 }} 1000 {{ $footerCurveY }} L1000 1500 L0 1500 Z" fill="url(#{{ $coverId }}-navy)" />

        <text x="500" y="{{ $yearY }}" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" font-family="{{ $fontSerif }}" font-size="{{ $yearFontSize }}" font-weight="700" letter-spacing="1">{{ $orationYear }}</text>

        <line x1="330" y1="{{ $yearLineY }}" x2="670" y2="{{ $yearLineY }}" stroke="#d4af37" stroke-width="2" opacity="0.9" />
        <polygon points="500,{{ $yearLineY - 5 }} 508,{{ $yearLineY + 4 }} 500,{{ $yearLineY + 13 }} 492,{{ $yearLineY + 4 }}" fill="#d4af37" />
    </g>
</svg>
