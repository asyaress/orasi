<?php

namespace Tests\Feature;

use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengumumanPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Pengumuman::query()->delete();
    }

    public function test_published_announcements_appear_on_public_index_and_detail(): void
    {
        $announcement = Pengumuman::create([
            'judul' => 'Agenda Orasi Ilmiah 2026',
            'slug' => 'agenda-orasi-ilmiah-2026',
            'ringkasan' => 'Informasi agenda terbaru.',
            'konten' => '<p>Konten pengumuman resmi.</p>',
            'tags' => ['Agenda', 'Orasi'],
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $this->get(route('portal.pengumuman.index'))
            ->assertOk()
            ->assertSee($announcement->judul)
            ->assertSee('orasi-page-banner has-video', false)
            ->assertSee('youtube.com/embed/', false)
            ->assertDontSee('Cari pengumuman')
            ->assertDontSee('pengumuman-tags', false)
            ->assertDontSee($announcement->ringkasan)
            ->assertSee(route('portal.pengumuman.show', $announcement));

        $this->get(route('portal.pengumuman.show', $announcement))
            ->assertOk()
            ->assertSee($announcement->judul)
            ->assertSee('pengumuman-detail-video-banner', false)
            ->assertSee('youtube.com/embed/', false)
            ->assertDontSee('pengumuman-detail-cover', false)
            ->assertSee('Konten pengumuman resmi.');
    }

    public function test_draft_and_scheduled_announcements_are_not_public(): void
    {
        $draft = Pengumuman::create([
            'judul' => 'Masih Draft',
            'slug' => 'masih-draft',
            'konten' => '<p>Belum tayang.</p>',
            'status' => 'draft',
        ]);

        $scheduled = Pengumuman::create([
            'judul' => 'Tayang Besok',
            'slug' => 'tayang-besok',
            'konten' => '<p>Belum waktunya.</p>',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('portal.pengumuman.index'))
            ->assertOk()
            ->assertDontSee($draft->judul)
            ->assertDontSee($scheduled->judul);

        $this->get(route('portal.pengumuman.show', $draft))->assertNotFound();
        $this->get(route('portal.pengumuman.show', $scheduled))->assertNotFound();
    }

    public function test_home_shows_latest_announcement_card_after_statistics(): void
    {
        $announcement = Pengumuman::create([
            'judul' => 'Prestasi Guru Besar UNMUL',
            'slug' => 'prestasi-guru-besar-unmul',
            'konten' => '<p>Isi pengumuman.</p>',
            'cover_path' => 'images/pengumuman/foto-sampul-conten-haviluddin.jpeg',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="pengumuman-home"', false)
            ->assertSee($announcement->judul)
            ->assertSee($announcement->cover_url);

        $html = $response->getContent();
        $this->assertLessThan(
            strpos($html, 'id="pengumuman-home"'),
            strpos($html, 'id="statistik"')
        );
    }

    public function test_home_sliders_exhaust_each_year_before_advancing_to_the_next_year(): void
    {
        foreach ([2025 => 5, 2024 => 2, 2023 => 1] as $year => $total) {
            $orasi = OrasiIlmiah::create([
                'judul' => "Orasi Ilmiah {$year}",
                'tahun' => $year,
                'tanggal_pelaksanaan' => "{$year}-06-01",
                'jenis' => 'Luring',
                'status' => 'published',
            ]);

            foreach (range(1, $total) as $number) {
                GuruBesar::create([
                    'orasi_ilmiah_id' => $orasi->id,
                    'pegawai_id' => "GB-{$year}-{$number}",
                    'sumber' => GuruBesar::SUMBER_MANUAL,
                    'nama' => "Prof. Tahun {$year} Nomor {$number}",
                    'youtube_url' => 'https://youtu.be/S_9XNYv6RZo',
                    'file_orasi_path' => "dokumen/{$year}/naskah-{$number}.pdf",
                    'fakultas_snapshot' => 'Fakultas Teknik',
                ]);
            }
        }

        $html = $this->get(route('home'))->assertOk()->getContent();

        preg_match_all('/data-orator-slide[^>]*data-slide-year="([^"]+)"/', $html, $oratorMatches);
        preg_match_all('/data-video-slide[^>]*data-slide-year="([^"]+)"/', $html, $videoMatches);
        preg_match_all('/data-document-slide[^>]*data-slide-year="([^"]+)"/', $html, $documentMatches);

        $expectedYears = ['2025', '2025', '2024', '2023'];
        $this->assertSame($expectedYears, $oratorMatches[1]);
        $this->assertSame($expectedYears, $videoMatches[1]);
        $this->assertSame($expectedYears, $documentMatches[1]);
        $this->assertSame(0, substr_count($html, 'data-sync-group="home-archive-years"'));
        $this->assertStringContainsString('Prof. Tahun 2025 Nomor 5', $html);
        $this->assertStringContainsString('Prof. Tahun 2023 Nomor 1', $html);
    }
}
