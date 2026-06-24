<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orasi extends Model
{
    protected $table = 'orasis';

    protected $fillable = [
        'urutan',
        'judul',
        'tanggal_pelaksanaan',
        'jenis',
        'pendaftaran_mulai',
        'pendaftaran_selesai',
        'youtube_url',
        'status',
        'pegawai_id',
        'pegawai_nama',
        'bidang_ilmu',
        'fakultas',
        'prodi',
        'tmt',
        'banner_path',
        'foto_path',
        'file_orasi_path',
        'ppt_path',
        'piagam_path',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'pendaftaran_mulai' => 'date',
        'pendaftaran_selesai' => 'date',
        'tmt' => 'date',
    ];
}

