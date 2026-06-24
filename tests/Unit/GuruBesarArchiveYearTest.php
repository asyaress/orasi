<?php

namespace Tests\Unit;

use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use Tests\TestCase;

class GuruBesarArchiveYearTest extends TestCase
{
    public function test_archive_year_merges_2019_to_2023_into_single_bucket(): void
    {
        $guruBesar = new GuruBesar([
            'tmt' => '2019-01-10',
        ]);

        $this->assertSame(2023, $guruBesar->archiveYear());
    }

    public function test_archive_year_uses_orasi_year_when_tmt_is_missing(): void
    {
        $guruBesar = new GuruBesar();
        $guruBesar->setRelation('orasiIlmiah', new OrasiIlmiah([
            'tahun' => 2020,
        ]));

        $this->assertSame(2023, $guruBesar->archiveYear());
    }

    public function test_archive_year_keeps_years_outside_the_merge_range(): void
    {
        $guruBesar = new GuruBesar([
            'tmt' => '2024-01-10',
        ]);

        $this->assertSame(2024, $guruBesar->archiveYear());
    }

    public function test_downloadable_package_includes_sertifikat(): void
    {
        $guruBesar = new GuruBesar([
            'sertifikat_path' => 'guru-besar/2025/contoh/sertifikat-orasi.pdf',
        ]);

        $this->assertTrue($guruBesar->hasDownloadablePackage());
        $this->assertTrue($guruBesar->hasSertifikat());
    }
}
