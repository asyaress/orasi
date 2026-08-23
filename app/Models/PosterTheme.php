<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PosterTheme extends Model
{
    protected $fillable = [
        'year',
        'name',
        'frame_background',
        'frame_highlight',
        'footer_start',
        'footer_end',
        'text_color',
        'is_active',
    ];

    protected $casts = [
        'year' => 'integer',
        'is_active' => 'boolean',
    ];

    private const DEFAULT_PALETTES = [
        [
            'name' => 'Emas Rimba',
            'frame_background' => '#f9aa28',
            'frame_highlight' => '#32b70c',
            'footer_start' => '#287ed9',
            'footer_end' => '#08335f',
            'text_color' => '#ffffff',
        ],
        [
            'name' => 'Meranti Biru',
            'frame_background' => '#d9a441',
            'frame_highlight' => '#13745d',
            'footer_start' => '#1f6f8b',
            'footer_end' => '#12314f',
            'text_color' => '#ffffff',
        ],
        [
            'name' => 'Ulin Maroon',
            'frame_background' => '#e2b84d',
            'frame_highlight' => '#244f37',
            'footer_start' => '#8f2f45',
            'footer_end' => '#341928',
            'text_color' => '#ffffff',
        ],
        [
            'name' => 'Mahakam Senja',
            'frame_background' => '#f0bf5b',
            'frame_highlight' => '#1d7f86',
            'footer_start' => '#4659a8',
            'footer_end' => '#1f2a57',
            'text_color' => '#ffffff',
        ],
    ];

    /** @return array{name:string,frame_background:string,frame_highlight:string,footer_start:string,footer_end:string,text_color:string} */
    public static function defaultPalette(?int $year = null): array
    {
        if ($year === 2026) {
            return self::DEFAULT_PALETTES[0];
        }

        $index = $year ? abs($year) % count(self::DEFAULT_PALETTES) : 0;

        return self::DEFAULT_PALETTES[$index];
    }

    /**
     * @param  iterable<int|string|null>  $years
     * @return array<int, array{name:string,frame_background:string,frame_highlight:string,footer_start:string,footer_end:string,text_color:string}>
     */
    public static function paletteMapForYears(iterable $years): array
    {
        $yearValues = collect($years)
            ->filter(fn ($year) => filled($year) && is_numeric($year))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values();

        if (! Schema::hasTable('poster_themes')) {
            return $yearValues
                ->mapWithKeys(fn (int $year) => [$year => self::defaultPalette($year)])
                ->all();
        }

        $customThemes = self::query()
            ->where('is_active', true)
            ->whereIn('year', $yearValues)
            ->get()
            ->keyBy('year');

        return $yearValues
            ->mapWithKeys(function (int $year) use ($customThemes) {
                $theme = $customThemes->get($year);

                return [$year => $theme ? $theme->palette() : self::defaultPalette($year)];
            })
            ->all();
    }

    /** @return array{name:string,frame_background:string,frame_highlight:string,footer_start:string,footer_end:string,text_color:string} */
    public function palette(): array
    {
        return [
            'name' => $this->name ?: self::defaultPalette($this->year)['name'],
            'frame_background' => $this->frame_background ?: self::defaultPalette($this->year)['frame_background'],
            'frame_highlight' => $this->frame_highlight ?: self::defaultPalette($this->year)['frame_highlight'],
            'footer_start' => $this->footer_start ?: self::defaultPalette($this->year)['footer_start'],
            'footer_end' => $this->footer_end ?: self::defaultPalette($this->year)['footer_end'],
            'text_color' => $this->text_color ?: self::defaultPalette($this->year)['text_color'],
        ];
    }

    /** @param  array<string, string>  $palette */
    public static function cssVariables(array $palette): string
    {
        return collect([
            '--orasi-poster-frame' => $palette['frame_background'] ?? self::DEFAULT_PALETTES[0]['frame_background'],
            '--orasi-poster-highlight' => $palette['frame_highlight'] ?? self::DEFAULT_PALETTES[0]['frame_highlight'],
            '--orasi-poster-footer-start' => $palette['footer_start'] ?? self::DEFAULT_PALETTES[0]['footer_start'],
            '--orasi-poster-footer-end' => $palette['footer_end'] ?? self::DEFAULT_PALETTES[0]['footer_end'],
            '--orasi-poster-text' => $palette['text_color'] ?? self::DEFAULT_PALETTES[0]['text_color'],
        ])->map(fn (string $value, string $key) => "{$key}: {$value}")
            ->implode('; ');
    }
}
