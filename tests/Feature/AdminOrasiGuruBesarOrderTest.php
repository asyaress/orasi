<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureTwoFactorConfirmed;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrasiGuruBesarOrderTest extends TestCase
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

    public function test_admin_and_home_use_saved_guru_besar_order(): void
    {
        $orasi = $this->createOrasi();
        $first = $this->createGuru($orasi, 'Prof. Urutan Awal', 1, '2026-02-01');
        $second = $this->createGuru($orasi, 'Prof. Urutan Akhir', 2, '2026-01-01');

        $this->get(route('admin.orasi-ilmiah.show', $orasi))
            ->assertOk()
            ->assertSeeInOrder([$first->nama, $second->nama]);

        $this->putJson(route('admin.orasi-ilmiah.guru-besar.reorder', $orasi), [
            'guru_besar_ids' => [$second->id, $first->id],
        ])->assertOk();

        $this->assertSame(2, $first->fresh()->urutan);
        $this->assertSame(1, $second->fresh()->urutan);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder([$second->nama, $first->nama]);
    }

    public function test_reorder_rejects_an_incomplete_assignment_list(): void
    {
        $orasi = $this->createOrasi();
        $first = $this->createGuru($orasi, 'Prof. Pertama', 1, '2026-01-01');
        $this->createGuru($orasi, 'Prof. Kedua', 2, '2026-02-01');

        $this->putJson(route('admin.orasi-ilmiah.guru-besar.reorder', $orasi), [
            'guru_besar_ids' => [$first->id],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('guru_besar_ids');
    }

    public function test_new_assignment_is_appended_and_detach_clears_order(): void
    {
        $orasi = $this->createOrasi();
        $this->createGuru($orasi, 'Prof. Sudah Ada', 1, '2026-01-01');
        $newGuru = GuruBesar::create([
            'nama' => 'Prof. Baru',
            'sumber' => GuruBesar::SUMBER_MANUAL,
            'tmt' => '2025-01-01',
        ]);

        $this->postJson(route('admin.orasi-ilmiah.guru-besar.attach', $orasi), [
            'guru_besar_id' => $newGuru->id,
        ])->assertOk();

        $this->assertSame($orasi->id, $newGuru->fresh()->orasi_ilmiah_id);
        $this->assertSame(2, $newGuru->fresh()->urutan);

        $this->deleteJson(route('admin.orasi-ilmiah.guru-besar.detach', [$orasi, $newGuru]))
            ->assertOk();

        $this->assertNull($newGuru->fresh()->orasi_ilmiah_id);
        $this->assertNull($newGuru->fresh()->urutan);
    }

    private function createOrasi(): OrasiIlmiah
    {
        return OrasiIlmiah::create([
            'judul' => 'Orasi Ilmiah 2026',
            'tahun' => 2026,
            'tanggal_pelaksanaan' => '2026-09-01',
            'jenis' => 'Luring',
            'status' => 'published',
        ]);
    }

    private function createGuru(OrasiIlmiah $orasi, string $nama, int $urutan, string $tmt): GuruBesar
    {
        return GuruBesar::create([
            'orasi_ilmiah_id' => $orasi->id,
            'urutan' => $urutan,
            'nama' => $nama,
            'sumber' => GuruBesar::SUMBER_MANUAL,
            'tmt' => $tmt,
        ]);
    }
}
