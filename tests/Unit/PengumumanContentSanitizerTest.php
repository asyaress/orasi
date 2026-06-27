<?php

namespace Tests\Unit;

use App\Services\PengumumanContentSanitizer;
use PHPUnit\Framework\TestCase;

class PengumumanContentSanitizerTest extends TestCase
{
    public function test_it_removes_executable_markup_and_keeps_editor_content(): void
    {
        $html = '<p onclick="alert(1)">Halo <strong>UNMUL</strong></p>'
            .'<script>alert(2)</script>'
            .'<img src="javascript:alert(3)" onerror="alert(4)" alt="Cover">';

        $clean = (new PengumumanContentSanitizer)->sanitize($html);

        $this->assertStringContainsString('<strong>UNMUL</strong>', $clean);
        $this->assertStringContainsString('<img alt="Cover">', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringNotContainsString('<script', $clean);
    }
}
