<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->string('sertifikat_path')->nullable()->after('piagam_path');
        });
    }

    public function down(): void
    {
        Schema::table('guru_besars', function (Blueprint $table) {
            $table->dropColumn('sertifikat_path');
        });
    }
};
