<?php

namespace Database\Seeders;

use App\Support\DatabaseSnapshotImporter;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductionSnapshotSeeder extends Seeder
{
    public function run(): void
    {
        $manifestPath = database_path('dumps/manifest.json');

        if (! is_file($manifestPath)) {
            throw new RuntimeException(
                'Snapshot produksi tidak ditemukan. Jalankan: php artisan orasi:export-snapshot'
            );
        }

        $manifest = app(DatabaseSnapshotImporter::class)->import();

        $this->command?->info('Snapshot produksi berhasil diimpor (1:1).');

        foreach ($manifest['tables'] ?? [] as $table => $count) {
            $this->command?->line(sprintf('  - %s: %d baris', $table, $count));
        }
    }
}
