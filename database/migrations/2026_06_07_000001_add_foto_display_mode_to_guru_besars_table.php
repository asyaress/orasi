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
            $table->string('foto_display_mode', 32)
                ->default('svg_bg_photo')
                ->after('foto_path');
        });

        DB::table('guru_besars')
            ->whereNull('foto_display_mode')
            ->update(['foto_display_mode' => 'svg_bg_photo']);
    }

    public function down(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->dropColumn('foto_display_mode');
        });
    }
};
