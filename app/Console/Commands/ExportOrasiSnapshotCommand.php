<?php

namespace App\Console\Commands;

use App\Support\DatabaseSnapshotExporter;
use Illuminate\Console\Command;

class ExportOrasiSnapshotCommand extends Command
{
    protected $signature = 'orasi:export-snapshot {--path= : Direktori output snapshot}';

    protected $description = 'Export data database lokal ke snapshot JSON 1:1 untuk deploy';

    public function handle(DatabaseSnapshotExporter $exporter): int
    {
        $manifest = $exporter->export($this->option('path') ?: null);

        $this->info('Snapshot database berhasil diekspor.');

        foreach ($manifest['tables'] as $table => $count) {
            $this->line(sprintf('  - %s: %d baris', $table, $count));
        }

        $this->newLine();
        $this->comment('Lokasi: database/dumps/');

        return self::SUCCESS;
    }
}
