<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class SyncPublicHtmlCommand extends Command
{
    protected $signature = 'orasi:sync-public-html
                            {--public-html= : Direktori document root (public_html)}
                            {--app-root= : Direktori root aplikasi Laravel}';

    protected $description = 'Salin isi public/ ke public_html bawaan server (sejajar folder orasi)';

    public function handle(): int
    {
        $appRoot = realpath($this->option('app-root') ?: base_path());

        if ($appRoot === false) {
            throw new RuntimeException('Direktori aplikasi Laravel tidak valid.');
        }

        $publicHtml = $this->resolvePublicHtmlDirectory($appRoot);

        $source = public_path();
        $copied = $this->mirrorPublicDirectory($source, $publicHtml);
        $this->writeSharedHostingIndex($appRoot, $publicHtml);
        $this->linkPublicStorage($appRoot, $publicHtml);

        $this->info('Sinkronisasi ke public_html bawaan server selesai.');
        $this->line("  App root     : {$appRoot}");
        $this->line("  Public HTML  : {$publicHtml}");
        $this->line("  File disalin : {$copied}");
        $this->line("  Storage link : {$publicHtml}/storage -> {$appRoot}/storage/app/public");

        return self::SUCCESS;
    }

    private function resolvePublicHtmlDirectory(string $appRoot): string
    {
        $appRoot = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $appRoot), DIRECTORY_SEPARATOR);
        $configured = $this->option('public-html') ?: env('ORASI_PUBLIC_HTML_DIR');

        if (! $configured) {
            $configured = $this->detectServerPublicHtml($appRoot);
        } elseif (! $this->isAbsolutePath($configured)) {
            $configured = dirname($appRoot).DIRECTORY_SEPARATOR.ltrim($configured, DIRECTORY_SEPARATOR);
        }

        $configured = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $configured), DIRECTORY_SEPARATOR);

        $this->guardPublicHtmlOutsideApp($appRoot, $configured);

        if (! is_dir($configured)) {
            throw new RuntimeException(
                "Folder public_html bawaan server tidak ditemukan di: {$configured}\n".
                'Gunakan folder public_html yang sudah ada di hosting (sejajar dengan folder orasi).'
            );
        }

        return realpath($configured) ?: $configured;
    }

    private function detectServerPublicHtml(string $appRoot): string
    {
        $home = env('HOME') ?: ($_SERVER['HOME'] ?? null);

        if (is_string($home) && $home !== '') {
            $homePublicHtml = rtrim($home, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'public_html';

            if (is_dir($homePublicHtml)) {
                return $homePublicHtml;
            }
        }

        return dirname($appRoot).DIRECTORY_SEPARATOR.'public_html';
    }

    private function guardPublicHtmlOutsideApp(string $appRoot, string $publicHtml): void
    {
        $appRootReal = realpath($appRoot) ?: $appRoot;
        $publicHtmlReal = realpath($publicHtml) ?: $publicHtml;

        $appRootNormalized = rtrim(str_replace('\\', '/', $appRootReal), '/');
        $publicHtmlNormalized = rtrim(str_replace('\\', '/', $publicHtmlReal), '/');

        if ($publicHtmlNormalized === $appRootNormalized
            || str_starts_with($publicHtmlNormalized.'/', $appRootNormalized.'/')) {
            throw new RuntimeException(
                'public_html tidak boleh berada di dalam folder aplikasi orasi. '.
                'Set ORASI_PUBLIC_HTML_DIR ke folder di luar orasi, contoh: /home/orasi/public_html'
            );
        }
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/')
            || str_starts_with($path, '\\')
            || (bool) preg_match('/^[A-Za-z]:[\\\\\\/]/', $path);
    }

    private function mirrorPublicDirectory(string $source, string $target): int
    {
        $source = rtrim($source, DIRECTORY_SEPARATOR);
        $target = rtrim($target, DIRECTORY_SEPARATOR);
        $copied = 0;

        if (! File::isDirectory($source)) {
            throw new RuntimeException("Folder public tidak ditemukan: {$source}");
        }

        $files = File::exists($source)
            ? collect(File::allFiles($source))
            : collect();

        foreach ($files as $file) {
            $pathname = $file->getPathname();
            $relative = Str::after($pathname, $source.DIRECTORY_SEPARATOR);

            if ($relative === 'index.php' || str_starts_with($relative, 'storage')) {
                continue;
            }

            $destination = $target.DIRECTORY_SEPARATOR.$relative;

            File::ensureDirectoryExists(dirname($destination));
            File::copy($pathname, $destination);
            $copied++;
        }

        return $copied;
    }

    private function writeSharedHostingIndex(string $appRoot, string $publicHtml): void
    {
        $stubPath = base_path('deploy/public_html.index.php.stub');

        if (! is_file($stubPath)) {
            throw new RuntimeException("Stub index shared hosting tidak ditemukan: {$stubPath}");
        }

        $index = str_replace(
            '__ORASI_APP_ROOT__',
            str_replace('\\', '/', $appRoot),
            (string) file_get_contents($stubPath)
        );

        File::put($publicHtml.DIRECTORY_SEPARATOR.'index.php', $index);
    }

    private function linkPublicStorage(string $appRoot, string $publicHtml): void
    {
        $storageTarget = $appRoot.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public';
        $storageLink = $publicHtml.DIRECTORY_SEPARATOR.'storage';

        if (! File::isDirectory($storageTarget)) {
            File::makeDirectory($storageTarget, 0755, true);
        }

        if (file_exists($storageLink) || is_link($storageLink)) {
            if (is_dir($storageLink) && ! is_link($storageLink)) {
                File::deleteDirectory($storageLink);
            } else {
                @unlink($storageLink);
            }
        }

        if (! symlink($storageTarget, $storageLink)) {
            throw new RuntimeException("Gagal membuat symlink storage di {$storageLink}");
        }
    }
}
