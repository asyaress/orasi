<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orasi_ilmiahs', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->nullable()->after('judul')->index();
        });

        DB::table('orasi_ilmiahs')->orderBy('id')->each(function ($row) {
            if ($row->tanggal_pelaksanaan) {
                DB::table('orasi_ilmiahs')->where('id', $row->id)->update([
                    'tahun' => (int) date('Y', strtotime($row->tanggal_pelaksanaan)),
                ]);
            }
        });

        Schema::table('orasi_ilmiahs', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->nullable(false)->change();
            $table->unique('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('orasi_ilmiahs', function (Blueprint $table) {
            $table->dropUnique(['tahun']);
            $table->dropColumn('tahun');
        });
    }
};
