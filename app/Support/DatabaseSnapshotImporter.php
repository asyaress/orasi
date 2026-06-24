<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class DatabaseSnapshotImporter
{
    /** @var list<string> */
    public const TRUNCATE_ORDER = [
        'two_factor_devices',
        'guru_besars',
        'pengumumans',
        'orasi_ilmiahs',
        'prodis',
        'fakultas',
        'orasis',
        'users',
    ];

    public function import(?string $sourceDirectory = null): array
    {
        $directory = $sourceDirectory ?: database_path('dumps');
        $manifestPath = $directory.DIRECTORY_SEPARATOR.'manifest.json';

        if (! is_file($manifestPath)) {
            throw new RuntimeException("Snapshot manifest tidak ditemukan: {$manifestPath}");
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (! is_array($manifest)) {
            throw new RuntimeException('Snapshot manifest tidak valid.');
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        try {
            foreach (self::TRUNCATE_ORDER as $table) {
                DB::table($table)->truncate();
            }

            foreach (DatabaseSnapshotExporter::TABLES as $table) {
                $this->importTable($directory, $table);
            }
        } finally {
            if (DB::getDriverName() === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif (DB::getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        }

        return $manifest;
    }

    private function importTable(string $directory, string $table): void
    {
        $path = $directory.DIRECTORY_SEPARATOR.$table.'.json';

        if (! is_file($path)) {
            throw new RuntimeException("Snapshot tabel tidak ditemukan: {$path}");
        }

        $rows = json_decode((string) file_get_contents($path), true);

        if (! is_array($rows)) {
            throw new RuntimeException("Snapshot tabel tidak valid: {$table}");
        }

        if ($rows === []) {
            return;
        }

        if ($table === 'users') {
            $rows = array_map(fn (array $row) => $this->normalizeUserRow($row), $rows);
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table($table)->insert($chunk);
        }

        $maxId = collect($rows)->max('id');

        if ($maxId && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `'.$table.'` AUTO_INCREMENT = '.((int) $maxId + 1));
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function normalizeUserRow(array $row): array
    {
        $row['two_factor_secret'] = null;
        $row['two_factor_recovery_codes'] = null;
        $row['two_factor_confirmed_at'] = null;

        return $row;
    }
}
