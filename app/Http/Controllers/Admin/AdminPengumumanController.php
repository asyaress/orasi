<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertPengumumanRequest;
use App\Models\Pengumuman;
use App\Services\PengumumanContentSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPengumumanController extends Controller
{
    public function index()
    {
        $items = Pengumuman::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.pengumuman.index', compact('items'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(UpsertPengumumanRequest $request)
    {
        $data = $this->prepareData($request);

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', [
            'pengumuman' => $pengumuman,
        ]);
    }

    public function update(UpsertPengumumanRequest $request, Pengumuman $pengumuman)
    {
        $data = $this->prepareData($request, $pengumuman);

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if (filled($pengumuman->cover_path)) {
            Storage::disk('public')->delete($pengumuman->cover_path);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $path = $validated['image']->store('pengumuman/content', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path),
            'path' => $path,
        ]);
    }

    private function prepareData(UpsertPengumumanRequest $request, ?Pengumuman $pengumuman = null): array
    {
        $validated = $request->validated();
        $slug = filled($validated['slug'] ?? null)
            ? $validated['slug']
            : $this->uniqueSlug($validated['judul'], $pengumuman?->id);

        $tags = collect(explode(',', (string) ($validated['tags'] ?? '')))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->unique(fn (string $tag): string => mb_strtolower($tag))
            ->take(10)
            ->values()
            ->all();

        $data = [
            'judul' => $validated['judul'],
            'slug' => $slug,
            'ringkasan' => filled($validated['ringkasan'] ?? null) ? $validated['ringkasan'] : null,
            'konten' => app(PengumumanContentSanitizer::class)->sanitize($validated['konten']),
            'tags' => $tags ?: null,
            'status' => $validated['status'],
            'is_pinned' => $request->boolean('is_pinned'),
            'published_at' => $validated['published_at'] ?? null,
        ];

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->boolean('remove_cover') && filled($pengumuman?->cover_path)) {
            Storage::disk('public')->delete($pengumuman->cover_path);
            $data['cover_path'] = null;
        }

        if ($request->hasFile('cover')) {
            if (filled($pengumuman?->cover_path)) {
                Storage::disk('public')->delete($pengumuman->cover_path);
            }

            $data['cover_path'] = $request->file('cover')->store('pengumuman/covers', 'public');
        }

        return $data;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'pengumuman';
        $slug = $base;
        $counter = 2;

        while (Pengumuman::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
