<?php

namespace Database\Seeders;

use App\Models\GuruBesar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GuruBesarMediaSeeder extends Seeder
{
    public function run(): void
    {
        $sourceRoots = array_values(array_filter([
            dirname(base_path()) . DIRECTORY_SEPARATOR . 'GuruBesar',
            dirname(base_path()) . DIRECTORY_SEPARATOR . 'guru-besar',
        ], 'is_dir'));

        if ($sourceRoots === []) {
            $this->command?->warn('Folder sumber media guru besar tidak ditemukan.');

            return;
        }

        $gurus = GuruBesar::query()
            ->with('orasiIlmiah')
            ->get();

        $exactMap = [];
        $fuzzyMap = [];

        foreach ($gurus as $guru) {
            $exactKey = Str::slug($guru->nama);
            $fuzzyKey = $this->matchKey($guru->nama);

            $exactMap[$exactKey] = $guru;

            if (! isset($fuzzyMap[$fuzzyKey])) {
                $fuzzyMap[$fuzzyKey] = [];
            }

            $fuzzyMap[$fuzzyKey][] = $guru;
        }

        $folders = collect($sourceRoots)
            ->flatMap(fn (string $root) => collect(File::directories($root))->map(fn (string $folder) => [
                'root' => $root,
                'folder' => $folder,
            ]))
            ->sortBy(fn (array $item) => $item['folder'])
            ->values();
        $updated = 0;
        $missing = 0;

        foreach ($folders as $source) {
            $root = $source['root'];
            $folder = $source['folder'];
            $slug = basename($folder);
            $guru = $exactMap[$slug] ?? null;

            if (! $guru) {
                $fuzzyCandidates = $fuzzyMap[$this->matchKey($slug)] ?? [];

                if (count($fuzzyCandidates) === 1) {
                    $guru = $fuzzyCandidates[0];
                }
            }

            if (! $guru) {
                $missing++;
                $this->command?->warn("Tidak ada record guru besar yang cocok untuk folder: {$slug}");

                continue;
            }

            $targetFolder = $guru->storageFolderPath();
            File::ensureDirectoryExists(storage_path('app/public/' . $targetFolder));

            $updates = [];
            $mediaFiles = $this->resolveMediaFiles($folder, $root);

            if ($photoSource = $mediaFiles['foto'] ?? null) {
                $updates['foto_path'] = $this->copyToStorage($photoSource, $targetFolder, 'foto');
                $updates['foto_display_mode'] = GuruBesar::FOTO_DISPLAY_MODE_PNG_FULL_OVERLAY;
            }

            if ($naskahSource = $mediaFiles['file_orasi'] ?? null) {
                $updates['file_orasi_path'] = $this->copyToStorage($naskahSource, $targetFolder, 'naskah-orasi');
            }

            if ($pptSource = $mediaFiles['ppt'] ?? null) {
                $updates['ppt_path'] = $this->copyToStorage($pptSource, $targetFolder, 'ppt-orasi');
            }

            $piagamSource = $mediaFiles['piagam'] ?? null;
            if ($piagamSource) {
                $updates['piagam_path'] = $this->copyToStorage($piagamSource, $targetFolder, 'piagam-orasi');
            }

            $sertifikatSource = $mediaFiles['sertifikat'] ?? null;
            if ($sertifikatSource) {
                $updates['sertifikat_path'] = $this->copyToStorage($sertifikatSource, $targetFolder, 'sertifikat-orasi');
            } elseif ($piagamSource) {
                $updates['sertifikat_path'] = $this->copyToStorage($piagamSource, $targetFolder, 'sertifikat-orasi');
            }

            if ($updates !== []) {
                $guru->forceFill($updates)->save();
                $updated++;
            }
        }

        $this->command?->info("Sinkron media guru besar selesai: {$updated} folder diperbarui, {$missing} folder tidak cocok.");
    }

    /**
     * @return array{foto?: string, file_orasi?: string, ppt?: string, piagam?: string, sertifikat?: string}
     */
    private function resolveMediaFiles(string $folder, string $root): array
    {
        $files = [];

        if ($photoSource = $this->firstMatchingFile($folder, ['foto_guru*.*'])) {
            $files['foto'] = $photoSource;
        }

        if ($naskahSource = $this->firstMatchingFile($folder, ['ilmiah_guru*.*'])) {
            $files['file_orasi'] = $naskahSource;
        }

        if ($pptSource = $this->firstMatchingFile($folder, ['ppt_guru*.*'])) {
            $files['ppt'] = $pptSource;
        }

        if ($piagamSource = $this->firstMatchingFile($folder, ['piagam_guru*.*'])) {
            $files['piagam'] = $piagamSource;
        }

        if ($sertifikatSource = $this->firstMatchingFile($folder, ['sertifikat_guru*.*'])) {
            $files['sertifikat'] = $sertifikatSource;
        }

        if ($files !== [] || ! str_ends_with(Str::lower($root), DIRECTORY_SEPARATOR . 'guru-besar')) {
            return $files;
        }

        return $this->resolveLegacyMediaFiles($folder);
    }

    /**
     * @return array{foto?: string, file_orasi?: string, ppt?: string, piagam?: string, sertifikat?: string}
     */
    private function resolveLegacyMediaFiles(string $folder): array
    {
        $files = [];

        if ($photoSource = $this->firstMatchingFile($folder, ['*.jpg', '*.jpeg', '*.png', '*.webp'])) {
            $files['foto'] = $photoSource;
        }

        $pdfs = collect(File::files($folder))
            ->filter(fn ($file) => strtolower($file->getExtension()) === 'pdf')
            ->map(fn ($file) => [
                'path' => $file->getPathname(),
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
            ])
            ->sortBy('name')
            ->values();

        if ($pdfs->isEmpty()) {
            return $files;
        }

        $piagam = $pdfs->first(fn (array $pdf) => str_contains(Str::lower($pdf['name']), 'piagam'));
        $ppt = $pdfs
            ->filter(fn (array $pdf) => $pdf['size'] <= 50000)
            ->sortBy('size')
            ->first();

        $remaining = $pdfs->reject(function (array $pdf) use ($piagam, $ppt) {
            return in_array($pdf['path'], array_filter([
                $piagam['path'] ?? null,
                $ppt['path'] ?? null,
            ]), true);
        })->values();

        $naskah = $remaining->sortBy('size')->first();

        if (! $piagam && $remaining->count() > 1) {
            $piagam = $remaining->sortByDesc('size')->first();

            if (($piagam['path'] ?? null) === ($naskah['path'] ?? null)) {
                $piagam = null;
            }
        }

        if ($naskah) {
            $files['file_orasi'] = $naskah['path'];
        }

        if ($ppt) {
            $files['ppt'] = $ppt['path'];
        }

        if ($piagam) {
            $files['piagam'] = $piagam['path'];
            $files['sertifikat'] = $piagam['path'];
        }

        return $files;
    }

    /**
     * @param  array<int, string>  $patterns
     */
    private function firstMatchingFile(string $folder, array $patterns): ?string
    {
        foreach ($patterns as $pattern) {
            $matches = glob($folder . DIRECTORY_SEPARATOR . $pattern) ?: [];
            sort($matches);

            if ($matches !== []) {
                return $matches[0];
            }
        }

        return null;
    }

    private function copyToStorage(string $sourceFile, string $targetFolder, string $baseName): string
    {
        $extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION) ?: 'bin');
        $relativePath = $targetFolder . DIRECTORY_SEPARATOR . $baseName . '.' . $extension;
        $destination = storage_path('app/public/' . $relativePath);

        File::ensureDirectoryExists(dirname($destination));
        copy($sourceFile, $destination);

        return str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
    }

    private function matchKey(string $value): string
    {
        $tokens = array_values(array_filter(explode('-', Str::slug($value))));

        $ignoredTokens = array_flip([
            'apec',
            'asean',
            'dr',
            'eng',
            'h',
            'ir',
            'ipm',
            'ipu',
            'mce',
            'mm',
            'mhum',
            'mkes',
            'mkom',
            'mp',
            'mpd',
            'msc',
            'msi',
            'mt',
            'mta',
            'phd',
            'prof',
            'se',
            'sh',
            'shut',
            'si',
            'skm',
            'skom',
            'spd',
            'st',
            'um',
            'm',
        ]);

        $filtered = array_values(array_filter(
            $tokens,
            static fn (string $token): bool => ! isset($ignoredTokens[$token])
        ));

        return implode('-', $filtered);
    }
}
