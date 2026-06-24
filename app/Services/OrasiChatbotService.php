<?php

namespace App\Services;

use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrasiChatbotService
{
    /** @var array<int, string> */
    private array $greetingPatterns = [
        'halo', 'hai', 'hi', 'hello', 'helo', 'hallo', 'permisi', 'pagi', 'siang', 'sore', 'malam',
        'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam', 'assalamualaikum', 'assalamu alaikum',
    ];

    /** @var array<int, string> */
    private array $thanksPatterns = [
        'terima kasih', 'makasih', 'thanks', 'thank you', 'thx', 'trimakasih',
    ];

    /** @var array<int, string> */
    private array $identityPatterns = [
        'siapa kamu', 'kamu siapa', 'anda siapa', 'siapa anda',
        'si ora siapa', 'kamu siapa ora', 'perkenalkan kamu',
        'perkenalkan diri', 'perkenalkan dirimu', 'who are you',
        'what are you', 'namamu siapa', 'nama kamu siapa', 'kamu itu siapa',
    ];

    /** @var array<string, array<int, string>> */
    private array $keywordAliases = [
        'about' => ['apa itu', 'pengertian', 'definisi', 'maksud', 'meaning', 'definition', 'explain', 'jelaskan'],
        'agenda' => ['agenda', 'jadwal', 'kegiatan', 'event', 'schedule', 'calendar', 'pelaksanaan', 'daftar orasi'],
        'archive' => ['arsip', 'tahun', 'year', 'periode', 'period', 'riwayat', 'history', 'rekap tahun'],
        'count' => ['berapa', 'jumlah', 'total', 'banyak', 'hitung', 'count', 'many', 'how many', 'rekap'],
        'document' => ['dokumen', 'naskah', 'ppt', 'powerpoint', 'presentasi', 'slide', 'file', 'unduh', 'download', 'materi', 'paper', 'manuscript', 'document'],
        'faculty' => ['fakultas', 'faculty', 'unit', 'school', 'college', 'prodi', 'program studi', 'jurusan', 'department'],
        'field' => ['bidang', 'ilmu', 'keahlian', 'kepakaran', 'spesialisasi', 'expertise', 'field', 'research', 'discipline'],
        'help' => ['bantuan', 'help', 'tolong', 'panduan', 'guide', 'fitur', 'menu', 'navigasi', 'cara', 'bagaimana', 'how', 'where', 'dimana'],
        'latest' => ['terbaru', 'latest', 'recent', 'update', 'baru', 'paling baru', 'last'],
        'orator' => ['guru', 'guru besar', 'profesor', 'professor', 'dosen', 'orator', 'narasumber', 'speaker', 'profil', 'biodata', 'profile'],
        'profile' => ['siapa', 'profil', 'biodata', 'info', 'informasi', 'detail', 'tentang', 'profile', 'about', 'identity'],
        'statistic' => ['statistik', 'grafik', 'chart', 'dashboard', 'data', 'rekap', 'sebaran', 'tren', 'trend', 'summary'],
        'title' => ['judul', 'tema', 'materi orasi', 'topik', 'topik orasi', 'title', 'topic', 'theme'],
        'video' => ['video', 'youtube', 'rekaman', 'tonton', 'tayangan', 'dokumentasi', 'recording', 'watch', 'media'],
    ];

    /** @var array<int, string> */
    private array $searchStopWords = [
        'apa', 'apakah', 'atau', 'bagaimana', 'bisa', 'boleh', 'cari', 'dapat', 'dan', 'di', 'dengan', 'dari',
        'info', 'informasi', 'ini', 'itu', 'kah', 'ke', 'mohon', 'pada', 'per', 'saya', 'seputar', 'tentang', 'tolong',
        'untuk', 'yang', 'kata', 'kunci', 'keyword', 'keywords', 'words', 'word',
        'a', 'about', 'and', 'any', 'can', 'find', 'for', 'give', 'how', 'in', 'is', 'me',
        'of', 'on', 'please', 'show', 'the', 'to', 'what', 'where', 'with',
        'prof', 'profesor', 'professor', 'dr', 'drs', 'dra', 'drg', 'drh', 'ir',
    ];

    public function buildContext(): array
    {
        $publicStatuses = ['published', 'archived'];

        $guruBesars = GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas', 'prodi'])
            ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
            ->whereIn('orasi_ilmiahs.status', $publicStatuses)
            ->select('guru_besars.*')
            ->orderBy('guru_besars.nama')
            ->get()
            ->map(fn (GuruBesar $guru) => $this->serializeGuruBesar($guru))
            ->values()
            ->all();

        $orasiList = OrasiIlmiah::query()
            ->withCount('guruBesars')
            ->whereIn('status', $publicStatuses)
            ->orderByDesc('tahun')
            ->orderByDesc('tanggal_pelaksanaan')
            ->get()
            ->map(fn (OrasiIlmiah $orasi) => [
                'id' => $orasi->id,
                'judul' => $orasi->judul,
                'judul_lengkap' => $orasi->judul_lengkap,
                'tahun' => $orasi->tahun,
                'status' => $orasi->status,
                'tanggal' => $orasi->tanggal_pelaksanaan?->translatedFormat('d F Y'),
                'guru_count' => (int) $orasi->guru_besars_count,
                'url' => route('portal.daftar-orasi', ['orasi' => $orasi->id]),
            ])
            ->values()
            ->all();

        $archiveYears = collect($orasiList)
            ->pluck('tahun')
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        $videoCount = collect($guruBesars)->where('has_video', true)->count();
        $docCount = collect($guruBesars)->filter(fn (array $g) => $g['has_dokumen'])->count();

        $fakultasStats = collect($guruBesars)
            ->map(fn (array $g) => $g['fakultas'])
            ->filter(fn (string $label) => $label !== '-')
            ->countBy()
            ->sortDesc()
            ->map(fn (int $total, string $label) => ['label' => $label, 'total' => $total])
            ->values()
            ->all();

        $yearStats = collect($guruBesars)
            ->map(fn (array $g) => $g['tahun_orasi'])
            ->filter()
            ->countBy()
            ->sortKeysDesc()
            ->map(fn (int $total, $year) => ['tahun' => (int) $year, 'total' => $total])
            ->values()
            ->all();

        return [
            'total_guru_besar' => count($guruBesars),
            'total_orasi' => count($orasiList),
            'total_video' => $videoCount,
            'total_dokumen' => $docCount,
            'total_fakultas' => count($fakultasStats),
            'archive_years' => $archiveYears,
            'fakultas_stats' => $fakultasStats,
            'year_stats' => $yearStats,
            'guru_besars' => $guruBesars,
            'orasi_list' => $orasiList,
            'urls' => [
                'home' => route('home'),
                'guru_besar' => route('portal.guru-besar'),
                'daftar_orasi' => route('portal.daftar-orasi'),
                'video_orasi' => route('portal.video-orasi'),
                'dokumen_orasi' => route('portal.dokumen-orasi'),
                'statistik' => route('portal.statistik'),
            ],
        ];
    }

    /**
     * @return array{message: string, type: string, suggestions?: array<int, string>}
     */
    public function reply(string $query, ?array $context = null): array
    {
        $context ??= $this->buildContext();
        $normalized = $this->normalize($query);

        if ($normalized === '') {
            return $this->emptyQueryResponse();
        }

        if ($this->matchesAny($normalized, $this->greetingPatterns)) {
            return $this->greetingResponse($context);
        }

        if ($this->isIdentityQuery($normalized)) {
            return $this->identityResponse($context);
        }

        if ($this->matchesAny($normalized, $this->thanksPatterns)) {
            return [
                'message' => 'Sama-sama. Jika masih ada pertanyaan seputar Orasi Ilmiah Guru Besar Universitas Mulawarman, silakan sampaikan melalui kolom percakapan ini.',
                'type' => 'thanks',
                'suggestions' => $this->defaultSuggestions(),
            ];
        }

        if ($this->hasGuruSpecificIntent($normalized)) {
            $matchedGuru = $this->findGuruBesarInQuery($normalized, $context['guru_besars']);

            if ($matchedGuru !== null) {
                return $this->guruBesarResponse($normalized, $matchedGuru, $context);
            }
        }

        $structured = $this->matchStructuredIntent($normalized, $context);

        if ($structured !== null) {
            return $structured;
        }

        if (! $this->isIntentOnlyQuery($normalized) && ! $this->isAggregateFacultyQuery($normalized, $context)) {
            $matchedGuru = $this->findGuruBesarInQuery($normalized, $context['guru_besars']);

            if ($matchedGuru !== null) {
                return $this->guruBesarResponse($normalized, $matchedGuru, $context);
            }
        }

        if ($this->hasSubstantiveSearchTokens($normalized) && ! $this->isIntentOnlyQuery($normalized)) {
            $keywordSearch = $this->matchKeywordSearch($normalized, $context);

            if ($keywordSearch !== null) {
                return $keywordSearch;
            }
        }

        return $this->fallbackResponse($context);
    }

    /** @return array<int, string> */
    public function defaultSuggestions(): array
    {
        return [
            'Apa itu orasi ilmiah?',
            'Berapa jumlah guru besar?',
            'Statistik Fakultas Teknik',
            'Statistik per fakultas',
            'Statistik per tahun',
            'Daftar video orasi',
        ];
    }

    /** @return array<int, string> */
    public function welcomeSuggestions(): array
    {
        return [
            'Berapa total guru besar?',
            'Jumlah guru besar Fakultas Teknik',
            'Statistik Fakultas Farmasi',
            'Statistik orasi per tahun',
            'Di mana dokumen orasi?',
        ];
    }

    private function serializeGuruBesar(GuruBesar $guru): array
    {
        $hasDokumen = $guru->hasFileOrasi() || $guru->hasPpt() || $guru->hasPiagam() || $guru->hasSertifikat();

        return [
            'id' => $guru->id,
            'nama' => $guru->nama,
            'nama_normalized' => $this->normalizeName($guru->nama),
            'bidang_ilmu' => $guru->bidang_ilmu ?: '-',
            'judul_orasi' => $guru->judul_orasi ?: '-',
            'fakultas' => $guru->displayFakultas(),
            'prodi' => $guru->displayProdi(),
            'tahun_orasi' => $guru->archiveYear(),
            'tmt' => $guru->tmt?->translatedFormat('d F Y'),
            'has_video' => $guru->hasVideo(),
            'has_dokumen' => $hasDokumen,
            'has_naskah' => $guru->hasFileOrasi(),
            'has_ppt' => $guru->hasPpt(),
            'orasi_judul' => $guru->orasiIlmiah?->judul_lengkap,
            'url' => route('portal.guru-besar.show', $guru),
        ];
    }

    private function normalize(string $text): string
    {
        $text = Str::ascii(mb_strtolower(trim($text)));
        $text = trim($text, " \t\n\r\0\x0B\\");
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    private function normalizeName(string $name): string
    {
        $name = $this->normalize($name);
        $name = preg_replace('/\b(prof|professor|dr|drg|drh|ir|s\.?si|m\.?si|m\.?kom|ph\.?d|skom|sked)\b\.?/u', ' ', $name) ?? $name;

        return trim(preg_replace('/\s+/u', ' ', $name) ?? $name);
    }

    private function matchesAny(string $haystack, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if ($haystack === $pattern || str_contains($haystack, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function containsKeywords(string $text, array $keywords): bool
    {
        foreach ($this->expandKeywords($keywords) as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /** @return array<int, string> */
    private function expandKeywords(array $keywords): array
    {
        $expanded = [];

        foreach ($keywords as $keyword) {
            $normalized = $this->normalize((string) $keyword);

            if ($normalized === '') {
                continue;
            }

            $expanded[] = $normalized;

            if (isset($this->keywordAliases[$normalized])) {
                foreach ($this->keywordAliases[$normalized] as $alias) {
                    $expanded[] = $this->normalize($alias);
                }

                continue;
            }

            foreach ($this->keywordAliases as $aliases) {
                $normalizedAliases = array_map(fn (string $alias) => $this->normalize($alias), $aliases);

                if (! in_array($normalized, $normalizedAliases, true)) {
                    continue;
                }

                foreach ($aliases as $alias) {
                    $expanded[] = $this->normalize($alias);
                }

                break;
            }
        }

        return array_values(array_unique(array_filter($expanded)));
    }

    /** @return array<int, string> */
    private function searchTokens(string $query): array
    {
        $intentWords = collect($this->keywordAliases)
            ->flatten()
            ->push(...array_keys($this->keywordAliases))
            ->flatMap(fn (string $keyword) => explode(' ', $this->normalize($keyword)))
            ->filter()
            ->all();

        $stopWords = array_unique(array_merge($this->searchStopWords, $intentWords, [
            'orasi', 'ilmiah', 'guru', 'besar', 'unmul', 'universitas', 'mulawarman',
        ]));

        return collect(explode(' ', $query))
            ->map(fn (string $token) => trim($token))
            ->filter(fn (string $token) => strlen($token) >= 3 || preg_match('/^20\d{2}$/', $token))
            ->reject(fn (string $token) => in_array($token, $stopWords, true))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $tokens
     */
    private function scoreTextAgainstTokens(?string $text, array $tokens, int $weight): int
    {
        $haystack = $this->normalize((string) $text);
        $score = 0;

        foreach ($tokens as $token) {
            if ($this->haystackContainsToken($haystack, $token)) {
                $score += $weight;
            }
        }

        return $score;
    }

    private function haystackContainsToken(string $haystack, string $token): bool
    {
        if ($haystack === '' || $token === '') {
            return false;
        }

        if (preg_match('/^20\d{2}$/', $token)) {
            return str_contains($haystack, $token);
        }

        if (strlen($token) < 4) {
            return false;
        }

        $words = preg_split('/\s+/u', $haystack) ?: [];

        foreach ($words as $word) {
            if (strlen($word) < 4) {
                continue;
            }

            if ($word === $token) {
                return true;
            }

            if (strlen($token) >= 5 && str_starts_with($word, $token)) {
                return true;
            }

            if (strlen($word) >= 5 && str_starts_with($token, $word)) {
                return true;
            }
        }

        return strlen($token) >= 7 && str_contains($haystack, $token);
    }

    private function hasGuruSpecificIntent(string $query): bool
    {
        if ($this->containsKeywords($query, ['title', 'field', 'video', 'document', 'profile'])) {
            return true;
        }

        if ($this->containsKeywords($query, ['faculty']) && ! $this->containsKeywords($query, ['statistic', 'count'])) {
            return true;
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function isAggregateFacultyQuery(string $query, array $context): bool
    {
        if (! $this->containsKeywords($query, ['statistic', 'count'])) {
            return false;
        }

        return $this->containsKeywords($query, ['faculty'])
            || $this->resolveFacultyFromQuery($query, $context) !== null;
    }

    private function queryReferencesDistinctPersonName(string $query, array $guru): bool
    {
        $nameTokens = array_filter(
            explode(' ', $guru['nama_normalized'] ?? ''),
            fn (string $token) => strlen($token) >= 5
        );

        foreach ($nameTokens as $token) {
            if ($this->isLikelyFacultyWord($token)) {
                continue;
            }

            if ($this->haystackContainsToken($query, $token)) {
                return true;
            }
        }

        return false;
    }

    private function isLikelyFacultyWord(string $token): bool
    {
        $facultyWords = [
            'farmasi', 'teknik', 'pertanian', 'perikanan', 'kedokteran', 'ekonomi',
            'bisnis', 'kehutanan', 'hukum', 'keguruan', 'pendidikan', 'matematika',
            'sosial', 'politik', 'kelautan', 'masyarakat', 'kesehatan',
        ];

        return in_array($token, $facultyWords, true);
    }

    private function isExplicitFacultyQuery(string $query): bool
    {
        if ($this->containsKeywords($query, ['statistic', 'count', 'video', 'document', 'faculty'])) {
            return true;
        }

        $tokens = $this->searchTokens($query);

        return $tokens !== [] && count($tokens) <= 2;
    }

    private function hasSubstantiveSearchTokens(string $query): bool
    {
        return $this->searchTokens($query) !== [];
    }

    private function isIntentOnlyQuery(string $query): bool
    {
        if (! $this->containsKeywords($query, [
            'statistic', 'video', 'document', 'agenda', 'count', 'help',
            'archive', 'faculty', 'latest', 'about', 'orator', 'profile',
        ])) {
            return false;
        }

        return ! $this->hasSubstantiveSearchTokens($query);
    }

    private function fuzzyTokenMatches(string $needle, string $candidate): bool
    {
        $needle = $this->normalize($needle);
        $candidate = $this->normalize($candidate);

        if ($needle === '' || $candidate === '') {
            return false;
        }

        if ($needle === $candidate || str_contains($candidate, $needle) || str_contains($needle, $candidate)) {
            return true;
        }

        $minLength = min(strlen($needle), strlen($candidate));
        $maxLength = max(strlen($needle), strlen($candidate));

        if ($minLength < 5 || ($maxLength - $minLength) > 2) {
            return false;
        }

        $distance = levenshtein($needle, $candidate);
        $allowedDistance = $minLength >= 8 ? 2 : 1;

        return $distance <= $allowedDistance;
    }

    /**
     * @param  array<int, string>  $tokens
     */
    private function scoreTextAgainstTokensFuzzy(?string $text, array $tokens, int $weight): int
    {
        $words = collect(explode(' ', $this->normalize((string) $text)))
            ->filter(fn (string $word) => strlen($word) >= 4)
            ->values()
            ->all();
        $score = 0;

        foreach ($tokens as $token) {
            foreach ($words as $word) {
                if ($this->fuzzyTokenMatches($token, $word)) {
                    $score += $weight;
                    break;
                }
            }
        }

        return $score;
    }

    /**
     * @param  array<int, array<string, mixed>>  $guruList
     * @return array<string, mixed>|null
     */
    private function findGuruBesarInQuery(string $query, array $guruList): ?array
    {
        $best = null;
        $bestScore = 0;
        $queryTokens = $this->searchTokens($query);

        foreach ($guruList as $guru) {
            $name = $guru['nama_normalized'];

            if ($name === '') {
                continue;
            }

            if (str_contains($query, $name)) {
                $score = strlen($name) + 100;

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = $guru;
                }

                continue;
            }

            $tokens = array_filter(explode(' ', $name), fn (string $token) => strlen($token) >= 4);

            foreach ($tokens as $token) {
                if (str_contains($query, $token)) {
                    $score = strlen($token) + 50;

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $best = $guru;
                    }
                }

                foreach ($queryTokens as $queryToken) {
                    if (! $this->fuzzyTokenMatches($queryToken, $token)) {
                        continue;
                    }

                    $score = strlen($token) + 45;

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $best = $guru;
                    }
                }
            }
        }

        return $bestScore >= 50 ? $best : null;
    }

    /**
     * @param  array<string, mixed>  $guru
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions?: array<int, string>}
     */
    private function guruBesarResponse(string $query, array $guru, array $context): array
    {
        $name = e($guru['nama']);
        $profileUrl = e($guru['url']);

        if ($this->containsKeywords($query, ['faculty']) && ! $this->containsKeywords($query, ['statistic', 'count'])) {
            return [
                'message' => "Informasi unit kerja <strong>{$name}</strong>:<br>Fakultas: {$this->text($guru['fakultas'])}<br>Prodi: {$this->text($guru['prodi'])}",
                'type' => 'guru_fakultas',
                'suggestions' => $this->guruFollowUpSuggestions($guru),
            ];
        }

        if ($this->containsKeywords($query, ['field'])) {
            return [
                'message' => "Bidang ilmu <strong>{$name}</strong>:<br>{$this->text($guru['bidang_ilmu'])}",
                'type' => 'guru_bidang',
                'suggestions' => $this->guruFollowUpSuggestions($guru),
            ];
        }

        if ($this->containsKeywords($query, ['title'])) {
            return [
                'message' => "Berikut judul orasi <strong>{$name}</strong>:<br><br><em>{$this->text($guru['judul_orasi'])}</em><br><br><a href=\"{$profileUrl}\">Buka profil lengkap</a>",
                'type' => 'guru_judul',
                'suggestions' => $this->guruFollowUpSuggestions($guru),
            ];
        }

        if ($this->containsKeywords($query, ['video'])) {
            if ($guru['has_video']) {
                return [
                    'message' => "Video orasi <strong>{$name}</strong> tersedia. Buka profil untuk menonton:<br><a href=\"{$profileUrl}\">Lihat profil &amp; video</a><br>Atau kunjungi halaman <a href=\"{$this->text($context['urls']['video_orasi'], false)}\">Video Orasi</a>.",
                    'type' => 'guru_video',
                ];
            }

            return [
                'message' => "Video orasi untuk <strong>{$name}</strong> belum tersedia di portal.",
                'type' => 'guru_video_missing',
            ];
        }

        if ($this->containsKeywords($query, ['document'])) {
            $parts = [];

            if ($guru['has_naskah']) {
                $parts[] = 'Naskah orasi';
            }

            if ($guru['has_ppt']) {
                $parts[] = 'PPT';
            }

            if ($guru['has_dokumen'] && empty($parts)) {
                $parts[] = 'Dokumen pendukung';
            }

            if ($parts === []) {
                return [
                    'message' => "Dokumen orasi untuk <strong>{$name}</strong> belum tersedia di portal.",
                    'type' => 'guru_dokumen_missing',
                ];
            }

            return [
                'message' => "Dokumen <strong>{$name}</strong>: ".implode(', ', $parts).".<br><a href=\"{$profileUrl}\">Buka profil untuk unduh</a> atau lihat <a href=\"{$this->text($context['urls']['dokumen_orasi'], false)}\">Dokumen Orasi</a>.",
                'type' => 'guru_dokumen',
            ];
        }

        if ($this->containsKeywords($query, ['archive', 'agenda', 'tmt', 'tanggal', 'kapan'])) {
            $year = $guru['tahun_orasi'] ?: '-';
            $tmt = $guru['tmt'] ?: '-';

            return [
                'message' => "<strong>{$name}</strong><br>Tahun orasi: {$year}<br>TMT: {$tmt}<br>Agenda: {$this->text($guru['orasi_judul'] ?? '-')}",
                'type' => 'guru_tahun',
            ];
        }

        if ($this->containsKeywords($query, ['profile'])) {
            return [
                'message' => $this->formatGuruProfile($guru),
                'type' => 'guru_profil',
                'suggestions' => $this->guruFollowUpSuggestions($guru),
            ];
        }

        return [
            'message' => $this->formatGuruProfile($guru),
            'type' => 'guru_default',
            'suggestions' => $this->guruFollowUpSuggestions($guru),
        ];
    }

    /**
     * @param  array<string, mixed>  $guru
     * @return array<int, string>
     */
    private function guruFollowUpSuggestions(array $guru): array
    {
        $label = $this->guruShortLabel($guru);

        return [
            "Judul orasi {$label}",
            "Video {$label}",
            "Fakultas {$label}",
        ];
    }

    /**
     * @param  array<string, mixed>  $guru
     */
    private function guruShortLabel(array $guru): string
    {
        $tokens = array_values(array_filter(
            explode(' ', $guru['nama_normalized'] ?? ''),
            fn (string $token) => strlen($token) >= 4
        ));

        if ($tokens === []) {
            return 'guru besar';
        }

        return ucfirst($tokens[0]);
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions?: array<int, string>}|null
     */
    private function matchStructuredIntent(string $query, array $context): ?array
    {
        if ($this->containsKeywords($query, ['about']) && str_contains($query, 'orasi')) {
            return [
                'message' => 'Orasi Ilmiah Guru Besar Universitas Mulawarman adalah forum akademik resmi bagi guru besar untuk menyampaikan gagasan, capaian, dan kontribusi keilmuannya. Portal ini menyediakan informasi agenda, profil guru besar, video, dokumen naskah atau presentasi, serta statistik arsip orasi.',
                'type' => 'about_orasi',
                'suggestions' => $this->defaultSuggestions(),
            ];
        }

        if ($this->containsKeywords($query, ['unmul', 'universitas mulawarman', 'kampus'])) {
            return [
                'message' => 'Portal ini memuat informasi Orasi Ilmiah Guru Besar Universitas Mulawarman. Saya, Si Ora, dapat membantu menelusuri agenda, profil guru besar, video, dokumen, dan statistik orasi.',
                'type' => 'about_unmul',
            ];
        }

        $facultySpecific = $this->matchFacultySpecificIntent($query, $context);

        if ($facultySpecific !== null) {
            return $facultySpecific;
        }

        if ($this->containsKeywords($query, ['statistic'])) {
            if ($this->containsKeywords($query, ['faculty'])) {
                return [
                    'message' => $this->formatFakultasStats($context),
                    'type' => 'stat_fakultas',
                    'suggestions' => ['Statistik orasi per tahun', 'Berapa jumlah guru besar?'],
                ];
            }

            if ($this->containsKeywords($query, ['archive']) || str_contains($query, 'tahun')) {
                return [
                    'message' => $this->formatYearStats($context),
                    'type' => 'stat_tahun',
                    'suggestions' => ['Agenda orasi apa saja?', 'Statistik per fakultas'],
                ];
            }

            return [
                'message' => $this->formatOverallStats($context),
                'type' => 'stat_ringkas',
                'suggestions' => ['Statistik per fakultas', 'Statistik Fakultas Teknik', 'Statistik per tahun'],
            ];
        }

        if ($this->containsKeywords($query, ['count']) && $this->containsKeywords($query, ['orator'])) {
            return [
                'message' => "Total guru besar yang tercatat di portal: <strong>{$context['total_guru_besar']}</strong> orang.<br>Informasi ini mencakup seluruh fakultas yang berpartisipasi dalam Orasi Ilmiah Guru Besar Universitas Mulawarman.<br><a href=\"{$this->text($context['urls']['guru_besar'], false)}\">Lihat daftar lengkap</a>",
                'type' => 'stat_guru',
                'suggestions' => ['Jumlah guru besar Fakultas Teknik', 'Statistik per fakultas'],
            ];
        }

        if ($this->containsKeywords($query, ['count']) && str_contains($query, 'orasi')) {
            return [
                'message' => "Total agenda orasi ilmiah: <strong>{$context['total_orasi']}</strong>.<br>Buka <a href=\"{$this->text($context['urls']['daftar_orasi'], false)}\">Daftar Orasi</a> untuk detail tiap tahun.",
                'type' => 'stat_orasi',
            ];
        }

        if ($this->containsKeywords($query, ['video'])) {
            $hasSearchSubject = $this->hasSubstantiveSearchTokens($query);
            $wantsVideoMeta = $this->containsKeywords($query, ['daftar', 'list', 'cari', 'semua', 'all', 'count', 'berapa', 'jumlah', 'total']);

            if ($hasSearchSubject && ! $wantsVideoMeta) {
                return null;
            }

            if ($wantsVideoMeta) {
                return [
                    'message' => $this->formatVideoList($context),
                    'type' => 'list_video',
                    'suggestions' => ['Berapa jumlah guru besar?', 'Statistik per tahun'],
                ];
            }

            return [
                'message' => "Video orasi tersedia: <strong>{$context['total_video']}</strong> dari {$context['total_guru_besar']} guru besar.<br>Kunjungi <a href=\"{$this->text($context['urls']['video_orasi'], false)}\">Video Orasi</a>.",
                'type' => 'stat_video',
            ];
        }

        if ($this->containsKeywords($query, ['document'])) {
            $hasSearchSubject = $this->hasSubstantiveSearchTokens($query);
            $wantsDocumentMeta = $this->containsKeywords($query, ['faculty', 'daftar', 'list', 'cari', 'semua', 'all', 'count', 'berapa', 'jumlah', 'total']);

            if ($this->containsKeywords($query, ['faculty'])) {
                return [
                    'message' => $this->formatDocumentStatsByFaculty($context),
                    'type' => 'stat_dokumen_fakultas',
                    'suggestions' => ['Statistik per fakultas', 'Di mana dokumen orasi?'],
                ];
            }

            if ($hasSearchSubject && ! $wantsDocumentMeta) {
                return null;
            }

            return [
                'message' => "Guru besar dengan dokumen orasi: <strong>{$context['total_dokumen']}</strong>.<br>Lihat <a href=\"{$this->text($context['urls']['dokumen_orasi'], false)}\">Dokumen Orasi</a>.",
                'type' => 'stat_dokumen',
            ];
        }

        if ($this->containsKeywords($query, ['faculty']) && ! $this->hasSubstantiveSearchTokens($query)) {
            return [
                'message' => $this->formatFakultasStats($context),
                'type' => 'stat_fakultas',
                'suggestions' => ['Statistik orasi per tahun', 'Berapa jumlah guru besar?'],
            ];
        }

        if ($this->containsKeywords($query, ['archive']) && ! $this->hasSubstantiveSearchTokens($query)) {
            return [
                'message' => $this->formatYearStats($context),
                'type' => 'stat_tahun',
                'suggestions' => ['Agenda orasi apa saja?', 'Statistik per fakultas'],
            ];
        }

        if ($this->containsKeywords($query, ['agenda']) || ($this->containsKeywords($query, ['orasi']) && $this->containsKeywords($query, ['daftar']))) {
            return [
                'message' => $this->formatOrasiList($context),
                'type' => 'list_orasi',
            ];
        }

        if ($this->containsKeywords($query, ['orator']) && ($this->containsKeywords($query, ['profile']) || $this->containsKeywords($query, ['daftar', 'list', 'nama', 'cari']))) {
            return [
                'message' => $this->formatGuruList($context),
                'type' => 'list_guru',
                'suggestions' => array_slice(array_map(fn (array $g) => 'Profil '.$this->guruShortLabel($g), $context['guru_besars']), 0, 3),
            ];
        }

        if ($this->containsKeywords($query, ['help'])) {
            return [
                'message' => "Navigasi portal:<br>&bull; <a href=\"{$this->text($context['urls']['daftar_orasi'], false)}\">Agenda Orasi</a> — daftar kegiatan per tahun<br>&bull; <a href=\"{$this->text($context['urls']['guru_besar'], false)}\">Guru Besar</a> — profil lengkap<br>&bull; <a href=\"{$this->text($context['urls']['video_orasi'], false)}\">Video Orasi</a><br>&bull; <a href=\"{$this->text($context['urls']['dokumen_orasi'], false)}\">Dokumen Orasi</a><br>&bull; <a href=\"{$this->text($context['urls']['statistik'], false)}\">Statistik</a><br><br>Untuk profil spesifik, ketik <em>Profil (nama guru besar)</em>.",
                'type' => 'navigasi',
            ];
        }

        if ($this->containsKeywords($query, ['bisa tanya apa', 'fitur'])) {
            return [
                'message' => 'Saya dapat menjawab pertanyaan tentang:<br>&bull; Jumlah guru besar, agenda, video, dokumen<br>&bull; Statistik per fakultas dan tahun<br>&bull; Profil guru besar (ketik nama)<br>&bull; Judul orasi, fakultas, video, dokumen per guru<br><br>Coba salah satu pertanyaan cepat di bawah.',
                'type' => 'bantuan',
                'suggestions' => $this->defaultSuggestions(),
            ];
        }

        if ($this->containsKeywords($query, ['latest'])) {
            $latestOrasi = $context['orasi_list'][0] ?? null;

            if ($latestOrasi) {
                return [
                    'message' => "Agenda orasi terbaru: <strong>{$this->text($latestOrasi['judul_lengkap'])}</strong> ({$latestOrasi['guru_count']} guru besar).<br><a href=\"{$this->text($latestOrasi['url'], false)}\">Lihat agenda</a>",
                    'type' => 'terbaru_orasi',
                ];
            }
        }

        if (preg_match('/\b(20\d{2})\b/', $query, $matches) && ! $this->containsKeywords($query, ['statistic', 'video', 'document'])) {
            $year = (int) $matches[1];

            return $this->yearSpecificResponse($year, $context);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions?: array<int, string>}|null
     */
    private function matchFacultySpecificIntent(string $query, array $context): ?array
    {
        $facultyLabel = $this->resolveFacultyFromQuery($query, $context);

        if ($facultyLabel === null) {
            return null;
        }

        $matchedGuru = $this->findGuruBesarInQuery($query, $context['guru_besars']);

        if ($matchedGuru !== null) {
            if ($this->hasGuruSpecificIntent($query)) {
                return null;
            }

            if ($this->queryReferencesDistinctPersonName($query, $matchedGuru)) {
                return null;
            }
        }

        if (! $this->isExplicitFacultyQuery($query)) {
            return null;
        }

        $wantsCount = $this->containsKeywords($query, ['count']);
        $wantsStats = $this->containsKeywords($query, ['statistic']);
        $wantsVideo = $this->containsKeywords($query, ['video']);
        $wantsDocument = $this->containsKeywords($query, ['document']);

        if ($wantsVideo) {
            return [
                'message' => $this->formatFacultyVideoList($facultyLabel, $context),
                'type' => 'list_video_fakultas',
                'suggestions' => ["Statistik {$this->facultyShortName($facultyLabel)}", 'Daftar video orasi'],
            ];
        }

        if ($wantsDocument) {
            return [
                'message' => $this->formatFacultyDocumentList($facultyLabel, $context),
                'type' => 'list_dokumen_fakultas',
                'suggestions' => ["Statistik {$this->facultyShortName($facultyLabel)}", 'Statistik per fakultas'],
            ];
        }

        return [
            'message' => $this->formatSingleFacultyStats($facultyLabel, $context, $wantsCount && ! $wantsStats),
            'type' => $wantsCount && ! $wantsStats ? 'stat_guru_fakultas' : 'stat_fakultas_detail',
            'suggestions' => [
                'Statistik per fakultas',
                'Jumlah guru besar Fakultas Teknik',
                'Statistik per tahun',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function resolveFacultyFromQuery(string $query, array $context): ?string
    {
        $normalizedQuery = $this->normalize($query);
        $bestLabel = null;
        $bestScore = 0;

        foreach ($context['fakultas_stats'] as $row) {
            $score = $this->scoreFacultyMatch($normalizedQuery, (string) $row['label']);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestLabel = (string) $row['label'];
            }
        }

        return $bestScore >= 10 ? $bestLabel : null;
    }

    private function scoreFacultyMatch(string $query, string $label): int
    {
        $normalizedLabel = $this->normalize($label);
        $slug = preg_replace('/^fakultas\s+/', '', $normalizedLabel) ?? $normalizedLabel;

        if ($slug !== '' && (str_contains($query, $normalizedLabel) || str_contains($query, $slug))) {
            return 100;
        }

        $genericWords = ['dan', 'ilmu', 'fakultas', 'serta', 'program', 'studi', 'universitas'];
        $score = 0;

        foreach (explode(' ', $slug) as $word) {
            if (strlen($word) < 4 || in_array($word, $genericWords, true)) {
                continue;
            }

            if ($this->haystackContainsToken($query, $word)) {
                $score += strlen($word) >= 7 ? 18 : 12;
            }
        }

        foreach ($this->facultyAliasesFor($label) as $alias) {
            if ($this->haystackContainsToken($query, $alias)) {
                $score += 14;
            }
        }

        return $score;
    }

    /** @return array<int, string> */
    private function facultyAliasesFor(string $label): array
    {
        $slug = $this->normalize(preg_replace('/^fakultas\s+/i', '', $label) ?? $label);

        $known = [
            'farmasi' => ['farmasi'],
            'teknik' => ['teknik', 'ft'],
            'pertanian' => ['pertanian', 'faperta'],
            'perikanan dan ilmu kelautan' => ['perikanan', 'kelautan', 'fpi'],
            'kedokteran' => ['kedokteran', 'dokter', 'fk'],
            'ekonomi dan bisnis' => ['ekonomi', 'bisnis', 'feb'],
            'kehutanan' => ['kehutanan', 'hutan'],
            'hukum' => ['hukum', 'fh'],
            'keguruan dan ilmu pendidikan' => ['keguruan', 'pendidikan', 'fkip'],
            'matematika dan ilmu pengetahuan alam' => ['matematika', 'mipa', 'fmipa', 'pengetahuan', 'alam'],
            'ilmu sosial dan ilmu politik' => ['sosial', 'politik', 'fisip', 'sospol'],
        ];

        foreach ($known as $key => $aliases) {
            if (str_contains($slug, $key) || $slug === $key) {
                return $aliases;
            }
        }

        return [];
    }

    private function facultyShortName(string $label): string
    {
        $short = preg_replace('/^fakultas\s+/i', '', $label) ?? $label;

        return 'Fakultas '.$short;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatOverallStats(array $context): string
    {
        return 'Ringkasan statistik portal Orasi Ilmiah Guru Besar Universitas Mulawarman:<br><br>'
            ."&bull; Guru besar: <strong>{$context['total_guru_besar']}</strong> orang<br>"
            ."&bull; Agenda orasi: <strong>{$context['total_orasi']}</strong> kegiatan<br>"
            ."&bull; Video orasi: <strong>{$context['total_video']}</strong> tersedia<br>"
            ."&bull; Dokumen orasi: <strong>{$context['total_dokumen']}</strong> tersedia<br>"
            ."&bull; Fakultas terlibat: <strong>{$context['total_fakultas']}</strong><br><br>"
            ."Untuk statistik per fakultas, ketik misalnya <em>Statistik Farmasi</em> atau <em>Jumlah guru besar Fakultas Teknik</em>.<br>"
            ."<a href=\"{$this->text($context['urls']['statistik'], false)}\">Buka halaman Statistik</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatSingleFacultyStats(string $facultyLabel, array $context, bool $countOnly = false): string
    {
        $gurus = collect($context['guru_besars'])
            ->filter(fn (array $guru) => ($guru['fakultas'] ?? '-') === $facultyLabel)
            ->values();

        $total = $gurus->count();
        $videoCount = $gurus->where('has_video', true)->count();
        $docCount = $gurus->filter(fn (array $guru) => $guru['has_dokumen'] ?? false)->count();
        $facultyName = e($facultyLabel);

        if ($total === 0) {
            return "Belum ada data guru besar untuk <strong>{$facultyName}</strong> di portal ini.";
        }

        if ($countOnly) {
            return "Berdasarkan data portal, <strong>{$facultyName}</strong> memiliki <strong>{$total}</strong> guru besar yang tercatat dalam Orasi Ilmiah Guru Besar Universitas Mulawarman.<br><br>"
                ."Rincian media: {$videoCount} video dan {$docCount} dokumen orasi tersedia.<br>"
                ."<a href=\"{$this->text($context['urls']['guru_besar'], false)}\">Lihat daftar guru besar</a>";
        }

        $yearLines = $gurus
            ->groupBy(fn (array $guru) => (string) ($guru['tahun_orasi'] ?: 'Tanpa Tahun'))
            ->sortKeysDesc()
            ->map(fn (Collection $items, string $year) => '&bull; Tahun '.$year.': '.$items->count().' guru besar')
            ->implode('<br>');

        $sampleList = $gurus
            ->take(5)
            ->map(function (array $guru) {
                $year = $guru['tahun_orasi'] ?: '-';

                return '&bull; <a href="'.$this->text($guru['url'], false).'">'.$this->text($guru['nama']).'</a> (Tahun '.$year.')';
            })
            ->implode('<br>');

        $more = $total > 5 ? '<br><em>Dan '.($total - 5).' guru besar lainnya.</em>' : '';

        return "Statistik Orasi Ilmiah — <strong>{$facultyName}</strong><br><br>"
            ."<strong>Ringkasan</strong><br>"
            ."&bull; Guru besar: <strong>{$total}</strong> orang<br>"
            ."&bull; Video orasi: {$videoCount} tersedia<br>"
            ."&bull; Dokumen orasi: {$docCount} tersedia<br><br>"
            ."<strong>Distribusi per tahun</strong><br>{$yearLines}<br><br>"
            ."<strong>Daftar guru besar</strong><br>{$sampleList}{$more}<br><br>"
            ."<a href=\"{$this->text($context['urls']['statistik'], false)}\">Lihat grafik statistik</a> &middot; "
            ."<a href=\"{$this->text($context['urls']['guru_besar'], false)}\">Buka direktori guru besar</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatFacultyVideoList(string $facultyLabel, array $context): string
    {
        $items = collect($context['guru_besars'])
            ->filter(fn (array $guru) => ($guru['fakultas'] ?? '-') === $facultyLabel && ($guru['has_video'] ?? false))
            ->take(8)
            ->map(function (array $guru) {
                $year = $guru['tahun_orasi'] ?: '-';

                return '&bull; <a href="'.$this->text($guru['url'], false).'">'.$this->text($guru['nama']).'</a> (Tahun '.$year.')';
            })
            ->implode('<br>');

        $facultyName = e($facultyLabel);

        if ($items === '') {
            return "Belum ada video orasi yang dipublikasikan untuk <strong>{$facultyName}</strong>.";
        }

        return "Video orasi — <strong>{$facultyName}</strong>:<br><br>{$items}<br><br>"
            ."<a href=\"{$this->text($context['urls']['video_orasi'], false)}\">Lihat semua video orasi</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatFacultyDocumentList(string $facultyLabel, array $context): string
    {
        $items = collect($context['guru_besars'])
            ->filter(fn (array $guru) => ($guru['fakultas'] ?? '-') === $facultyLabel && ($guru['has_dokumen'] ?? false))
            ->take(8)
            ->map(function (array $guru) {
                $parts = [];

                if ($guru['has_naskah'] ?? false) {
                    $parts[] = 'Naskah';
                }

                if ($guru['has_ppt'] ?? false) {
                    $parts[] = 'PPT';
                }

                $label = $parts !== [] ? ' ('.implode(', ', $parts).')' : '';

                return '&bull; <a href="'.$this->text($guru['url'], false).'">'.$this->text($guru['nama']).'</a>'.$label;
            })
            ->implode('<br>');

        $facultyName = e($facultyLabel);

        if ($items === '') {
            return "Belum ada dokumen orasi yang dipublikasikan untuk <strong>{$facultyName}</strong>.";
        }

        return "Dokumen orasi — <strong>{$facultyName}</strong>:<br><br>{$items}<br><br>"
            ."<a href=\"{$this->text($context['urls']['dokumen_orasi'], false)}\">Lihat semua dokumen orasi</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions?: array<int, string>}|null
     */
    private function matchKeywordSearch(string $query, array $context): ?array
    {
        $tokens = $this->searchTokens($query);

        if ($tokens === []) {
            return null;
        }

        $wantsVideo = $this->containsKeywords($query, ['video']);
        $wantsDocument = $this->containsKeywords($query, ['document']);

        $guruMatches = collect($context['guru_besars'])
            ->map(function (array $guru) use ($tokens, $wantsVideo, $wantsDocument) {
                $score = 0;
                $score += $this->scoreTextAgainstTokens($guru['nama'] ?? null, $tokens, 8);
                $score += $this->scoreTextAgainstTokensFuzzy($guru['nama'] ?? null, $tokens, 10);
                $score += $this->scoreTextAgainstTokens($guru['fakultas'] ?? null, $tokens, 6);
                $score += $this->scoreTextAgainstTokens($guru['prodi'] ?? null, $tokens, 5);
                $score += $this->scoreTextAgainstTokens($guru['bidang_ilmu'] ?? null, $tokens, 5);
                $score += $this->scoreTextAgainstTokens($guru['judul_orasi'] ?? null, $tokens, 4);
                $score += $this->scoreTextAgainstTokens((string) ($guru['tahun_orasi'] ?? ''), $tokens, 8);

                if ($wantsVideo && ($guru['has_video'] ?? false)) {
                    $score += 3;
                }

                if ($wantsDocument && ($guru['has_dokumen'] ?? false)) {
                    $score += 3;
                }

                return ['score' => $score, 'guru' => $guru];
            })
            ->filter(fn (array $row) => $row['score'] >= 5)
            ->sortByDesc('score')
            ->take(5)
            ->values();

        $orasiMatches = collect($context['orasi_list'])
            ->map(function (array $orasi) use ($tokens) {
                $score = 0;
                $score += $this->scoreTextAgainstTokens($orasi['judul'] ?? null, $tokens, 6);
                $score += $this->scoreTextAgainstTokens($orasi['judul_lengkap'] ?? null, $tokens, 6);
                $score += $this->scoreTextAgainstTokens((string) ($orasi['tahun'] ?? ''), $tokens, 8);

                return ['score' => $score, 'orasi' => $orasi];
            })
            ->filter(fn (array $row) => $row['score'] >= 6)
            ->sortByDesc('score')
            ->take(3)
            ->values();

        if ($guruMatches->isEmpty() && $orasiMatches->isEmpty()) {
            return null;
        }

        $message = 'Saya menemukan kecocokan berdasarkan kata kunci yang Anda berikan.';

        if ($guruMatches->isNotEmpty()) {
            $message .= '<br><br>Guru besar terkait:<br>';
            $message .= $guruMatches
                ->map(function (array $row) {
                    $guru = $row['guru'];
                    $year = $guru['tahun_orasi'] ?: '-';

                    return '&bull; <a href="'.$this->text($guru['url'], false).'">'.$this->text($guru['nama']).'</a>'
                        .' - '.$this->text($guru['fakultas'])
                        .' - Tahun '.$year;
                })
                ->implode('<br>');
        }

        if ($orasiMatches->isNotEmpty()) {
            $message .= '<br><br>Agenda terkait:<br>';
            $message .= $orasiMatches
                ->map(function (array $row) {
                    $orasi = $row['orasi'];

                    return '&bull; <a href="'.$this->text($orasi['url'], false).'">'.$this->text($orasi['judul_lengkap']).'</a>';
                })
                ->implode('<br>');
        }

        $message .= '<br><br>Anda dapat mempersempit pencarian dengan menambahkan kata kunci seperti video, dokumen, fakultas, bidang ilmu, atau tahun.';

        return [
            'message' => $message,
            'type' => 'keyword_search',
            'suggestions' => [
                'Statistik per fakultas',
                'Statistik per tahun',
                'Daftar video orasi',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions?: array<int, string>}
     */
    private function yearSpecificResponse(int $year, array $context): array
    {
        $orasi = collect($context['orasi_list'])->firstWhere('tahun', $year);
        $guruCount = collect($context['guru_besars'])->where('tahun_orasi', $year)->count();

        if ($orasi === null && $guruCount === 0) {
            return [
                'message' => "Tidak ada data orasi untuk tahun {$year} di portal.",
                'type' => 'year_not_found',
            ];
        }

        $message = "Data orasi tahun <strong>{$year}</strong>:<br>";

        if ($orasi) {
            $message .= "&bull; Agenda: {$this->text($orasi['judul_lengkap'])}<br>";
            $message .= "&bull; Guru besar: {$orasi['guru_count']}<br>";
            $message .= "<a href=\"{$this->text($orasi['url'], false)}\">Lihat agenda {$year}</a>";
        } else {
            $message .= "&bull; Guru besar tercatat: {$guruCount}";
        }

        return [
            'message' => $message,
            'type' => 'year_detail',
        ];
    }

    /**
     * @param  array<string, mixed>  $guru
     */
    private function formatGuruProfile(array $guru): string
    {
        $name = e($guru['nama']);
        $url = e($guru['url']);
        $video = $guru['has_video'] ? 'Tersedia' : 'Belum ada';
        $dokumen = $guru['has_dokumen'] ? 'Tersedia' : 'Belum ada';

        return "<strong>{$name}</strong><br>"
            ."Fakultas: {$this->text($guru['fakultas'])}<br>"
            ."Prodi: {$this->text($guru['prodi'])}<br>"
            ."Bidang ilmu: {$this->text($guru['bidang_ilmu'])}<br>"
            ."Judul orasi: {$this->text($guru['judul_orasi'])}<br>"
            ."Tahun: ".($guru['tahun_orasi'] ?: '-')."<br>"
            ."Video: {$video} | Dokumen: {$dokumen}<br>"
            ."<a href=\"{$url}\">Buka profil lengkap</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatVideoList(array $context): string
    {
        $items = collect($context['guru_besars'])
            ->where('has_video', true)
            ->take(8)
            ->map(function (array $guru) {
                $year = $guru['tahun_orasi'] ?: '-';

                return '&bull; <a href="'.$this->text($guru['url'], false).'">'.$this->text($guru['nama']).'</a>'
                    .' ('.$this->text($guru['fakultas']).', Tahun '.$year.')';
            })
            ->implode('<br>');

        if ($items === '') {
            return 'Belum ada video orasi yang dipublikasikan.';
        }

        return "Daftar video orasi (tampil 8):<br>{$items}<br><a href=\"{$this->text($context['urls']['video_orasi'], false)}\">Lihat semua video</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatDocumentStatsByFaculty(array $context): string
    {
        $lines = collect($context['guru_besars'])
            ->filter(fn (array $guru) => $guru['has_dokumen'] ?? false)
            ->groupBy(fn (array $guru) => $guru['fakultas'] ?? '-')
            ->map(fn (Collection $items, string $label) => [
                'label' => $label,
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->take(8)
            ->map(fn (array $row) => '&bull; '.$this->text($row['label']).': '.$row['total'].' guru besar')
            ->implode('<br>');

        if ($lines === '') {
            return 'Belum ada dokumen orasi per fakultas.';
        }

        return "Dokumen orasi per fakultas:<br>{$lines}<br><a href=\"{$this->text($context['urls']['dokumen_orasi'], false)}\">Buka halaman Dokumen Orasi</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatFakultasStats(array $context): string
    {
        $lines = collect($context['fakultas_stats'])
            ->take(10)
            ->map(fn (array $row) => '&bull; '.$this->text($row['label']).': <strong>'.$row['total'].'</strong> guru besar')
            ->implode('<br>');

        if ($lines === '') {
            return 'Belum ada data statistik fakultas.';
        }

        return "Statistik guru besar per fakultas ({$context['total_fakultas']} fakultas):<br><br>{$lines}<br><br>"
            ."Untuk detail per fakultas, ketik misalnya <em>Statistik Farmasi</em> atau <em>Jumlah guru besar Fakultas Teknik</em>.<br>"
            ."<a href=\"{$this->text($context['urls']['statistik'], false)}\">Lihat grafik statistik</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatYearStats(array $context): string
    {
        $years = collect($context['archive_years'])->implode(', ');

        $lines = collect($context['year_stats'])
            ->take(6)
            ->map(fn (array $row) => '&bull; '.$row['tahun'].': '.$row['total'].' guru besar')
            ->implode('<br>');

        return "Arsip tahun tersedia: {$years}<br><br>{$lines}<br><a href=\"{$this->text($context['urls']['statistik'], false)}\">Statistik lengkap</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatOrasiList(array $context): string
    {
        $items = collect($context['orasi_list'])
            ->take(5)
            ->map(function (array $orasi) {
                return '&bull; '.$this->text($orasi['judul_lengkap']).' ('.$orasi['guru_count'].' GB) — <a href="'.$this->text($orasi['url'], false).'">detail</a>';
            })
            ->implode('<br>');

        if ($items === '') {
            return 'Belum ada agenda orasi yang dipublikasikan.';
        }

        return "Agenda orasi ilmiah:<br>{$items}<br><a href=\"{$this->text($context['urls']['daftar_orasi'], false)}\">Lihat semua agenda</a>";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function formatGuruList(array $context): string
    {
        $items = collect($context['guru_besars'])
            ->take(8)
            ->map(function (array $guru) {
                return '&bull; <a href="'.$this->text($guru['url'], false).'">'.$this->text($guru['nama']).'</a> ('.$this->text($guru['fakultas']).')';
            })
            ->implode('<br>');

        $total = $context['total_guru_besar'];

        return "Direktori guru besar Orasi Ilmiah Universitas Mulawarman ({$total} total, ditampilkan 8):<br><br>{$items}<br><br>"
            ."<a href=\"{$this->text($context['urls']['guru_besar'], false)}\">Lihat semua guru besar</a><br><br>"
            .'<em>Untuk profil spesifik, ketik Profil (nama guru besar) atau Judul orasi (nama guru besar).</em>';
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions: array<int, string>}
     */
    private function greetingResponse(array $context): array
    {
        return [
            'message' => 'Selamat datang. Perkenalkan, saya <strong>Si Ora</strong>, asisten informasi '.$this->portalLabel().'. Saya dapat membantu menelusuri agenda orasi, profil guru besar, statistik, video, dokumen, serta pencarian berdasarkan kata kunci.',
            'type' => 'greeting',
            'suggestions' => $this->welcomeSuggestions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions: array<int, string>}
     */
    private function identityResponse(array $context): array
    {
        return [
            'message' => 'Perkenalkan, saya <strong>Si Ora</strong>, asisten informasi '.$this->portalLabel().'.<br><br>'
                .'Saya membantu menjawab pertanyaan seputar agenda orasi ilmiah, profil guru besar, statistik, video, dan dokumen pada portal Universitas Mulawarman.',
            'type' => 'identity',
            'suggestions' => $this->welcomeSuggestions(),
        ];
    }

    private function isIdentityQuery(string $query): bool
    {
        if ($this->matchesAny($query, $this->identityPatterns)) {
            return true;
        }

        return str_contains($query, 'perkenalkan') && str_contains($query, 'siapa');
    }

    private function portalLabel(): string
    {
        return 'Portal Orasi Ilmiah Guru Besar Universitas Mulawarman';
    }

    /**
     * @return array{message: string, type: string, suggestions: array<int, string>}
     */
    private function emptyQueryResponse(): array
    {
        return [
            'message' => 'Silakan ketik pertanyaan Anda tentang Orasi Ilmiah Guru Besar Universitas Mulawarman. Anda dapat menggunakan nama guru besar, fakultas, bidang ilmu, tahun, video, dokumen, atau kata kunci lain yang relevan.',
            'type' => 'empty',
            'suggestions' => $this->defaultSuggestions(),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, type: string, suggestions: array<int, string>}
     */
    private function fallbackResponse(array $context): array
    {
        return [
            'message' => 'Maaf, saya belum menemukan informasi yang sesuai dalam lingkup '.$this->portalLabel().'.<br><br>'
                .'Silakan gunakan kata kunci yang lebih spesifik, misalnya:<br>'
                .'&bull; Profil guru besar: <em>Profil (nama guru besar)</em><br>'
                .'&bull; Judul orasi: <em>Judul orasi (nama guru besar)</em><br>'
                .'&bull; Statistik fakultas: <em>Statistik Farmasi</em><br>'
                .'&bull; Jumlah per fakultas: <em>Jumlah guru besar Fakultas Teknik</em><br>'
                .'&bull; Informasi umum: <em>Statistik per tahun</em>, <em>Daftar video orasi</em>',
            'type' => 'fallback',
            'suggestions' => $this->defaultSuggestions(),
        ];
    }

    private function text(mixed $value, bool $escape = true): string
    {
        $string = (string) ($value ?? '-');

        return $escape ? e($string) : $string;
    }
}
