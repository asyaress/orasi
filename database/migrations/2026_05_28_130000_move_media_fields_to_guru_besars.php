<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->string('youtube_url', 500)->nullable()->after('foto_path');
            $table->string('file_orasi_path')->nullable()->after('youtube_url');
            $table->string('ppt_path')->nullable()->after('file_orasi_path');
            $table->string('piagam_path')->nullable()->after('ppt_path');
        });

        Schema::table('orasi_ilmiahs', function (Blueprint $table) {
            $table->dropColumn(['youtube_url', 'file_orasi_path', 'ppt_path', 'piagam_path']);
        });
    }

    public function down(): void
    {
        Schema::table('orasi_ilmiahs', function (Blueprint $table) {
            $table->string('youtube_url', 500)->nullable();
            $table->string('file_orasi_path')->nullable();
            $table->string('ppt_path')->nullable();
            $table->string('piagam_path')->nullable();
        });

        Schema::table('guru_besars', function (Blueprint $table) {
            $table->dropColumn(['youtube_url', 'file_orasi_path', 'ppt_path', 'piagam_path']);
        });
    }
};
