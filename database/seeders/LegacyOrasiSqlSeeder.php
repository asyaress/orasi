<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LegacyOrasiSqlSeeder extends Seeder
{
    private string $sqlPath;

    /** @var array<string, Fakultas> */
    private array $fakultasCache = [];

    /** @var array<string, Prodi> */
    private array $prodiCache = [];

    public function run(): void
    {
        $this->sqlPath = dirname(base_path()) . DIRECTORY_SEPARATOR . 'orasi-ilmiah.sql';

        if (! is_file($this->sqlPath)) {
            $this->command?->warn("File legacy SQL tidak ditemukan: {$this->sqlPath}");

            return;
        }

        $orasiRows = $this->extractRows('orasi');
        $guruRows = $this->extractRows('guru');

        if ($orasiRows === [] && $guruRows === []) {
            $this->command?->warn('Tidak ada baris legacy yang berhasil diparsing dari dump SQL.');

            return;
        }

        $this->cleanupNonLegacySeedData();

        $orasiMap = $this->seedOrasiIlmiahs($orasiRows);
        $totalGuru = $this->seedGuruBesars($guruRows, $orasiMap);

        $this->command?->info("Legacy SQL selesai diimpor: {$totalGuru} guru besar, " . count($orasiMap) . ' agenda orasi.');
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function extractRows(string $table): array
    {
        $rows = [];
        $handle = fopen($this->sqlPath, 'rb');

        if (! $handle) {
            return $rows;
        }

        while (($line = fgets($handle)) !== false) {
            if (! str_starts_with($line, 'INSERT INTO "public"."'.$table.'"')) {
                continue;
            }

            $parsed = $this->parseInsertLine($line);

            if ($parsed !== null) {
                $rows[] = $parsed;
            }
        }

        fclose($handle);

        return $rows;
    }

    /**
     * @return array<string, string|null>|null
     */
    private function parseInsertLine(string $line): ?array
    {
        $line = trim($line);

        if (! preg_match('/INSERT INTO "public"\."[^"]+"\s+\((?<columns>.+?)\)\s+VALUES\s+\((?<values>.*)\);$/u', $line, $matches)) {
            return null;
        }

        preg_match_all('/"([^"]+)"/', $matches['columns'], $columnMatches);
        $columns = $columnMatches[1] ?? [];
        $values = $this->splitSqlTuple($matches['values']);

        if (count($columns) !== count($values)) {
            return null;
        }

        return array_combine($columns, $values) ?: null;
    }

    /**
     * @return array<int, string|null>
     */
    private function splitSqlTuple(string $tuple): array
    {
        $values = [];
        $buffer = '';
        $inQuote = false;
        $length = strlen($tuple);

        for ($i = 0; $i < $length; $i++) {
            $char = $tuple[$i];
            $next = $i + 1 < $length ? $tuple[$i + 1] : null;

            if ($char === "'") {
                if ($inQuote && $next === "'") {
                    $buffer .= "'";
                    $i++;
                    continue;
                }

                $inQuote = ! $inQuote;
                continue;
            }

            if (! $inQuote && $char === ',') {
                $values[] = $this->normalizeSqlValue($buffer);
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        $values[] = $this->normalizeSqlValue($buffer);

        return $values;
    }

    private function normalizeSqlValue(string $value): ?string
    {
        $value = trim($value);

        if ($value === '' || strtoupper($value) === 'NULL') {
            return null;
        }

        return $value;
    }

    /**
     * @param  array<int, array<string, string|null>>  $rows
     * @return array<int, OrasiIlmiah>
     */
    private function seedOrasiIlmiahs(array $rows): array
    {
        $latestId = collect($rows)
            ->sortByDesc(function (array $row) {
                return sprintf(
                    '%s-%010d',
                    $row['tanggal_pelaksanaan'] ?? '',
                    (int) ($row['id'] ?? 0)
                );
            })
            ->pluck('id')
            ->filter()
            ->first();

        $map = [];

        foreach ($rows as $row) {
            $legacyId = (int) ($row['id'] ?? 0);
            $tahun = $this->extractYear($row['tanggal_pelaksanaan'] ?? null, $row['judul'] ?? null);
            $judul = $this->cleanText($row['judul']) ?: ('Orasi Ilmiah ' . ($tahun ?: 'Universitas Mulawarman'));
            $tanggal = $this->safeDate($row['tanggal_pelaksanaan']);
            $pendaftaranMulai = $this->safeDate($row['pendaftaran_awal']);
            $pendaftaranSelesai = $this->safeDate($row['pendaftaran_akhir']);
            $jenis = Str::contains(Str::lower((string) $row['jenis_pelaksanaan']), 'daring') ? 'Daring' : 'Luring';
            $status = ((string) ($row['id'] ?? '')) === (string) $latestId ? 'published' : 'archived';

            $orasi = OrasiIlmiah::query()->updateOrCreate(
                ['tahun' => $tahun],
                [
                    'judul' => $judul,
                    'tahun' => $tahun,
                    'tanggal_pelaksanaan' => $tanggal,
                    'jenis' => $jenis,
                    'pendaftaran_mulai' => $pendaftaranMulai,
                    'pendaftaran_selesai' => $pendaftaranSelesai,
                    'status' => $status,
                    'created_at' => $this->safeTimestamp($row['created_at']) ?? now(),
                    'updated_at' => $this->safeTimestamp($row['updated_at']) ?? now(),
                ]
            );

            $map[$legacyId] = $orasi;
        }

        return $map;
    }

    /**
     * @param  array<int, array<string, string|null>>  $rows
     * @param  array<int, OrasiIlmiah>  $orasiMap
     */
    private function seedGuruBesars(array $rows, array $orasiMap): int
    {
        $count = 0;

        foreach ($rows as $row) {
            $legacyId = (int) ($row['id'] ?? 0);
            $fakultas = $this->resolveFakultas($row['fakultas']);
            $prodi = $this->resolveProdi($row['prodi'], $fakultas);
            $orasiIlmiah = Arr::get($orasiMap, (int) ($row['orasi_id'] ?? 0));

            GuruBesar::query()->updateOrCreate(
                ['pegawai_id' => 'LEGACY-GURU-'.$legacyId],
                [
                    'orasi_ilmiah_id' => $orasiIlmiah?->id,
                    'sumber' => GuruBesar::SUMBER_MANUAL,
                    'nama' => $this->cleanText($row['nama']),
                    'bidang_ilmu' => $this->cleanText($row['bidang_ilmu']),
                    'judul_orasi' => $this->cleanText($row['judul_orasi'] ?? null),
                    'tmt' => $this->safeDate($row['tmt']),
                    'youtube_url' => $this->normalizeUrl($row['video_orasi']),
                    'file_orasi_path' => null,
                    'ppt_path' => null,
                    'piagam_path' => null,
                    'sertifikat_path' => null,
                    'fakultas_id' => $fakultas?->id,
                    'prodi_id' => $prodi?->id,
                    'fakultas_snapshot' => $fakultas?->nama ?? $this->formatLegacyFacultyName($row['fakultas']),
                    'prodi_snapshot' => $prodi?->nama ?? $this->formatLegacyProdiName($row['prodi']),
                    'created_at' => $this->safeTimestamp($row['created_at']) ?? now(),
                    'updated_at' => $this->safeTimestamp($row['updated_at']) ?? now(),
                ]
            );

            $count++;
        }

        return $count;
    }

    private function cleanupNonLegacySeedData(): void
    {
        GuruBesar::query()->where('pegawai_id', 'like', 'DEMO-%')->delete();

        GuruBesar::query()->whereIn('pegawai_id', [
            '197503152005011001',
            '196812101990031002',
            '197201011998021003',
            '197805202010011004',
            '198003122012011005',
            '197604081999031006',
            '198509152018011007',
            '198712202020011008',
        ])->delete();

        GuruBesar::query()
            ->whereNull('pegawai_id')
            ->where('nama', 'Prof. Dr. M. Yusuf (Input Manual)')
            ->delete();

        OrasiIlmiah::query()->whereNotIn('tahun', [2023, 2024, 2025])->delete();
    }

    private function resolveFakultas(?string $raw): ?Fakultas
    {
        if (! filled($raw)) {
            return null;
        }

        $raw = trim($raw);

        if (isset($this->fakultasCache[$raw])) {
            return $this->fakultasCache[$raw];
        }

        [$kode, $label] = $this->splitLegacyCodeAndLabel($raw);
        $nama = $this->formatLegacyFacultyName($label);
        $slug = Str::slug($nama);

        $fakultas = Fakultas::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'kode' => $kode,
                'nama' => $nama,
                'is_active' => true,
            ]
        );

        return $this->fakultasCache[$raw] = $fakultas;
    }

    private function resolveProdi(?string $raw, ?Fakultas $fakultas): ?Prodi
    {
        if (! filled($raw) || ! $fakultas) {
            return null;
        }

        $cacheKey = $fakultas->id.'|'.trim($raw);

        if (isset($this->prodiCache[$cacheKey])) {
            return $this->prodiCache[$cacheKey];
        }

        $parts = array_values(array_filter(explode('-', trim($raw)), fn ($value) => $value !== ''));
        $kode = $parts[0] ?? null;
        $jenjang = $parts[1] ?? null;
        $label = count($parts) >= 3 ? implode('-', array_slice($parts, 2)) : ($parts[1] ?? $parts[0] ?? trim($raw));
        $nama = $this->formatLegacyProdiName($label);
        $slug = Str::slug(($kode ?: 'legacy').'-'.$fakultas->id.'-'.$nama);

        $prodi = Prodi::query()->updateOrCreate(
            [
                'fakultas_id' => $fakultas->id,
                'nama' => $nama,
            ],
            [
                'kode' => $kode,
                'slug' => $slug,
                'jenjang' => $jenjang,
                'is_active' => true,
            ]
        );

        return $this->prodiCache[$cacheKey] = $prodi;
    }

    /**
     * @return array{0: string|null, 1: string}
     */
    private function splitLegacyCodeAndLabel(string $raw): array
    {
        $parts = array_values(array_filter(explode('-', trim($raw)), fn ($value) => $value !== ''));

        if (count($parts) >= 2 && preg_match('/^\d+$/', $parts[0])) {
            return [$parts[0], implode('-', array_slice($parts, 1))];
        }

        return [null, $raw];
    }

    private function formatLegacyFacultyName(?string $raw): string
    {
        if (! filled($raw)) {
            return 'Fakultas Tidak Diketahui';
        }

        [, $label] = $this->splitLegacyCodeAndLabel($raw);
        $label = $this->cleanText($label);
        $formatted = Str::title(Str::lower($label));

        if (! Str::startsWith(Str::lower($formatted), 'fakultas ')) {
            $formatted = 'Fakultas '.$formatted;
        }

        return $formatted;
    }

    private function formatLegacyProdiName(?string $raw): string
    {
        if (! filled($raw)) {
            return 'Program Studi Tidak Diketahui';
        }

        $parts = array_values(array_filter(explode('-', trim($raw)), fn ($value) => $value !== ''));
        $label = count($parts) >= 3 ? implode('-', array_slice($parts, 2)) : ($parts[1] ?? $parts[0] ?? $raw);

        return Str::title(Str::lower($this->cleanText($label)));
    }

    private function extractYear(?string $date, ?string $judul): ?int
    {
        if ($safeDate = $this->safeDate($date)) {
            return (int) Carbon::parse($safeDate)->format('Y');
        }

        if ($judul && preg_match('/(20\d{2})/', $judul, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function safeDate(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function safeTimestamp(?string $value): ?Carbon
    {
        if (! filled($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeUrl(?string $value): ?string
    {
        $value = $this->cleanText($value);

        if (! filled($value)) {
            return null;
        }

        if (! Str::startsWith(Str::lower($value), ['http://', 'https://'])) {
            return null;
        }

        return $value;
    }

    private function cleanText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        return preg_replace('/\s+/u', ' ', $value) ?: $value;
    }
}
