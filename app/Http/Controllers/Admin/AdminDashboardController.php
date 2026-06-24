<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Pengumuman;
use App\Models\Prodi;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orasi_ilmiah' => OrasiIlmiah::query()->count(),
            'guru_besar' => GuruBesar::query()->count(),
            'fakultas' => Fakultas::query()->count(),
            'prodi' => Prodi::query()->count(),
            'pengumuman' => Pengumuman::query()->count(),
            'published' => OrasiIlmiah::query()->where('status', 'published')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
