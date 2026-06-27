<?php

use Database\Seeders\PengumumanHaviluddinSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SLUG = 'universitas-mulawarman-apresiasi-prestasi-prof-haviluddin-ilmuwan-top-dunia';

    public function up(): void
    {
        app(PengumumanHaviluddinSeeder::class)->run();
    }

    public function down(): void
    {
        DB::table('pengumumans')->where('slug', self::SLUG)->delete();
    }
};
