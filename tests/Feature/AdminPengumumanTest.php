<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureTwoFactorConfirmed;
use App\Models\Pengumuman;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPengumumanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Pengumuman::query()->delete();
        $this->withoutMiddleware([
            Authenticate::class,
            EnsureAdmin::class,
            EnsureTwoFactorConfirmed::class,
        ]);
        Storage::fake('public');
    }

    public function test_admin_can_create_published_announcement_with_cover_tags_and_safe_content(): void
    {
        $response = $this->post(route('admin.pengumuman.store'), [
            'judul' => 'Pengumuman Wisuda Guru Besar',
            'ringkasan' => 'Jadwal pelaksanaan terbaru.',
            'konten' => '<p onclick="alert(1)">Isi <strong>resmi</strong>.</p><script>alert(2)</script>',
            'cover' => UploadedFile::fake()->image('cover.jpg', 1280, 720),
            'tags' => 'Agenda, Akademik, agenda',
            'status' => 'published',
            'is_pinned' => '1',
        ]);

        $response->assertRedirect(route('admin.pengumuman.index'));

        $announcement = Pengumuman::sole();
        $this->assertSame('pengumuman-wisuda-guru-besar', $announcement->slug);
        $this->assertSame(['Agenda', 'Akademik'], $announcement->tags);
        $this->assertTrue($announcement->is_pinned);
        $this->assertNotNull($announcement->published_at);
        $this->assertStringNotContainsString('onclick', $announcement->konten);
        $this->assertStringNotContainsString('<script', $announcement->konten);
        Storage::disk('public')->assertExists($announcement->cover_path);
    }

    public function test_admin_can_upload_summernote_image(): void
    {
        $response = $this->postJson(route('admin.pengumuman.upload-image'), [
            'image' => UploadedFile::fake()->image('konten.png', 900, 600),
        ]);

        $path = $response->assertOk()->json('path');
        $this->assertStringStartsWith('pengumuman/content/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_delete_announcement_and_its_cover(): void
    {
        $coverPath = UploadedFile::fake()->image('cover.webp')->store('pengumuman/covers', 'public');
        $announcement = Pengumuman::create([
            'judul' => 'Pengumuman Lama',
            'slug' => 'pengumuman-lama',
            'konten' => '<p>Isi.</p>',
            'cover_path' => $coverPath,
            'status' => 'draft',
        ]);

        $this->delete(route('admin.pengumuman.destroy', $announcement))
            ->assertRedirect(route('admin.pengumuman.index'));

        $this->assertDatabaseMissing('pengumumans', ['id' => $announcement->id]);
        Storage::disk('public')->assertMissing($coverPath);
    }

    public function test_create_form_loads_summernote_and_upload_controls(): void
    {
        $this->get(route('admin.pengumuman.create'))
            ->assertOk()
            ->assertSee('summernote-bs5.min.js', false)
            ->assertSee('name="cover"', false)
            ->assertSee('name="tags"', false)
            ->assertSee('upload-image', false);
    }
}
