<?php

namespace Database\Seeders;

use App\Models\GuruBesar;
use App\Support\GuruBesarGenderResolver;
use Illuminate\Database\Seeder;

class BackfillGuruBesarJenisKelaminSeeder extends Seeder
{
    public function run(): void
    {
        $resolver = new GuruBesarGenderResolver();

        GuruBesar::query()
            ->orderBy('id')
            ->get()
            ->each(function (GuruBesar $guruBesar) use ($resolver) {
                $jenisKelamin = $resolver->resolve($guruBesar->nama);

                if (! $jenisKelamin) {
                    return;
                }

                $guruBesar->forceFill(['jenis_kelamin' => $jenisKelamin])->save();
            });
    }
}
