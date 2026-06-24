<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProductionSnapshotManifestTest extends TestCase
{
    public function test_production_snapshot_manifest_exists_with_expected_tables(): void
    {
        $manifestPath = dirname(__DIR__, 2).'/database/dumps/manifest.json';

        $this->assertFileExists($manifestPath);

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        $this->assertIsArray($manifest);
        $this->assertSame(58, $manifest['tables']['guru_besars'] ?? null);
        $this->assertSame(4, $manifest['tables']['orasi_ilmiahs'] ?? null);
        $this->assertSame(13, $manifest['tables']['fakultas'] ?? null);
    }
}
