<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class DemoGuruBesarSeeder extends Seeder
{
    public function run(): void
    {
        $orasiMap = $this->ensureOrasiMap();
        $facultyConfigs = $this->facultyConfigs();
        $namesByYear = $this->namesByYear();

        foreach ($namesByYear as $year => $names) {
            foreach ($names as $index => $name) {
                $facultyConfig = $facultyConfigs[($index + ($year % count($facultyConfigs))) % count($facultyConfigs)];
                $fakultas = Fakultas::query()->where('kode', $facultyConfig['kode'])->first();
                $prodi = $this->findProdi($fakultas, $facultyConfig['prodi']);

                $sequence = $index + 1;

                GuruBesar::query()->updateOrCreate(
                    ['pegawai_id' => sprintf('DEMO-%d-%02d', $year, $sequence)],
                    [
                        'orasi_ilmiah_id' => $orasiMap[$year]->id,
                        'sumber' => GuruBesar::SUMBER_MANUAL,
                        'nama' => $name,
                        'bidang_ilmu' => $facultyConfig['bidang'][$index % count($facultyConfig['bidang'])],
                        'tmt' => sprintf('%d-%02d-%02d', max(2013, $year - 8), (($sequence + 2) % 12) + 1, (($sequence * 2) % 27) + 1),
                        'fakultas_id' => $fakultas?->id,
                        'prodi_id' => $prodi?->id,
                        'fakultas_snapshot' => $fakultas?->nama,
                        'prodi_snapshot' => $prodi?->nama ?? $facultyConfig['prodi'],
                        'youtube_url' => $sequence <= 3
                            ? 'https://www.youtube.com/watch?v=S_9XNYv6RZo'
                            : null,
                    ]
                );
            }
        }
    }

    /** @return array<int, OrasiIlmiah> */
    private function ensureOrasiMap(): array
    {
        $defaults = [
            2023 => ['status' => 'archived', 'jenis' => 'Luring', 'tanggal' => '2023-11-20'],
            2024 => ['status' => 'archived', 'jenis' => 'Luring', 'tanggal' => '2024-11-18'],
            2025 => ['status' => 'published', 'jenis' => 'Luring', 'tanggal' => '2025-11-15'],
        ];

        $map = [];

        foreach ($defaults as $year => $config) {
            $map[$year] = OrasiIlmiah::query()->updateOrCreate(
                ['tahun' => $year],
                [
                    'judul' => 'Orasi Guru Besar Universitas Mulawarman',
                    'tahun' => $year,
                    'tanggal_pelaksanaan' => $config['tanggal'],
                    'jenis' => $config['jenis'],
                    'status' => $config['status'],
                ]
            );
        }

        return $map;
    }

    /** @return array<int, array{kode:string,prodi:string,bidang:array<int,string>}> */
    private function facultyConfigs(): array
    {
        return [
            [
                'kode' => 'FT',
                'prodi' => 'Teknik Informatika',
                'bidang' => [
                    'Sistem Cerdas dan Rekayasa Perangkat Lunak',
                    'Komputasi Awan dan Keamanan Siber',
                    'Data Science dan Sistem Informasi',
                ],
            ],
            [
                'kode' => 'FKIP',
                'prodi' => 'Pendidikan Bahasa Inggris',
                'bidang' => [
                    'Pendidikan Bahasa Inggris dan Kurikulum',
                    'Teknologi Pembelajaran Bahasa',
                    'Literasi Akademik dan Evaluasi Pendidikan',
                ],
            ],
            [
                'kode' => 'FMIPA',
                'prodi' => 'Matematika',
                'bidang' => [
                    'Matematika Terapan dan Pemodelan',
                    'Statistika Komputasi dan Analitika',
                    'Aljabar dan Optimisasi',
                ],
            ],
            [
                'kode' => 'FH',
                'prodi' => 'Hukum',
                'bidang' => [
                    'Hukum Tata Negara dan Kebijakan Publik',
                    'Hukum Perdata dan Reformasi Regulasi',
                    'Hukum Administrasi Negara',
                ],
            ],
            [
                'kode' => 'FEB',
                'prodi' => 'Manajemen',
                'bidang' => [
                    'Manajemen Strategik dan Inovasi',
                    'Keuangan Korporasi dan Tata Kelola',
                    'Pemasaran Digital dan Perilaku Konsumen',
                ],
            ],
        ];
    }

    /** @return array<int, array<int, string>> */
    private function namesByYear(): array
    {
        return [
            2023 => [
                'Prof. Dr. Ir. Aditya Nugraha, M.T.',
                'Prof. Dr. Maya Kartikasari, M.Pd.',
                'Prof. Dr. Rahmat Hidayat, M.Si.',
                'Prof. Dr. Lestari Wulandari, S.H., M.H.',
                'Prof. Dr. Budi Santoso, M.M.',
                'Prof. Dr. Nuraini Azizah, M.Pd.',
                'Prof. Dr. Hendra Saputra, M.Si.',
                'Prof. Dr. Intan Permata, S.H., M.H.',
                'Prof. Dr. Yulianto Prabowo, M.T.',
                'Prof. Dr. Siska Maharani, M.M.',
            ],
            2024 => [
                'Prof. Dr. Rina Setyawati, M.Pd.',
                'Prof. Dr. Doni Firmansyah, M.T.',
                'Prof. Dr. Arif Maulana, M.Si.',
                'Prof. Dr. Nabila Khairunnisa, M.H.',
                'Prof. Dr. Taufik Akbar, M.M.',
                'Prof. Dr. Wening Kusuma, M.Pd.',
                'Prof. Dr. Guntur Prawira, M.T.',
                'Prof. Dr. Ayu Laksmi, M.Si.',
                'Prof. Dr. Candra Wibisono, S.H., M.H.',
                'Prof. Dr. Desi Novitasari, M.M.',
            ],
            2025 => [
                'Prof. Dr. Farhan Ramadhan, M.T.',
                'Prof. Dr. Suci Ramadhani, M.Pd.',
                'Prof. Dr. Galih Prasetya, M.Si.',
                'Prof. Dr. Tiara Anindita, S.H., M.H.',
                'Prof. Dr. Reza Mahendra, M.M.',
                'Prof. Dr. Nadia Paramitha, M.Pd.',
                'Prof. Dr. Yoga Saputro, M.T.',
                'Prof. Dr. Bela Maharani, M.Si.',
                'Prof. Dr. Ilham Kurniawan, S.H., M.H.',
                'Prof. Dr. Karin Aulia, M.M.',
            ],
        ];
    }

    private function findProdi(?Fakultas $fakultas, string $prodiName): ?Prodi
    {
        if (! $fakultas) {
            return null;
        }

        return Prodi::query()
            ->where('fakultas_id', $fakultas->id)
            ->where('nama', $prodiName)
            ->first();
    }
}
