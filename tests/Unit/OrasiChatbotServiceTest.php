<?php

namespace Tests\Unit;

use App\Services\OrasiChatbotService;
use PHPUnit\Framework\TestCase;

class OrasiChatbotServiceTest extends TestCase
{
    public function test_greeting_introduces_si_ora_formally(): void
    {
        $result = (new OrasiChatbotService())->reply('hello', $this->context());

        $this->assertSame('greeting', $result['type']);
        $this->assertStringContainsString('Si Ora', $result['message']);
        $this->assertStringContainsString('Universitas Mulawarman', $result['message']);
        $this->assertStringNotContainsString('UNMUL', $result['message']);
    }

    public function test_identity_query_introduces_si_ora(): void
    {
        $result = (new OrasiChatbotService())->reply('perkenalkan kamu siapa', $this->context());

        $this->assertSame('identity', $result['type']);
        $this->assertStringContainsString('Si Ora', $result['message']);
        $this->assertStringContainsString('Universitas Mulawarman', $result['message']);
        $this->assertStringNotContainsString('UNMUL', $result['message']);
    }

    public function test_keyword_search_accepts_mixed_language_terms(): void
    {
        $result = (new OrasiChatbotService())->reply('recording hayati 2024', $this->context());

        $this->assertSame('keyword_search', $result['type']);
        $this->assertStringContainsString('Prof. Dr. Budi Farmasi', $result['message']);
        $this->assertStringContainsString('Fakultas Farmasi', $result['message']);
    }

    public function test_general_intent_accepts_english_aliases(): void
    {
        $result = (new OrasiChatbotService())->reply('professor count', $this->context());

        $this->assertSame('stat_guru', $result['type']);
        $this->assertStringContainsString('<strong>3</strong>', $result['message']);
    }

    public function test_name_search_tolerates_common_typos(): void
    {
        $result = (new OrasiChatbotService())->reply('prof havilludin', $this->context());

        $this->assertSame('guru_default', $result['type']);
        $this->assertStringContainsString('Prof. Dr. Haviluddin', $result['message']);
    }

    public function test_statistik_per_tahun_returns_year_stats(): void
    {
        $result = (new OrasiChatbotService())->reply('Statistik per tahun', $this->context());

        $this->assertSame('stat_tahun', $result['type']);
        $this->assertStringContainsString('2024', $result['message']);
        $this->assertStringNotContainsString('Guru besar terkait', $result['message']);
    }

    public function test_statistik_per_fakultas_returns_faculty_stats(): void
    {
        $result = (new OrasiChatbotService())->reply('Statistik per fakultas', $this->context());

        $this->assertSame('stat_fakultas', $result['type']);
        $this->assertStringContainsString('Fakultas Farmasi', $result['message']);
        $this->assertStringNotContainsString('Guru besar terkait', $result['message']);
    }

    public function test_video_search_without_keyword_returns_video_list(): void
    {
        $result = (new OrasiChatbotService())->reply('Cari video dengan kata kunci', $this->context());

        $this->assertSame('list_video', $result['type']);
        $this->assertStringContainsString('Daftar video orasi', $result['message']);
    }

    public function test_document_per_faculty_returns_grouped_stats(): void
    {
        $result = (new OrasiChatbotService())->reply('Cari dokumen per fakultas', $this->context());

        $this->assertSame('stat_dokumen_fakultas', $result['type']);
        $this->assertStringContainsString('Dokumen orasi per fakultas', $result['message']);
        $this->assertStringNotContainsString('Guru besar terkait', $result['message']);
    }

    public function test_trailing_backslash_in_name_still_matches(): void
    {
        $result = (new OrasiChatbotService())->reply('havilludin\\', $this->context());

        $this->assertSame('guru_default', $result['type']);
        $this->assertStringContainsString('Prof. Dr. Haviluddin', $result['message']);
    }

    public function test_statistik_farmasi_returns_faculty_detail(): void
    {
        $result = (new OrasiChatbotService())->reply('statistik farmasi', $this->context());

        $this->assertSame('stat_fakultas_detail', $result['type']);
        $this->assertStringContainsString('Fakultas Farmasi', $result['message']);
        $this->assertStringContainsString('Prof. Dr. Budi Farmasi', $result['message']);
        $this->assertStringNotContainsString('Fakultas Teknik', $result['message']);
    }

    public function test_jumlah_guru_besar_fakultas_teknik_returns_count(): void
    {
        $result = (new OrasiChatbotService())->reply('jumlah guru besar fakultas teknik', $this->context());

        $this->assertSame('stat_guru_fakultas', $result['type']);
        $this->assertStringContainsString('Fakultas Teknik', $result['message']);
        $this->assertStringContainsString('<strong>2</strong>', $result['message']);
    }

    public function test_judul_orasi_with_formal_name_returns_title_not_faculty_stats(): void
    {
        $result = (new OrasiChatbotService())->reply(
            'Judul orasi Prof. Dr. Haviluddin',
            $this->context()
        );

        $this->assertSame('guru_judul', $result['type']);
        $this->assertStringContainsString('Kecerdasan Komputasional', $result['message']);
        $this->assertStringNotContainsString('Statistik Orasi Ilmiah', $result['message']);
    }

    public function test_judul_orasi_short_suggestion_label_works(): void
    {
        $result = (new OrasiChatbotService())->reply('Judul orasi Anindita', [
            'total_guru_besar' => 1,
            'total_orasi' => 1,
            'total_video' => 1,
            'total_dokumen' => 1,
            'total_fakultas' => 1,
            'archive_years' => [2024],
            'fakultas_stats' => [['label' => 'Fakultas Teknik', 'total' => 1]],
            'year_stats' => [['tahun' => 2024, 'total' => 1]],
            'guru_besars' => [[
                'id' => 1,
                'nama' => 'Prof. Dr. Anindita Septiarini, S.T., M.Cs.',
                'nama_normalized' => 'anindita septiarini',
                'bidang_ilmu' => 'Kecerdasan Buatan',
                'judul_orasi' => 'Judul Orasi Contoh',
                'fakultas' => 'Fakultas Teknik',
                'prodi' => 'S1 Informatika',
                'tahun_orasi' => 2024,
                'tmt' => '1 Januari 2024',
                'has_video' => true,
                'has_dokumen' => true,
                'has_naskah' => true,
                'has_ppt' => false,
                'orasi_judul' => 'Orasi Ilmiah 2024',
                'url' => '/guru-besar/anindita',
            ]],
            'orasi_list' => [],
            'urls' => [
                'home' => '/',
                'guru_besar' => '/guru-besar',
                'daftar_orasi' => '/daftar-orasi',
                'video_orasi' => '/video-orasi',
                'dokumen_orasi' => '/dokumen-orasi',
                'statistik' => '/statistik',
            ],
        ]);

        $this->assertSame('guru_judul', $result['type']);
        $this->assertStringContainsString('Judul Orasi Contoh', $result['message']);
    }

    private function context(): array
    {
        return [
            'total_guru_besar' => 3,
            'total_orasi' => 1,
            'total_video' => 1,
            'total_dokumen' => 1,
            'total_fakultas' => 2,
            'archive_years' => [2024],
            'fakultas_stats' => [
                ['label' => 'Fakultas Farmasi', 'total' => 1],
                ['label' => 'Fakultas Teknik', 'total' => 1],
            ],
            'year_stats' => [
                ['tahun' => 2024, 'total' => 2],
            ],
            'guru_besars' => [
                [
                    'id' => 1,
                    'nama' => 'Prof. Dr. Budi Farmasi',
                    'nama_normalized' => 'budi farmasi',
                    'bidang_ilmu' => 'Teknologi bahan alam',
                    'judul_orasi' => 'Inovasi Farmasi Berbasis Sumber Daya Hayati',
                    'fakultas' => 'Fakultas Farmasi',
                    'prodi' => 'S1 Farmasi',
                    'tahun_orasi' => 2024,
                    'tmt' => '1 Januari 2024',
                    'has_video' => true,
                    'has_dokumen' => true,
                    'has_naskah' => true,
                    'has_ppt' => false,
                    'orasi_judul' => 'Orasi Ilmiah 2024',
                    'url' => '/guru-besar/prof-budi-farmasi',
                ],
                [
                    'id' => 2,
                    'nama' => 'Prof. Dr. Sari Teknik',
                    'nama_normalized' => 'sari teknik',
                    'bidang_ilmu' => 'Rekayasa material',
                    'judul_orasi' => 'Material Cerdas untuk Infrastruktur',
                    'fakultas' => 'Fakultas Teknik',
                    'prodi' => 'S1 Teknik Sipil',
                    'tahun_orasi' => 2024,
                    'tmt' => '1 Februari 2024',
                    'has_video' => false,
                    'has_dokumen' => false,
                    'has_naskah' => false,
                    'has_ppt' => false,
                    'orasi_judul' => 'Orasi Ilmiah 2024',
                    'url' => '/guru-besar/prof-sari-teknik',
                ],
                [
                    'id' => 3,
                    'nama' => 'Prof. Dr. Haviluddin',
                    'nama_normalized' => 'haviluddin',
                    'bidang_ilmu' => 'Informatika',
                    'judul_orasi' => 'Kecerdasan Komputasional untuk Transformasi Digital',
                    'fakultas' => 'Fakultas Teknik',
                    'prodi' => 'S1 Informatika',
                    'tahun_orasi' => 2024,
                    'tmt' => '1 Maret 2024',
                    'has_video' => true,
                    'has_dokumen' => true,
                    'has_naskah' => true,
                    'has_ppt' => true,
                    'orasi_judul' => 'Orasi Ilmiah 2024',
                    'url' => '/guru-besar/prof-haviluddin',
                ],
            ],
            'orasi_list' => [
                [
                    'id' => 1,
                    'judul' => 'Orasi Guru Besar Tahun 2024',
                    'judul_lengkap' => 'Orasi Ilmiah 2024 - Orasi Guru Besar Tahun 2024',
                    'tahun' => 2024,
                    'status' => 'published',
                    'tanggal' => '1 Januari 2024',
                    'guru_count' => 2,
                    'url' => '/daftar-orasi?orasi=1',
                ],
            ],
            'urls' => [
                'home' => '/',
                'guru_besar' => '/guru-besar',
                'daftar_orasi' => '/daftar-orasi',
                'video_orasi' => '/video-orasi',
                'dokumen_orasi' => '/dokumen-orasi',
                'statistik' => '/statistik',
            ],
        ];
    }
}
