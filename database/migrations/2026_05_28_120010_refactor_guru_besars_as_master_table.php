<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->dropForeign(['orasi_ilmiah_id']);
            $table->dropUnique(['orasi_ilmiah_id', 'pegawai_id']);
        });

        Schema::table('guru_besars', function (Blueprint $table) {
            $table->unsignedBigInteger('orasi_ilmiah_id')->nullable()->change();
            $table->string('sumber', 16)->default('manual')->after('pegawai_id'); // manual|api
            $table->foreign('orasi_ilmiah_id')->references('id')->on('orasi_ilmiahs')->nullOnDelete();
            $table->unique('pegawai_id');
        });
    }

    public function down(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->dropForeign(['orasi_ilmiah_id']);
            $table->dropUnique(['pegawai_id']);
            $table->dropColumn('sumber');
        });

        Schema::table('guru_besars', function (Blueprint $table) {
            $table->unsignedBigInteger('orasi_ilmiah_id')->nullable(false)->change();
            $table->foreign('orasi_ilmiah_id')->references('id')->on('orasi_ilmiahs')->cascadeOnDelete();
            $table->unique(['orasi_ilmiah_id', 'pegawai_id']);
        });
    }
};
