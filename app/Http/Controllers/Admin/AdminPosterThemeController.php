<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrasiIlmiah;
use App\Models\PosterTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminPosterThemeController extends Controller
{
    public function index()
    {
        $years = OrasiIlmiah::query()
            ->whereNotNull('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn ($year) => (int) $year)
            ->merge([(int) date('Y')])
            ->unique()
            ->sortDesc()
            ->values();

        $themes = Schema::hasTable('poster_themes')
            ? PosterTheme::query()
                ->whereIn('year', $years)
                ->get()
                ->keyBy('year')
            : collect();

        $themeRows = $years->map(function (int $year) use ($themes) {
            $theme = $themes->get($year);

            return [
                'year' => $year,
                'theme' => $theme,
                'palette' => $theme ? $theme->palette() : PosterTheme::defaultPalette($year),
            ];
        });

        return view('admin.poster-themes.index', compact('themeRows'));
    }

    public function update(Request $request, int $year)
    {
        if (! Schema::hasTable('poster_themes')) {
            return redirect()
                ->route('admin.poster-themes.index')
                ->with('warning', 'Tabel tema poster belum tersedia. Jalankan php artisan migrate --force di server.');
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:80'],
            'frame_background' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'frame_highlight' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'footer_start' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'footer_end' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        PosterTheme::updateOrCreate(
            ['year' => $year],
            $data + ['year' => $year]
        );

        return redirect()
            ->route('admin.poster-themes.index')
            ->with('success', "Tema poster {$year} berhasil disimpan.");
    }
}
