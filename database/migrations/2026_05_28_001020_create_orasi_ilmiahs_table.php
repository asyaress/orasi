<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orasi_ilmiahs', function (Blueprint $table) {
            $table->id();

            $table->string('judul')->index();
            $table->date('tanggal_pelaksanaan')->index();
            $table->string('jenis', 16)->default('Luring')->index(); // Luring|Daring

            $table->date('pendaftaran_mulai')->nullable()->index();
            $table->date('pendaftaran_selesai')->nullable()->index();

            $table->string('status', 16)->default('draft')->index(); // draft|published|archived

            $table->string('banner_path')->nullable();
            $table->string('youtube_url', 500)->nullable();
            $table->string('file_orasi_path')->nullable();
            $table->string('ppt_path')->nullable();
            $table->string('piagam_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orasi_ilmiahs');
    }
};

