<?php

namespace Tests\Unit;

use App\Services\OrasiDocumentMergeService;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class OrasiDocumentMergeServiceTest extends TestCase
{
    #[Test]
    public function it_maps_year_labels_to_route_slugs_and_back(): void
    {
        $service = new OrasiDocumentMergeService();

        $this->assertSame('2025', $service->yearSlug('2025'));
        $this->assertSame('tanpa-tahun', $service->yearSlug('Tanpa Tahun'));
        $this->assertSame('2025', $service->yearLabelFromSlug('2025'));
        $this->assertSame('Tanpa Tahun', $service->yearLabelFromSlug('tanpa-tahun'));
    }

    #[Test]
    public function it_rejects_invalid_year_slugs(): void
    {
        $service = new OrasiDocumentMergeService();

        $this->expectException(RuntimeException::class);
        $service->yearLabelFromSlug('invalid-year');
    }

    #[Test]
    public function it_accepts_document_kind_slugs(): void
    {
        $service = new OrasiDocumentMergeService();

        $this->assertSame('naskah', $service->documentKindFromSlug('naskah'));
        $this->assertSame('presentasi', $service->documentKindFromSlug('presentasi'));
    }

    #[Test]
    public function it_rejects_invalid_document_kind_slugs(): void
    {
        $service = new OrasiDocumentMergeService();

        $this->expectException(RuntimeException::class);
        $service->documentKindFromSlug('semua');
    }
}
