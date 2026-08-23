<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poster_themes', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->string('name')->nullable();
            $table->string('frame_background', 7)->default('#f9aa28');
            $table->string('frame_highlight', 7)->default('#32b70c');
            $table->string('footer_start', 7)->default('#287ed9');
            $table->string('footer_end', 7)->default('#08335f');
            $table->string('text_color', 7)->default('#ffffff');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poster_themes');
    }
};
