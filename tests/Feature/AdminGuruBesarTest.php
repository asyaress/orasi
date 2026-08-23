<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureTwoFactorConfirmed;
use App\Models\Fakultas;
use App\Models\GuruBesar;
use App\Models\PosterTheme;
use App\Models\Prodi;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGuruBesarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            Authenticate::class,
            EnsureAdmin::class,
            EnsureTwoFactorConfirmed::class,
        ]);
    }

    public function test_admin_can_store_guru_besar_with_optional_empty_fields(): void
    {
        $response = $this->post(route('admin.guru-besar.store'), [
            'nama' => 'Prof. Dr. Contoh Guru Besar',
            'jenis_kelamin' => '',
            'bidang_ilmu' => '',
            'judul_orasi' => '',
            'tmt' => '',
            'fakultas_id' => '',
            'prodi_id' => '',
            'foto_display_mode' => '',
        ]);

        $response->assertRedirect(route('admin.guru-besar.index'));

        $guruBesar = GuruBesar::sole();
        $this->assertSame(GuruBesar::SUMBER_MANUAL, $guruBesar->sumber);
        $this->assertSame(GuruBesar::FOTO_DISPLAY_MODE_SVG_BG_PHOTO, $guruBesar->foto_display_mode);
        $this->assertNull($guruBesar->jenis_kelamin);
        $this->assertNull($guruBesar->bidang_ilmu);
    }

    public function test_admin_can_store_guru_besar_when_prodi_is_selected(): void
    {
        $fakultas = Fakultas::create([
            'nama' => 'Fakultas Kehutanan',
            'slug' => 'fakultas-kehutanan',
            'is_active' => true,
        ]);
        $prodi = Prodi::create([
            'fakultas_id' => $fakultas->id,
            'nama' => 'Pemuliaan Pohon',
            'slug' => 'pemuliaan-pohon',
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.guru-besar.store'), [
            'nama' => 'Prof. Ir. Sukartiningsih, M.Sc., Ph.D., IPU.',
            'bidang_ilmu' => 'Pemuliaan Pohon',
            'fakultas_id' => '',
            'prodi_id' => $prodi->id,
            'foto_display_mode' => GuruBesar::FOTO_DISPLAY_MODE_SVG_BG_PHOTO,
        ]);

        $response->assertRedirect(route('admin.guru-besar.index'));

        $guruBesar = GuruBesar::sole();
        $this->assertSame($fakultas->id, $guruBesar->fakultas_id);
        $this->assertSame($prodi->id, $guruBesar->prodi_id);
    }

    public function test_admin_can_save_custom_poster_theme_for_year(): void
    {
        $response = $this->put(route('admin.poster-themes.update', 2026), [
            'name' => 'Tema Tahun Ini',
            'frame_background' => '#f3b23a',
            'frame_highlight' => '#2f8a3a',
            'footer_start' => '#2457a8',
            'footer_end' => '#102c61',
            'text_color' => '#ffffff',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.poster-themes.index'));

        $theme = PosterTheme::where('year', 2026)->sole();
        $this->assertSame('Tema Tahun Ini', $theme->name);
        $this->assertSame('#2f8a3a', $theme->frame_highlight);
        $this->assertTrue($theme->is_active);
    }
}
