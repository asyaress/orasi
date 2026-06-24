<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            AdminUserSeeder::class,
        ];

        $legacySqlPath = dirname(base_path()) . DIRECTORY_SEPARATOR . 'orasi-ilmiah.sql';

        if (is_file($legacySqlPath)) {
            $seeders[] = LegacyOrasiSqlSeeder::class;
        } else {
            $seeders[] = DummyDataSeeder::class;
            $seeders[] = DemoGuruBesarSeeder::class;
        }

        $seeders[] = GuruBesarMediaSeeder::class;

        $this->call($seeders);
    }
}
