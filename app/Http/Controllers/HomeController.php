<?php

namespace App\Http\Controllers;

use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Pengumuman;
use App\Services\OrasiChatbotService;
use App\Services\OrasiDocumentMergeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.home', $this->buildPortalData($request, false) + [
            'pageMode' => 'home',
        ]);
    }

    public function chatbotReply(Request $request, OrasiChatbotService $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $result = $chatbot->reply($validated['message']);

        return response()->json($result);
    }

    public function guruBesar(Request $request): View
    {
        $portalData = $this->buildPortalData($request, true);
        $activeOrasiFilter = $portalData['activeOrasiFilter'] ?? null;

        return view('pages.portal-section', $portalData + [
            'pageMode' => 'guru-besar',
            'pageTitle' => 'Guru Besar',
            'pageDescription' => $activeOrasiFilter
                ? 'Direktori guru besar yang terhubung pada agenda orasi ilmiah terpilih di lingkungan Universitas Mulawarman.'
                : 'Direktori guru besar Universitas Mulawarman yang ditampilkan pada portal orasi ilmiah.',
        ]);
    }

    public function guruBesarShow(GuruBesar $guruBesar): View
    {
        $guruBesar->loadMissing(['orasiIlmiah', 'fakultas', 'prodi']);

        abort_unless(
            $guruBesar->orasiIlmiah && in_array($guruBesar->orasiIlmiah->status, ['published', 'archived'], true),
            404
        );

        $relatedByYear = collect();

        if ($guruBesar->archiveYear()) {
            $relatedByYear = GuruBesar::query()
                ->with(['orasiIlmiah', 'fakultas', 'prodi'])
                ->whereHas('orasiIlmiah', fn ($query) => $query->whereIn('status', ['published', 'archived']))
                ->where('id', '!=', $guruBesar->id)
                ->get()
                ->filter(fn (GuruBesar $item) => $item->archiveYear() === $guruBesar->archiveYear());
            $relatedByYear = GuruBesar::sortByTmtAscending($relatedByYear);
        }

        return view('pages.guru-besar-show', [
            'guruBesar' => $guruBesar,
            'relatedByYear' => $relatedByYear,
            'pageTitle' => $guruBesar->nama,
            'pageDescription' => 'Profil guru besar, media orasi, dan dokumen publik Orasi Ilmiah Universitas Mulawarman.',
            'youtubeEmbedUrl' => $this->youtubeEmbedUrl($guruBesar->youtube_url),
            'youtubeThumbnailUrl' => $this->youtubeThumbnailUrl($guruBesar->youtube_url),
            'heroBackground' => asset('foto-gor-27.png'),
        ]);
    }

    public function downloadGuruBesarPackage(GuruBesar $guruBesar): BinaryFileResponse|RedirectResponse
    {
        $guruBesar->loadMissing(['orasiIlmiah', 'fakultas', 'prodi']);

        abort_unless(
            $guruBesar->orasiIlmiah && in_array($guruBesar->orasiIlmiah->status, ['published', 'archived'], true),
            404
        );

        if (! $guruBesar->hasDownloadablePackage()) {
            return redirect()
                ->route('portal.guru-besar.show', $guruBesar)
                ->with('warning', 'Belum ada foto atau dokumen untuk dipaketkan.');
        }

        $rootFolder = $guruBesar->packageFolderName();
        $zipFile = tempnam(sys_get_temp_dir(), 'orasi-gb-');

        if ($zipFile === false) {
            abort(500, 'Tidak dapat menyiapkan file unduhan.');
        }

        $zip = new ZipArchive;

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipFile);
            abort(500, 'Tidak dapat membuat arsip ZIP.');
        }

        $tempFiles = [];
        $this->appendPackageMedia($zip, $guruBesar, $rootFolder, $tempFiles);
        $zip->close();

        app()->terminating(function () use ($tempFiles): void {
            foreach ($tempFiles as $tempFile) {
                if (is_file($tempFile)) {
                    @unlink($tempFile);
                }
            }
        });

        return response()
            ->download($zipFile, $rootFolder.'.zip', ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }

    public function daftarOrasi(Request $request): View
    {
        return $this->renderPortalPage(
            $request,
            'daftar-orasi',
            'Daftar Orasi',
            'Agenda dan arsip pelaksanaan orasi ilmiah guru besar Universitas Mulawarman berdasarkan kegiatan yang telah dipublikasikan.'
        );
    }

    public function videoOrasi(Request $request): View
    {
        return $this->renderPortalPage(
            $request,
            'video-orasi',
            'Video Orasi',
            'Kompilasi dokumentasi video orasi ilmiah guru besar Universitas Mulawarman yang terhubung melalui kanal publikasi resmi.'
        );
    }

    public function dokumenOrasi(Request $request): View
    {
        return $this->renderPortalPage(
            $request,
            'dokumen-orasi',
            'Dokumen Orasi',
            'Koleksi naskah, bahan presentasi, dan dokumen pendukung orasi ilmiah guru besar Universitas Mulawarman.'
        );
    }

    public function downloadMergedDocumentsByYear(string $year, string $type, OrasiDocumentMergeService $mergeService): BinaryFileResponse|RedirectResponse
    {
        try {
            $yearLabel = $mergeService->yearLabelFromSlug($year);
            $kind = $mergeService->documentKindFromSlug($type);
            $merged = $mergeService->mergeYearDocuments($yearLabel, $kind);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('portal.dokumen-orasi')
                ->with('warning', $exception->getMessage());
        }

        $downloadName = 'dokumen-orasi-'.Str::slug($yearLabel).'-gabungan-'.$kind.'.pdf';

        app()->terminating(function () use ($merged): void {
            foreach ($merged['cleanup'] as $tempFile) {
                if (is_string($tempFile) && is_file($tempFile)) {
                    @unlink($tempFile);
                }
            }
        });

        return response()
            ->download($merged['path'], $downloadName, ['Content-Type' => 'application/pdf']);
    }

    public function statistik(Request $request): View
    {
        return $this->renderPortalPage(
            $request,
            'statistik',
            'Statistik',
            'Statistik guru besar dan orasi ilmiah Universitas Mulawarman berdasarkan data yang tersedia pada portal publik.'
        );
    }

    private function renderPortalPage(Request $request, string $pageMode, string $pageTitle, string $pageDescription): View
    {
        return view('pages.portal-section', $this->buildPortalData($request, true) + [
            'pageMode' => $pageMode,
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
        ]);
    }

    /**
     * @param  array<int, string>  $tempFiles
     */
    private function appendPackageMedia(ZipArchive $zip, GuruBesar $guruBesar, string $rootFolder, array &$tempFiles): void
    {
        $files = [
            ['path' => $guruBesar->foto_path, 'name' => 'foto', 'photo_mode' => $guruBesar->fotoDisplayMode()],
            ['path' => $guruBesar->file_orasi_path, 'name' => 'naskah-orasi', 'prefer_jpg' => false],
            ['path' => $guruBesar->ppt_path, 'name' => 'ppt-orasi', 'prefer_jpg' => false],
            ['path' => $guruBesar->piagam_path, 'name' => 'piagam-orasi', 'prefer_jpg' => false],
            ['path' => $guruBesar->sertifikat_path, 'name' => 'sertifikat-orasi', 'prefer_jpg' => false],
        ];

        foreach ($files as $file) {
            if (! filled($file['path']) || ! Storage::disk('public')->exists($file['path'])) {
                continue;
            }

            $absolutePath = Storage::disk('public')->path($file['path']);
            $entry = $this->buildPackageEntry(
                $absolutePath,
                $file['name'],
                $file['photo_mode'] ?? null,
                $file['prefer_jpg'] ?? false,
                $tempFiles
            );

            if ($entry === null) {
                continue;
            }

            [$entryPath, $entryName] = $entry;
            $zip->addFile($entryPath, "{$rootFolder}/{$entryName}");
        }
    }

    /**
     * @param  array<int, string>  $tempFiles
     * @return array{0:string,1:string}|null
     */
    private function buildPackageEntry(string $absolutePath, string $baseName, ?string $photoMode, bool $preferJpg, array &$tempFiles): ?array
    {
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION) ?: 'bin');

        if ($baseName === 'foto' && $photoMode === GuruBesar::FOTO_DISPLAY_MODE_SVG_BG_PHOTO && $preferJpg) {
            $imageData = @file_get_contents($absolutePath);
            $image = $imageData ? @imagecreatefromstring($imageData) : false;

            if ($image !== false) {
                $tempFile = tempnam(sys_get_temp_dir(), 'orasi-gb-img-');

                if ($tempFile === false) {
                    imagedestroy($image);

                    return [$absolutePath, "{$baseName}.{$extension}"];
                }

                $jpgPath = $tempFile.'.jpg';
                @rename($tempFile, $jpgPath);
                if (imagejpeg($image, $jpgPath, 92) !== true) {
                    @unlink($jpgPath);
                    imagedestroy($image);

                    return [$absolutePath, "{$baseName}.{$extension}"];
                }
                imagedestroy($image);
                $tempFiles[] = $jpgPath;

                return [$jpgPath, "{$baseName}.jpg"];
            }
        }

        return [$absolutePath, "{$baseName}.{$extension}"];
    }

    private function buildPortalData(Request $request, bool $fullLists = false): array
    {
        $publicStatuses = ['published', 'archived'];
        $heroYoutubeUrl = 'https://youtu.be/S_9XNYv6RZo?si=wLjkc8MsCtvqSR-G';
        $selectedOrasiId = $request->query('orasi');
        $activeOrasiFilter = null;

        if (filled($selectedOrasiId) && is_numeric($selectedOrasiId)) {
            $activeOrasiFilter = OrasiIlmiah::query()
                ->whereIn('status', $publicStatuses)
                ->find((int) $selectedOrasiId);
        }

        $featuredGuru = GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas', 'prodi'])
            ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
            ->whereIn('orasi_ilmiahs.status', $publicStatuses)
            ->whereNotNull('guru_besars.youtube_url')
            ->where('guru_besars.youtube_url', '!=', '')
            ->orderByRaw("case when orasi_ilmiahs.status = 'published' then 0 else 1 end")
            ->orderByDesc('orasi_ilmiahs.tahun')
            ->orderByDesc('orasi_ilmiahs.tanggal_pelaksanaan')
            ->select('guru_besars.*')
            ->first();

        $featuredOrasi = $featuredGuru?->orasiIlmiah
            ?? OrasiIlmiah::query()
                ->with(['guruBesars.fakultas', 'guruBesars.prodi'])
                ->whereIn('status', $publicStatuses)
                ->orderByRaw("case when status = 'published' then 0 else 1 end")
                ->orderByDesc('tahun')
                ->orderByDesc('tanggal_pelaksanaan')
                ->first()
            ?? OrasiIlmiah::query()
                ->with(['guruBesars.fakultas', 'guruBesars.prodi'])
                ->orderByDesc('tahun')
                ->orderByDesc('tanggal_pelaksanaan')
                ->first();

        if (! $featuredGuru && $featuredOrasi) {
            $featuredGuru = $featuredOrasi->guruBesars
                ->sortByDesc(fn (GuruBesar $guru) => filled($guru->youtube_url))
                ->sortByDesc(fn (GuruBesar $guru) => filled($guru->foto_path))
                ->first();
        }

        $latestOrators = GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas', 'prodi'])
            ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
            ->whereIn('orasi_ilmiahs.status', $publicStatuses)
            ->when(
                $activeOrasiFilter,
                fn ($query) => $query->where('guru_besars.orasi_ilmiah_id', $activeOrasiFilter->id)
            )
            ->select('guru_besars.*')
            ->orderByDesc('orasi_ilmiahs.tahun')
            ->orderByTmtAscending()
            ->get();

        $latestVideos = GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas'])
            ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
            ->whereIn('orasi_ilmiahs.status', $publicStatuses)
            ->whereNotNull('guru_besars.youtube_url')
            ->where('guru_besars.youtube_url', '!=', '')
            ->select('guru_besars.*')
            ->orderByDesc('orasi_ilmiahs.tahun')
            ->orderByTmtAscending()
            ->get();

        $latestVideos->each(function (GuruBesar $video) {
            $video->youtube_thumbnail_url = $this->youtubeThumbnailUrl($video->youtube_url);
        });

        $orasiHighlights = OrasiIlmiah::query()
            ->with([
                'guruBesars' => function ($query) {
                    $query->orderByTmtAscending();
                },
            ])
            ->withCount('guruBesars')
            ->whereIn('status', $publicStatuses)
            ->orderByRaw("case when status = 'published' then 0 else 1 end")
            ->orderByDesc('tahun')
            ->orderByDesc('tanggal_pelaksanaan')
            ->get();

        if (! $fullLists) {
            $orasiHighlights = $orasiHighlights->take(4)->values();
        }

        $documentItems = GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas'])
            ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
            ->whereIn('orasi_ilmiahs.status', $publicStatuses)
            ->where(function ($query) {
                $query->whereNotNull('guru_besars.file_orasi_path')
                    ->orWhereNotNull('guru_besars.ppt_path')
                    ->orWhereNotNull('guru_besars.piagam_path')
                    ->orWhereNotNull('guru_besars.sertifikat_path');
            })
            ->select('guru_besars.*')
            ->orderByDesc('orasi_ilmiahs.tahun')
            ->orderByTmtAscending()
            ->get();

        $archiveYears = OrasiIlmiah::query()
            ->whereIn('status', $publicStatuses)
            ->whereNotNull('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn ($year) => (int) $year)
            ->values();

        $selectedYear = null;
        $requestedYear = $request->query('year');

        if (filled($requestedYear) && is_numeric($requestedYear)) {
            $requestedYear = (int) $requestedYear;

            if ($archiveYears->contains($requestedYear)) {
                $selectedYear = $requestedYear;
            }
        }

        $statsScopeLabel = $selectedYear ? "Tahun {$selectedYear}" : 'Data Aktif';
        $statsPerYear = OrasiIlmiah::query()
            ->join('guru_besars', 'guru_besars.orasi_ilmiah_id', '=', 'orasi_ilmiahs.id')
            ->whereIn('status', $publicStatuses)
            ->whereNotNull('tahun')
            ->selectRaw('orasi_ilmiahs.tahun as tahun, COUNT(guru_besars.id) as total_guru_besar, COUNT(DISTINCT orasi_ilmiahs.id) as total_orasi')
            ->groupBy('orasi_ilmiahs.tahun')
            ->orderBy('tahun')
            ->get();

        $scopeYears = collect([null])->merge($archiveYears->all());
        $statsScopes = [];
        $facultyScopeMap = [];
        $genderScopeMap = [];
        $yearBreakdownScopes = [];
        $peakArchiveYear = $statsPerYear->sortByDesc('total_guru_besar')->first();

        foreach ($scopeYears as $scopeYear) {
            $scopeKey = $scopeYear ? (string) $scopeYear : 'all';
            $scopeLabel = $scopeYear ? "Tahun {$scopeYear}" : 'Data Aktif';

            $publicOrasiQuery = OrasiIlmiah::query()
                ->whereIn('status', $publicStatuses);

            $publicGuruQuery = GuruBesar::query()
                ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
                ->whereIn('orasi_ilmiahs.status', $publicStatuses);

            if ($scopeYear) {
                $publicOrasiQuery->where('tahun', $scopeYear);
                $publicGuruQuery->where('orasi_ilmiahs.tahun', $scopeYear);
            }

            $scopeStats = [
                'guru_besar' => (clone $publicGuruQuery)->count(),
                'orasi_ilmiah' => (clone $publicOrasiQuery)->count(),
                'video_orasi' => (clone $publicGuruQuery)->whereNotNull('youtube_url')->where('youtube_url', '!=', '')->count(),
                'dokumen_orasi' => (clone $publicGuruQuery)
                    ->where(function ($query) {
                        $query->whereNotNull('file_orasi_path')
                            ->orWhereNotNull('ppt_path')
                            ->orWhereNotNull('piagam_path')
                            ->orWhereNotNull('sertifikat_path');
                    })
                    ->count(),
                'fakultas_terlibat' => (clone $publicGuruQuery)
                    ->whereNotNull('orasi_ilmiah_id')
                    ->whereNotNull('fakultas_id')
                    ->distinct('fakultas_id')
                    ->count('fakultas_id'),
            ];

            $scopeFaculty = (clone $publicGuruQuery)
                ->leftJoin('fakultas', 'fakultas.id', '=', 'guru_besars.fakultas_id')
                ->selectRaw("COALESCE(fakultas.nama, guru_besars.fakultas_snapshot, 'Tanpa Fakultas') as label, COUNT(*) as total")
                ->groupBy(DB::raw("COALESCE(fakultas.nama, guru_besars.fakultas_snapshot, 'Tanpa Fakultas')"))
                ->orderByDesc('total')
                ->get();

            $scopeGender = collect();

            if (Schema::hasColumn('guru_besars', 'jenis_kelamin')) {
                $scopeGender = (clone $publicGuruQuery)
                    ->selectRaw("
                        CASE
                            WHEN LOWER(COALESCE(jenis_kelamin, '')) IN ('l', 'laki-laki', 'laki laki', 'male') THEN 'Laki-laki'
                            WHEN LOWER(COALESCE(jenis_kelamin, '')) IN ('p', 'perempuan', 'female') THEN 'Perempuan'
                            ELSE 'Belum diisi'
                        END as label,
                        COUNT(*) as total
                    ")
                    ->groupBy('label')
                    ->orderByDesc('total')
                    ->get();
            }

            if ($scopeGender->isEmpty()) {
                $scopeGender = collect([
                    (object) ['label' => 'Belum diisi', 'total' => $scopeStats['guru_besar']],
                ]);
            }

            $topFaculty = $scopeFaculty->first();
            $videoCoverage = $scopeStats['guru_besar'] > 0
                ? (int) round(($scopeStats['video_orasi'] / $scopeStats['guru_besar']) * 100)
                : 0;
            $documentCoverage = $scopeStats['guru_besar'] > 0
                ? (int) round(($scopeStats['dokumen_orasi'] / $scopeStats['guru_besar']) * 100)
                : 0;
            $avgPerFaculty = $scopeStats['fakultas_terlibat'] > 0
                ? round($scopeStats['guru_besar'] / $scopeStats['fakultas_terlibat'], 1)
                : 0;

            $statsScopes[$scopeKey] = $scopeStats + [
                'scope_label' => $scopeLabel,
                'tahun_arsip' => $scopeYear ? 1 : $archiveYears->count(),
                'top_faculty_label' => $topFaculty?->label ?? 'Belum ada data',
                'top_faculty_total' => (int) ($topFaculty?->total ?? 0),
                'video_coverage' => $videoCoverage,
                'document_coverage' => $documentCoverage,
                'avg_per_faculty' => $avgPerFaculty,
                'peak_year_label' => $scopeYear ? (string) $scopeYear : (string) ($peakArchiveYear?->tahun ?? '-'),
                'peak_year_total' => $scopeYear ? $scopeStats['guru_besar'] : (int) ($peakArchiveYear?->total_guru_besar ?? 0),
            ];

            $facultyScopeMap[$scopeKey] = [
                'labels' => $scopeFaculty->pluck('label')->values()->all(),
                'data' => $scopeFaculty->pluck('total')->map(fn ($value) => (int) $value)->values()->all(),
                'count' => $scopeStats['fakultas_terlibat'],
            ];

            $genderScopeMap[$scopeKey] = [
                'labels' => $scopeGender->pluck('label')->values()->all(),
                'data' => $scopeGender->pluck('total')->map(fn ($value) => (int) $value)->values()->all(),
            ];

            $yearBreakdownScopes[$scopeKey] = ($scopeYear
                ? $statsPerYear->where('tahun', (int) $scopeYear)->values()
                : $statsPerYear->values()
            )->map(fn ($row) => [
                'tahun' => (int) $row->tahun,
                'total_guru_besar' => (int) $row->total_guru_besar,
                'total_orasi' => (int) $row->total_orasi,
            ])->all();
        }

        $stats = $statsScopes[$selectedYear ? (string) $selectedYear : 'all'];
        $statsPerFakultas = collect($facultyScopeMap[$selectedYear ? (string) $selectedYear : 'all']['labels'] ?? [])
            ->map(function ($label, $index) use ($facultyScopeMap, $selectedYear) {
                $key = $selectedYear ? (string) $selectedYear : 'all';

                return (object) [
                    'label' => $label,
                    'total' => $facultyScopeMap[$key]['data'][$index] ?? 0,
                ];
            });

        $statsByGender = collect($genderScopeMap[$selectedYear ? (string) $selectedYear : 'all']['labels'] ?? [])
            ->map(function ($label, $index) use ($genderScopeMap, $selectedYear) {
                $key = $selectedYear ? (string) $selectedYear : 'all';

                return (object) [
                    'label' => $label,
                    'total' => $genderScopeMap[$key]['data'][$index] ?? 0,
                ];
            });

        $yearBreakdownScopeKey = $selectedYear ? (string) $selectedYear : 'all';
        $yearBreakdownActive = collect($yearBreakdownScopes[$yearBreakdownScopeKey] ?? []);

        $activeYearChartData = [
            'labels' => $yearBreakdownActive->pluck('tahun')->map(fn ($year) => (string) $year)->all(),
            'data' => $yearBreakdownActive->pluck('total_guru_besar')->map(fn ($value) => (int) $value)->all(),
        ];

        $activeVideoYearChartData = $latestVideos
            ->groupBy(fn (GuruBesar $video) => (string) ($video->archiveYear() ?: 'Tanpa Tahun'))
            ->sortKeysDesc()
            ->map(function ($videos, $year) {
                return [
                    'label' => (string) $year,
                    'total' => $videos->count(),
                ];
            })
            ->values();

        $activeDocumentYearChartData = $documentItems
            ->groupBy(fn (GuruBesar $item) => (string) ($item->archiveYear() ?: 'Tanpa Tahun'))
            ->sortKeysDesc()
            ->map(function ($items, $year) {
                return [
                    'label' => (string) $year,
                    'total' => $items->count(),
                ];
            })
            ->values();

        $activeStatSummary = [
            'total_guru_besar' => (int) $stats['guru_besar'],
            'total_orasi' => (int) $stats['orasi_ilmiah'],
            'total_video_orasi' => (int) $stats['video_orasi'],
            'total_dokumen_orasi' => (int) $stats['dokumen_orasi'],
            'total_fakultas' => (int) $stats['fakultas_terlibat'],
            'period_label' => $statsScopeLabel,
            'top_year_label' => (string) ($statsPerYear->sortByDesc('total_guru_besar')->first()?->tahun ?? '-'),
            'top_year_total' => (int) ($statsPerYear->sortByDesc('total_guru_besar')->first()?->total_guru_besar ?? 0),
            'video_year_count' => (int) $activeVideoYearChartData->count(),
        ];

        $excelInsights = $fullLists
            ? $this->loadExcelAchievementInsights()
            : $this->emptyExcelAchievementInsights();

        $activeOrasiFilterStats = null;

        $latestPengumuman = Pengumuman::query()
            ->published()
            ->publicOrder()
            ->first();

        if ($activeOrasiFilter) {
            $activeOrasiFilterStats = [
                'guru_besar' => $latestOrators->count(),
                'fakultas_terlibat' => $latestOrators
                    ->map(fn (GuruBesar $guru) => $guru->displayFakultas())
                    ->filter(fn (string $label) => $label !== '-')
                    ->unique()
                    ->count(),
                'tahun' => $activeOrasiFilter->tahun,
            ];
        }

        return [
            'featuredGuru' => $featuredGuru,
            'featuredOrasi' => $featuredOrasi,
            'latestPengumuman' => $latestPengumuman,
            'heroYoutubeEmbedUrl' => $this->youtubeEmbedUrl($heroYoutubeUrl, true),
            'heroVideoSource' => $this->resolveHeroVideoSource(),
            'heroBackground' => $this->resolveHeroBackground($featuredOrasi, $featuredGuru),
            'latestOrators' => $latestOrators,
            'latestVideos' => $latestVideos,
            'orasiHighlights' => $orasiHighlights,
            'documentItems' => $documentItems,
            'archiveYears' => $archiveYears,
            'selectedYear' => $selectedYear,
            'statsScopeLabel' => $statsScopeLabel,
            'stats' => $stats,
            'statsPerFakultas' => $statsPerFakultas,
            'statsPerYear' => $statsPerYear,
            'statsByGender' => $statsByGender,
            'statsScopes' => $statsScopes,
            'facultyScopeMap' => $facultyScopeMap,
            'genderScopeMap' => $genderScopeMap,
            'yearBreakdownScopes' => $yearBreakdownScopes,
            'activeFacultyChartData' => [
                'labels' => $statsPerFakultas->pluck('label')->all(),
                'data' => $statsPerFakultas->pluck('total')->all(),
            ],
            'activeGenderChartData' => [
                'labels' => $statsByGender->pluck('label')->all(),
                'data' => $statsByGender->pluck('total')->map(fn ($value) => (int) $value)->all(),
            ],
            'activeYearChartData' => $activeYearChartData,
            'activeVideoYearChartData' => $activeVideoYearChartData,
            'activeDocumentYearChartData' => $activeDocumentYearChartData,
            'activeStatSummary' => $activeStatSummary,
            'excelAchievementData' => $excelInsights['achievementData'],
            'excelStatSummary' => $excelInsights['statSummary'],
            'activeOrasiFilter' => $activeOrasiFilter,
            'activeOrasiFilterStats' => $activeOrasiFilterStats,
        ];
    }

    private function emptyExcelAchievementInsights(): array
    {
        return [
            'achievementData' => [
                'faculties' => [],
                'faculty_labels' => [],
                'series' => [],
            ],
            'statSummary' => [
                'period_label' => 'SK 2019-2025',
                'year_count' => 0,
                'total_achievements' => 0,
                'total_faculties' => 0,
                'active_achievement_faculties' => 0,
                'top_year_label' => '-',
                'top_year_total' => 0,
                'top_achievement_faculty_label' => '-',
                'top_achievement_faculty_total' => 0,
            ],
        ];
    }

    private function loadExcelAchievementInsights(): array
    {
        $facultyReference = [
            'FEB' => 'Fakultas Ekonomi dan Bisnis',
            'FISIPOL' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
            'FAPERTA' => 'Fakultas Pertanian',
            'FAHUTAN' => 'Fakultas Kehutanan',
            'FKIP' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'FPIK' => 'Fakultas Perikanan dan Ilmu Kelautan',
            'FMIPA' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'FH' => 'Fakultas Hukum',
            'FT' => 'Fakultas Teknik',
            'FK' => 'Fakultas Kedokteran',
            'FKM' => 'Fakultas Kesehatan Masyarakat',
            'FF' => 'Fakultas Farmasi',
            'FIB' => 'Fakultas Ilmu Budaya',
            'FKG' => 'Fakultas Kedokteran Gigi',
        ];

        $fallbackRows = collect([
            ['code' => 'FEB', 'label' => $facultyReference['FEB'], 'years' => [0, 0, 0, 0, 3, 1, 0]],
            ['code' => 'FISIPOL', 'label' => $facultyReference['FISIPOL'], 'years' => [0, 0, 0, 0, 2, 0, 0]],
            ['code' => 'FAPERTA', 'label' => $facultyReference['FAPERTA'], 'years' => [0, 1, 1, 2, 4, 3, 1]],
            ['code' => 'FAHUTAN', 'label' => $facultyReference['FAHUTAN'], 'years' => [1, 1, 1, 0, 0, 0, 0]],
            ['code' => 'FKIP', 'label' => $facultyReference['FKIP'], 'years' => [0, 2, 0, 3, 6, 1, 2]],
            ['code' => 'FPIK', 'label' => $facultyReference['FPIK'], 'years' => [0, 0, 0, 0, 1, 1, 0]],
            ['code' => 'FMIPA', 'label' => $facultyReference['FMIPA'], 'years' => [0, 0, 0, 3, 1, 3, 3]],
            ['code' => 'FH', 'label' => $facultyReference['FH'], 'years' => [0, 0, 0, 0, 1, 0, 0]],
            ['code' => 'FT', 'label' => $facultyReference['FT'], 'years' => [0, 0, 1, 0, 4, 1, 1]],
            ['code' => 'FK', 'label' => $facultyReference['FK'], 'years' => [0, 0, 0, 0, 0, 0, 1]],
            ['code' => 'FKM', 'label' => $facultyReference['FKM'], 'years' => [0, 0, 0, 1, 0, 0, 0]],
            ['code' => 'FF', 'label' => $facultyReference['FF'], 'years' => [1, 0, 0, 0, 0, 0, 0]],
            ['code' => 'FIB', 'label' => $facultyReference['FIB'], 'years' => [0, 0, 0, 0, 0, 0, 0]],
        ])->map(fn (array $row) => $row + ['total' => array_sum($row['years'])]);
        $fallbackYears = collect([
            ['label' => '2019', 'total' => 2],
            ['label' => '2020', 'total' => 4],
            ['label' => '2021', 'total' => 3],
            ['label' => '2022', 'total' => 9],
            ['label' => '2023', 'total' => 22],
            ['label' => '2024', 'total' => 10],
            ['label' => '2025', 'total' => 8],
        ]);

        try {
            $excelPath = dirname(base_path()).DIRECTORY_SEPARATOR.'GB 2021-2025.xlsx';
            $rows = $this->readExcelSheetRows($excelPath, 'REKAP');

            if ($rows !== []) {
                $headerRow = $rows[4] ?? [];
                $yearColumns = collect(['C', 'D', 'E', 'F', 'G', 'H', 'I'])
                    ->mapWithKeys(function (string $column) use ($headerRow) {
                        $value = trim((string) ($headerRow[$column] ?? ''));

                        if ($value === '') {
                            return [];
                        }

                        return [$column => (string) (int) (float) $value];
                    });

                if ($yearColumns->isNotEmpty()) {
                    $parsedRows = collect();
                    $yearTotals = collect();

                    foreach ($rows as $row) {
                        $markerA = Str::upper(trim((string) ($row['A'] ?? '')));
                        $markerB = Str::upper(trim((string) ($row['B'] ?? '')));

                        if ($markerA === 'JUMLAH' || $markerB === 'JUMLAH') {
                            $yearTotals = $yearColumns->map(function (string $yearLabel, string $column) use ($row) {
                                return [
                                    'label' => $yearLabel,
                                    'total' => (int) round((float) ($row[$column] ?? 0)),
                                ];
                            })->values();
                            break;
                        }

                        if ($markerB === '' || $markerB === 'FAKULTAS') {
                            continue;
                        }

                        $years = $yearColumns->map(function (string $yearLabel, string $column) use ($row) {
                            return (int) round((float) ($row[$column] ?? 0));
                        })->values()->all();

                        $parsedRows->push([
                            'code' => $markerB,
                            'label' => $facultyReference[$markerB] ?? $markerB,
                            'years' => $years,
                            'total' => array_sum($years),
                        ]);
                    }

                    if ($parsedRows->isNotEmpty()) {
                        $fallbackRows = $parsedRows;
                    }

                    if ($yearTotals->isNotEmpty()) {
                        $fallbackYears = $yearTotals;
                    } elseif ($parsedRows->isNotEmpty()) {
                        $fallbackYears = $yearColumns->values()->map(function (string $yearLabel, int $index) use ($parsedRows) {
                            return [
                                'label' => $yearLabel,
                                'total' => (int) $parsedRows->sum(fn (array $row) => $row['years'][$index] ?? 0),
                            ];
                        });
                    }
                }
            }
        } catch (\Throwable) {
            // Keep fallback values when workbook parsing is unavailable.
        }

        $colors = ['#f6b234', '#2f8cf6', '#8b5cf6', '#10b981', '#f97316', '#ef4444', '#06b6d4'];
        $topYear = $fallbackYears->sortByDesc('total')->first();
        $topFaculty = $fallbackRows->sortByDesc('total')->first();

        return [
            'achievementData' => [
                'faculties' => $fallbackRows->pluck('code')->all(),
                'faculty_labels' => $fallbackRows->pluck('label')->all(),
                'series' => $fallbackYears->values()->map(function (array $row, int $index) use ($fallbackRows, $colors) {
                    return [
                        'label' => $row['label'],
                        'color' => $colors[$index % count($colors)],
                        'data' => $fallbackRows->map(fn (array $facultyRow) => $facultyRow['years'][$index] ?? 0)->all(),
                    ];
                })->all(),
            ],
            'statSummary' => [
                'period_label' => 'SK 2019-2025',
                'year_count' => (int) $fallbackYears->count(),
                'total_achievements' => (int) $fallbackYears->sum('total'),
                'total_faculties' => (int) $fallbackRows->count(),
                'active_achievement_faculties' => (int) $fallbackRows->filter(fn (array $row) => $row['total'] > 0)->count(),
                'top_year_label' => (string) ($topYear['label'] ?? '2023'),
                'top_year_total' => (int) ($topYear['total'] ?? 0),
                'top_achievement_faculty_label' => (string) ($topFaculty['code'] ?? 'FKIP'),
                'top_achievement_faculty_name' => (string) ($topFaculty['label'] ?? 'Fakultas Keguruan dan Ilmu Pendidikan'),
                'top_achievement_faculty_total' => (int) ($topFaculty['total'] ?? 0),
            ],
        ];
    }

    private function readExcelSheetRows(string $path, string $sheetName): array
    {
        if (! is_file($path)) {
            return [];
        }

        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            return [];
        }

        try {
            $workbookXml = simplexml_load_string((string) $zip->getFromName('xl/workbook.xml'));
            $relsXml = simplexml_load_string((string) $zip->getFromName('xl/_rels/workbook.xml.rels'));

            if (! $workbookXml || ! $relsXml) {
                return [];
            }

            $sharedStrings = $this->readExcelSharedStrings($zip);
            $sheetPath = $this->resolveExcelSheetPath($workbookXml, $relsXml, $sheetName);

            if (! $sheetPath) {
                return [];
            }

            $sheetXml = simplexml_load_string((string) $zip->getFromName($sheetPath));

            if (! $sheetXml) {
                return [];
            }

            $sheetXml->registerXPathNamespace('main', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $rowNodes = $sheetXml->xpath('//main:sheetData/main:row') ?: [];
            $rows = [];

            foreach ($rowNodes as $rowNode) {
                $rowIndex = (int) ($rowNode['r'] ?? 0);
                $cells = [];

                foreach ($rowNode->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main') as $cellNode) {
                    if ($cellNode->getName() !== 'c') {
                        continue;
                    }

                    $reference = (string) ($cellNode['r'] ?? '');

                    if (! preg_match('/^[A-Z]+/', $reference, $matches)) {
                        continue;
                    }

                    $column = $matches[0];
                    $type = (string) ($cellNode['t'] ?? '');
                    $cells[$column] = $this->readExcelCellValue($cellNode, $type, $sharedStrings);
                }

                $rows[$rowIndex] = $cells;
            }

            return $rows;
        } finally {
            $zip->close();
        }
    }

    private function readExcelSharedStrings(ZipArchive $zip): array
    {
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedXml === false) {
            return [];
        }

        $xml = simplexml_load_string($sharedXml);

        if (! $xml) {
            return [];
        }

        $xml->registerXPathNamespace('main', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        return collect($xml->xpath('//main:si') ?: [])
            ->map(function ($item) {
                $item->registerXPathNamespace('main', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                $texts = $item->xpath('.//main:t') ?: [];

                return collect($texts)
                    ->map(fn ($textNode) => (string) $textNode)
                    ->implode('');
            })
            ->all();
    }

    private function resolveExcelSheetPath(\SimpleXMLElement $workbookXml, \SimpleXMLElement $relsXml, string $sheetName): ?string
    {
        $workbookXml->registerXPathNamespace('main', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $workbookXml->registerXPathNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $relsXml->registerXPathNamespace('rel', 'http://schemas.openxmlformats.org/package/2006/relationships');

        $sheetNodes = $workbookXml->xpath('//main:sheets/main:sheet') ?: [];
        $sheetRelationId = null;

        foreach ($sheetNodes as $sheetNode) {
            $attributes = $sheetNode->attributes();
            $relationAttributes = $sheetNode->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');

            if ((string) ($attributes['name'] ?? '') === $sheetName) {
                $sheetRelationId = (string) ($relationAttributes['id'] ?? '');
                break;
            }
        }

        if (! $sheetRelationId) {
            return null;
        }

        $relationNodes = $relsXml->xpath('//rel:Relationship') ?: [];

        foreach ($relationNodes as $relationNode) {
            $attributes = $relationNode->attributes();

            if ((string) ($attributes['Id'] ?? '') !== $sheetRelationId) {
                continue;
            }

            $target = ltrim((string) ($attributes['Target'] ?? ''), '/');

            if ($target === '') {
                return null;
            }

            return Str::startsWith($target, 'xl/') ? $target : 'xl/'.$target;
        }

        return null;
    }

    private function readExcelCellValue(\SimpleXMLElement $cellNode, string $type, array $sharedStrings): string
    {
        if ($type === 'inlineStr') {
            $cellNode->registerXPathNamespace('main', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

            return collect($cellNode->xpath('.//main:t') ?: [])
                ->map(fn ($textNode) => (string) $textNode)
                ->implode('');
        }

        $value = isset($cellNode->v) ? (string) $cellNode->v : '';

        if ($type === 's') {
            return (string) ($sharedStrings[(int) $value] ?? '');
        }

        return $value;
    }

    private function resolveHeroBackground(?OrasiIlmiah $orasi, ?GuruBesar $guru): string
    {
        if ($orasi?->banner_path) {
            return asset('storage/'.$orasi->banner_path);
        }

        if ($guru?->foto_path) {
            return asset('storage/'.$guru->foto_path);
        }

        return asset('images/background/1.webp');
    }

    private function resolveHeroVideoSource(): string
    {
        $publicPath = public_path('videos/orasi-hero.webm');
        $storagePath = storage_path('app/public/videos/orasi-hero.webm');

        if (file_exists($publicPath)) {
            return asset('videos/orasi-hero.webm');
        }

        if (file_exists($storagePath)) {
            return asset('storage/videos/orasi-hero.webm');
        }

        return asset('videos/orasi-hero.webm');
    }

    private function youtubeEmbedUrl(?string $url, bool $background = false): ?string
    {
        if (! filled($url)) {
            return null;
        }

        $parsed = parse_url($url);
        $host = $parsed['host'] ?? '';
        $path = trim($parsed['path'] ?? '', '/');
        $videoId = null;

        if (str_contains($host, 'youtu.be')) {
            $videoId = $path;
        }

        if (! $videoId && str_contains($host, 'youtube.com')) {
            parse_str($parsed['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;

            if (! $videoId && str_starts_with($path, 'embed/')) {
                $videoId = Str::after($path, 'embed/');
            }
        }

        if (! $videoId) {
            return null;
        }

        if ($background) {
            $origin = urlencode(url('/'));

            return "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&controls=0&loop=1&playlist={$videoId}&playsinline=1&rel=0&modestbranding=1&showinfo=0&iv_load_policy=3&disablekb=1&fs=0&enablejsapi=1&origin={$origin}";
        }

        return "https://www.youtube.com/embed/{$videoId}?rel=0";
    }

    private function youtubeThumbnailUrl(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        $parsed = parse_url($url);
        $host = $parsed['host'] ?? '';
        $path = trim($parsed['path'] ?? '', '/');
        $videoId = null;

        if (str_contains($host, 'youtu.be')) {
            $videoId = $path;
        }

        if (! $videoId && str_contains($host, 'youtube.com')) {
            parse_str($parsed['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;

            if (! $videoId && str_starts_with($path, 'embed/')) {
                $videoId = Str::after($path, 'embed/');
            }
        }

        if (! $videoId) {
            return null;
        }

        return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
    }
}
