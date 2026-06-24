<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrasiIlmiah extends Model
{
    protected $table = 'orasi_ilmiahs';

    protected $fillable = [
        'judul',
        'tahun',
        'tanggal_pelaksanaan',
        'jenis',
        'pendaftaran_mulai',
        'pendaftaran_selesai',
        'status',
        'banner_path',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tanggal_pelaksanaan' => 'date',
        'pendaftaran_mulai' => 'date',
        'pendaftaran_selesai' => 'date',
    ];

    public function guruBesars(): HasMany
    {
        return $this->hasMany(GuruBesar::class);
    }

    public function getJudulLengkapAttribute(): string
    {
        if ($this->tahun && ! str_contains($this->judul, (string) $this->tahun)) {
            return "Orasi Ilmiah {$this->tahun} - {$this->judul}";
        }

        return $this->judul;
    }
}
