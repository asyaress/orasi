<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GuruBesar extends Model
{
    public const SUMBER_MANUAL = 'manual';

    public const SUMBER_API = 'api';

    public const FOTO_DISPLAY_MODE_SVG_BG_PHOTO = 'svg_bg_photo';

    public const FOTO_DISPLAY_MODE_PNG_FULL_OVERLAY = 'png_full_overlay';

    public const JENIS_KELAMIN_LAKI_LAKI = 'laki-laki';

    public const JENIS_KELAMIN_PEREMPUAN = 'perempuan';

    /** @return array<string, string> */
    public static function jenisKelaminOptions(): array
    {
        return [
            self::JENIS_KELAMIN_LAKI_LAKI => 'Laki-laki',
            self::JENIS_KELAMIN_PEREMPUAN => 'Perempuan',
        ];
    }

    protected $table = 'guru_besars';

    protected $fillable = [
        'orasi_ilmiah_id',
        'pegawai_id',
        'sumber',
        'nama',
        'jenis_kelamin',
        'bidang_ilmu',
        'judul_orasi',
        'tmt',
        'foto_path',
        'foto_display_mode',
        'youtube_url',
        'file_orasi_path',
        'ppt_path',
        'piagam_path',
        'sertifikat_path',
        'fakultas_id',
        'prodi_id',
        'fakultas_snapshot',
        'prodi_snapshot',
    ];

    protected $casts = [
        'tmt' => 'date',
    ];

    public function orasiIlmiah(): BelongsTo
    {
        return $this->belongsTo(OrasiIlmiah::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function scopeBelumDitugaskan(Builder $query): Builder
    {
        return $query->whereNull('orasi_ilmiah_id');
    }

    public function scopeUntukOrasi(Builder $query, int $orasiIlmiahId): Builder
    {
        return $query->where('orasi_ilmiah_id', $orasiIlmiahId);
    }

    public function isBelumDitugaskan(): bool
    {
        return $this->orasi_ilmiah_id === null;
    }

    public function displayFakultas(): string
    {
        return $this->fakultas_snapshot ?: ($this->fakultas?->nama ?: '-');
    }

    public function displayProdi(): string
    {
        if (filled($this->prodi_snapshot)) {
            return $this->prodi_snapshot;
        }

        if ($this->prodi?->nama) {
            $jenjang = filled($this->prodi->jenjang) ? trim($this->prodi->jenjang) . ' ' : '';

            return trim($jenjang . $this->prodi->nama);
        }

        return '-';
    }

    public function archiveYear(): ?int
    {
        if ($this->orasiIlmiah?->tahun) {
            return (int) $this->orasiIlmiah->tahun;
        }

        $year = $this->tmt?->year;

        if (in_array($year, [2019, 2020, 2021, 2022, 2023], true)) {
            return 2023;
        }

        return $year;
    }

    public function storageYear(): ?int
    {
        $orasiYear = $this->orasi_ilmiah_id
            ? OrasiIlmiah::query()->find($this->orasi_ilmiah_id)?->tahun
            : null;

        return $orasiYear ?: $this->tmt?->year;
    }

    public function storageFolderPath(): string
    {
        $year = $this->storageYear() ?: 'tanpa-tahun';
        $slug = Str::slug($this->nama) ?: 'guru-besar';

        return "guru-besar/{$year}/{$slug}";
    }

    public function packageFolderName(): string
    {
        $year = $this->storageYear() ?: 'tanpa-tahun';
        $slug = Str::slug($this->nama) ?: 'guru-besar';

        return "{$year}-{$slug}";
    }

    public function hasVideo(): bool
    {
        return filled($this->youtube_url);
    }

    public function hasFileOrasi(): bool
    {
        return filled($this->file_orasi_path);
    }

    public function hasPpt(): bool
    {
        return filled($this->ppt_path);
    }

    public function hasPiagam(): bool
    {
        return filled($this->piagam_path);
    }

    public function hasSertifikat(): bool
    {
        return filled($this->sertifikat_path);
    }

    public function hasDownloadablePackage(): bool
    {
        return filled($this->foto_path)
            || filled($this->file_orasi_path)
            || filled($this->ppt_path)
            || filled($this->piagam_path)
            || filled($this->sertifikat_path);
    }

    public function fotoDisplayMode(): string
    {
        return $this->foto_display_mode ?: self::FOTO_DISPLAY_MODE_SVG_BG_PHOTO;
    }

    public function usesFullPngOverlay(): bool
    {
        return $this->fotoDisplayMode() === self::FOTO_DISPLAY_MODE_PNG_FULL_OVERLAY;
    }

    public function displayJenisKelamin(): string
    {
        return self::jenisKelaminOptions()[$this->jenis_kelamin] ?? '-';
    }
}
