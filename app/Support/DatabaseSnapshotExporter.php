<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class DatabaseSnapshotExporter
{
    /** @var list<string> */
    public const TABLES = [
        'users',
        'fakultas',
        'prodis',
        'orasis',
        'orasi_ilmiahs',
        'guru_besars',
        'pengumumans',
        'two_factor_devices',
    ];

    public function export(?string $targetDirectory = null): array
    {
        $directory = $targetDirectory ?: database_path('dumps');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $counts = [];

        foreach (self::TABLES as $table) {
            $rows = DB::table($table)->orderBy('id')->get()->map(fn ($row) => (array) $row)->all();
            $counts[$table] = count($rows);

            File::put(
                $directory.DIRECTORY_SEPARATOR.$table.'.json',
                json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
        }

        $manifest = [
            'exported_at' => now()->toIso8601String(),
            'connection' => config('database.default'),
            'database' => config('database.connections.'.config('database.default').'.database'),
            'tables' => $counts,
        ];

        File::put(
            $directory.DIRECTORY_SEPARATOR.'manifest.json',
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return $manifest;
    }
}
