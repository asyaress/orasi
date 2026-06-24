<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orasis', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('urutan')->nullable()->index();

            // Snapshot/fallback dari API kepegawaian (atau input manual)
            $table->string('pegawai_id', 64)->nullable()->index();
            $table->string('pegawai_nama')->nullable()->index();
            $table->string('bidang_ilmu')->nullable();
            $table->string('fakultas')->nullable()->index();
            $table->string('prodi')->nullable();
            $table->date('tmt')->nullable();

            $table->string('judul')->index();
            $table->date('tanggal_pelaksanaan')->index();
            $table->string('jenis', 16)->default('Luring')->index(); // Luring|Daring

            $table->date('pendaftaran_mulai')->nullable()->index();
            $table->date('pendaftaran_selesai')->nullable()->index();

            $table->string('youtube_url', 500)->nullable();

            // Media uploads (storage public)
            $table->string('banner_path')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('file_orasi_path')->nullable();
            $table->string('ppt_path')->nullable();
            $table->string('piagam_path')->nullable();

            $table->string('status', 16)->default('draft')->index(); // draft|published|archived

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orasis');
    }
};

