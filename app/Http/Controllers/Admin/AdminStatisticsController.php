<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminStatisticsController extends Controller
{
    public function index()
    {
        $year = (int) request()->query('year', (int) now()->format('Y'));

        $totalOrasi = Orasi::query()->count();
        $totalGuruBesar = Orasi::query()
            ->whereNotNull('pegawai_id')
            ->distinct('pegawai_id')
            ->count('pegawai_id');
        $fakultasTerlibat = Orasi::query()
            ->whereNotNull('fakultas')
            ->distinct('fakultas')
            ->count('fakultas');
        $orasiTahunBerjalan = Orasi::query()
            ->whereYear('tanggal_pelaksanaan', $year)
            ->count();

        $perBulan = Orasi::query()
            ->selectRaw('MONTH(tanggal_pelaksanaan) as m, COUNT(*) as total')
            ->whereYear('tanggal_pelaksanaan', $year)
            ->groupBy(DB::raw('MONTH(tanggal_pelaksanaan)'))
            ->orderBy(DB::raw('MONTH(tanggal_pelaksanaan)'))
            ->pluck('total', 'm')
            ->all();

        $labels = [];
        $series = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::createFromDate($year, $m, 1)->isoFormat('MMM');
            $series[] = (int) ($perBulan[$m] ?? 0);
        }

        $perFakultas = Orasi::query()
            ->selectRaw('fakultas as label, COUNT(*) as total')
            ->whereNotNull('fakultas')
            ->whereYear('tanggal_pelaksanaan', $year)
            ->groupBy('fakultas')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.statistics.index', [
            'year' => $year,
            'kpis' => [
                'total_orasi' => $totalOrasi,
                'total_guru_besar' => $totalGuruBesar,
                'fakultas_terlibat' => $fakultasTerlibat,
                'orasi_tahun_berjalan' => $orasiTahunBerjalan,
            ],
            'trend' => [
                'labels' => $labels,
                'series' => $series,
            ],
            'perFakultas' => $perFakultas,
        ]);
    }
}

