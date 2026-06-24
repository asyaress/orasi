<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (is_file(database_path('dumps/manifest.json'))) {
            $this->call(ProductionSnapshotSeeder::class);

            return;
        }

        $this->command?->warn('Snapshot database/dumps/manifest.json tidak ditemukan. Fallback ke seeder legacy.');

        $seeders = [
            AdminUserSeeder::class,
        ];

        $legacySqlPath = dirname(base_path()).DIRECTORY_SEPARATOR.'orasi-ilmiah.sql';

        if (is_file($legacySqlPath)) {
            $seeders[] = LegacyOrasiSqlSeeder::class;
        } else {
            $seeders[] = DummyDataSeeder::class;
            $seeders[] = DemoGuruBesarSeeder::class;
        }

        $seeders[] = GuruBesarMediaSeeder::class;
        $seeders[] = BackfillGuruBesarJenisKelaminSeeder::class;

        $this->call($seeders);
    }
}
