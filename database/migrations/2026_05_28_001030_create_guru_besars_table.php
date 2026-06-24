<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_besars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orasi_ilmiah_id')->constrained('orasi_ilmiahs')->cascadeOnDelete();

            // API reference + snapshot/manual fallback
            $table->string('pegawai_id', 64)->nullable()->index();
            $table->string('nama')->nullable()->index();
            $table->string('bidang_ilmu')->nullable();
            $table->date('tmt')->nullable();
            $table->string('foto_path')->nullable();

            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->string('fakultas_snapshot')->nullable();
            $table->string('prodi_snapshot')->nullable();

            $table->timestamps();

            $table->unique(['orasi_ilmiah_id', 'pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_besars');
    }
};

