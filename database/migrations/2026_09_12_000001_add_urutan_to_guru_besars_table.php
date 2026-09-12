<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->unsignedInteger('urutan')->nullable()->after('orasi_ilmiah_id');
            $table->index(['orasi_ilmiah_id', 'urutan']);
        });

        // Pertahankan urutan home yang sudah berlaku (TMT terlama lebih dulu)
        // sebagai urutan awal di admin untuk setiap tahun/orasi.
        DB::table('guru_besars')
            ->whereNotNull('orasi_ilmiah_id')
            ->orderBy('orasi_ilmiah_id')
            ->orderByRaw('CASE WHEN tmt IS NULL THEN 1 ELSE 0 END')
            ->orderBy('tmt')
            ->orderBy('nama')
            ->orderBy('id')
            ->get(['id', 'orasi_ilmiah_id'])
            ->groupBy('orasi_ilmiah_id')
            ->each(function ($guruBesars): void {
                foreach ($guruBesars->values() as $index => $guruBesar) {
                    DB::table('guru_besars')
                        ->where('id', $guruBesar->id)
                        ->update(['urutan' => $index + 1]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->dropIndex(['orasi_ilmiah_id', 'urutan']);
            $table->dropColumn('urutan');
        });
    }
};
