<?php

namespace App\Services;

use App\Models\GuruBesar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use setasign\Fpdi\Fpdi;
use Symfony\Component\Process\Process;

class OrasiDocumentMergeService
{
    /** @var array<int, string> */
    private array $publicStatuses = ['published', 'archived'];

    public function yearSlug(string $yearLabel): string
    {
        return $yearLabel === 'Tanpa Tahun' ? 'tanpa-tahun' : $yearLabel;
    }

    public function yearLabelFromSlug(string $slug): string
    {
        if ($slug === 'tanpa-tahun') {
            return 'Tanpa Tahun';
        }

        if (ctype_digit($slug)) {
            return (string) (int) $slug;
        }

        throw new RuntimeException('Tahun arsip tidak valid.');
    }

    public function documentKindFromSlug(string $slug): string
    {
        return match ($slug) {
            'naskah' => 'naskah',
            'presentasi' => 'presentasi',
            default => throw new RuntimeException('Jenis dokumen tidak valid.'),
        };
    }

    /**
     * @return array{path: string, cleanup: array<int, string>}
     */
    public function mergeYearDocuments(string $yearLabel, string $kind): array
    {
        $pdfSources = $this->collectPdfSourcesForYear($yearLabel, $kind);

        if ($pdfSources === []) {
            $label = $kind === 'presentasi' ? 'presentasi' : 'naskah';

            throw new RuntimeException("Tidak ada {$label} PDF yang dapat digabungkan untuk tahun ini.");
        }

        $cleanup = [];
        $outputPath = tempnam(sys_get_temp_dir(), 'orasi-merge-');

        if ($outputPath === false) {
            throw new RuntimeException('Tidak dapat menyiapkan file gabungan.');
        }

        $outputPdf = $outputPath.'.pdf';
        @unlink($outputPath);

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        foreach ($pdfSources as $source) {
            if (! is_file($source['path'])) {
                continue;
            }

            if (isset($source['cleanup'])) {
                $cleanup[] = $source['cleanup'];
            }

            try {
                $pageCount = $pdf->setSourceFile($source['path']);
            } catch (\Throwable) {
                continue;
            }

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                $template = $pdf->importPage($pageNumber);
                $size = $pdf->getTemplateSize($template);
                $orientation = ($size['width'] ?? 0) > ($size['height'] ?? 0) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($template);
            }
        }

        if ($pdf->PageNo() === 0) {
            throw new RuntimeException('Tidak ada halaman PDF yang berhasil digabungkan.');
        }

        $pdf->Output($outputPdf, 'F');

        $cleanup[] = $outputPdf;

        return [
            'path' => $outputPdf,
            'cleanup' => $cleanup,
        ];
    }

    /**
     * @return array<int, array{path: string, cleanup?: string}>
     */
    public function collectPdfSourcesForYear(string $yearLabel, string $kind): array
    {
        $sources = [];
        $pathField = $kind === 'presentasi' ? 'ppt_path' : 'file_orasi_path';

        foreach ($this->publicDocumentItems() as $item) {
            if ($this->archiveYearLabel($item) !== $yearLabel) {
                continue;
            }

            $resolved = $this->resolveMergeablePdf($item->{$pathField});

            if ($resolved === null) {
                continue;
            }

            $sources[] = $resolved;
        }

        return $sources;
    }

    /**
     * @return Collection<int, GuruBesar>
     */
    private function publicDocumentItems(): Collection
    {
        return GuruBesar::query()
            ->with(['orasiIlmiah', 'fakultas'])
            ->join('orasi_ilmiahs', 'orasi_ilmiahs.id', '=', 'guru_besars.orasi_ilmiah_id')
            ->whereIn('orasi_ilmiahs.status', $this->publicStatuses)
            ->where(function ($query) {
                $query->whereNotNull('guru_besars.file_orasi_path')
                    ->orWhereNotNull('guru_besars.ppt_path');
            })
            ->select('guru_besars.*')
            ->orderByDesc('orasi_ilmiahs.tahun')
            ->orderByTmtAscending()
            ->get();
    }

    private function archiveYearLabel(GuruBesar $item): string
    {
        return (string) ($item->archiveYear() ?: 'Tanpa Tahun');
    }

    /**
     * @return array{path: string, cleanup?: string}|null
     */
    private function resolveMergeablePdf(?string $relativePath): ?array
    {
        if (! filled($relativePath) || ! Storage::disk('public')->exists($relativePath)) {
            return null;
        }

        $absolutePath = Storage::disk('public')->path($relativePath);
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION) ?: '');

        if ($extension === 'pdf') {
            return ['path' => $absolutePath];
        }

        if (in_array($extension, ['ppt', 'pptx', 'odp'], true)) {
            $converted = $this->convertOfficeDocumentToPdf($absolutePath);

            if ($converted !== null) {
                return [
                    'path' => $converted,
                    'cleanup' => $converted,
                ];
            }
        }

        return null;
    }

    private function convertOfficeDocumentToPdf(string $absolutePath): ?string
    {
        $binary = $this->libreOfficeBinary();

        if ($binary === null) {
            return null;
        }

        $outputDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'orasi-merge-'.Str::random(12);

        if (! @mkdir($outputDir) && ! is_dir($outputDir)) {
            return null;
        }

        $process = new Process([
            $binary,
            '--headless',
            '--nologo',
            '--nofirststartwizard',
            '--convert-to',
            'pdf',
            '--outdir',
            $outputDir,
            $absolutePath,
        ]);

        $process->setTimeout(180);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->removeDirectory($outputDir);

            return null;
        }

        $baseName = pathinfo($absolutePath, PATHINFO_FILENAME);
        $convertedPath = $outputDir.DIRECTORY_SEPARATOR.$baseName.'.pdf';

        if (! is_file($convertedPath)) {
            $this->removeDirectory($outputDir);

            return null;
        }

        $finalPath = tempnam(sys_get_temp_dir(), 'orasi-ppt-');

        if ($finalPath === false) {
            $this->removeDirectory($outputDir);

            return null;
        }

        @unlink($finalPath);
        $finalPdf = $finalPath.'.pdf';

        if (! @rename($convertedPath, $finalPdf)) {
            @unlink($finalPath);
            $this->removeDirectory($outputDir);

            return null;
        }

        $this->removeDirectory($outputDir);

        return $finalPdf;
    }

    private function libreOfficeBinary(): ?string
    {
        $configured = config('services.libreoffice.binary');

        if (filled($configured) && $this->isExecutableBinary((string) $configured)) {
            return (string) $configured;
        }

        $candidates = [
            'soffice',
            'libreoffice',
            'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
            'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
            '/usr/bin/libreoffice',
            '/usr/bin/soffice',
        ];

        foreach ($candidates as $candidate) {
            if ($this->isExecutableBinary($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function isExecutableBinary(string $binary): bool
    {
        if (str_contains($binary, DIRECTORY_SEPARATOR) || str_contains($binary, '/')) {
            return is_file($binary);
        }

        $process = new Process([$binary, '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        foreach (scandir($directory) ?: [] as $entry) {
            if (in_array($entry, ['.', '..'], true)) {
                continue;
            }

            $path = $directory.DIRECTORY_SEPARATOR.$entry;

            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($directory);
    }
}
