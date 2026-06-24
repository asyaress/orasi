<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->constrained('fakultas')->restrictOnDelete();
            $table->string('kode', 20)->nullable();
            $table->string('nama')->index();
            $table->string('slug')->unique();
            $table->string('jenjang', 10)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['fakultas_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};

