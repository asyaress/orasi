<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\GuruBesar;
use App\Models\OrasiIlmiah;
use App\Models\Pengumuman;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasMap = $this->seedFakultasAndProdi();
        $orasiMap = $this->seedOrasiIlmiah();
        $this->seedGuruBesar($fakultasMap, $orasiMap);
        $this->seedPengumuman();
    }

    /** @return array<string, Fakultas> */
    private function seedFakultasAndProdi(): array
    {
        $data = [
            'ft' => [
                'kode' => 'FT',
                'nama' => 'Fakultas Teknik',
                'prodis' => [
                    ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1'],
                    ['kode' => 'TS', 'nama' => 'Teknik Sipil', 'jenjang' => 'S1'],
                    ['kode' => 'TM', 'nama' => 'Teknik Mesin', 'jenjang' => 'S1'],
                ],
            ],
            'fkip' => [
                'kode' => 'FKIP',
                'nama' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'prodis' => [
                    ['kode' => 'PBI', 'nama' => 'Pendidikan Bahasa Inggris', 'jenjang' => 'S1'],
                    ['kode' => 'PMAT', 'nama' => 'Pendidikan Matematika', 'jenjang' => 'S1'],
                ],
            ],
            'fmipa' => [
                'kode' => 'FMIPA',
                'nama' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'prodis' => [
                    ['kode' => 'MAT', 'nama' => 'Matematika', 'jenjang' => 'S1'],
                    ['kode' => 'BIO', 'nama' => 'Biologi', 'jenjang' => 'S1'],
                ],
            ],
            'fh' => [
                'kode' => 'FH',
                'nama' => 'Fakultas Hukum',
                'prodis' => [
                    ['kode' => 'HK', 'nama' => 'Hukum', 'jenjang' => 'S1'],
                ],
            ],
            'feb' => [
                'kode' => 'FEB',
                'nama' => 'Fakultas Ekonomi dan Bisnis',
                'prodis' => [
                    ['kode' => 'MAN', 'nama' => 'Manajemen', 'jenjang' => 'S1'],
                    ['kode' => 'AK', 'nama' => 'Akuntansi', 'jenjang' => 'S1'],
                ],
            ],
        ];

        $map = [];

        foreach ($data as $key => $row) {
            $fakultas = Fakultas::query()->updateOrCreate(
                ['slug' => Str::slug($row['nama'])],
                [
                    'kode' => $row['kode'],
                    'nama' => $row['nama'],
                    'is_active' => true,
                ]
            );

            foreach ($row['prodis'] as $prodiRow) {
                Prodi::query()->updateOrCreate(
                    [
                        'fakultas_id' => $fakultas->id,
                        'nama' => $prodiRow['nama'],
                    ],
                    [
                        'kode' => $prodiRow['kode'],
                        'slug' => Str::slug($fakultas->kode.'-'.$prodiRow['kode']),
                        'jenjang' => $prodiRow['jenjang'],
                        'is_active' => true,
                    ]
                );
            }

            $map[$key] = $fakultas;
        }

        return $map;
    }

    /** @return array<int, OrasiIlmiah> */
    private function seedOrasiIlmiah(): array
    {
        $events = [
            2023 => [
                'judul' => 'Orasi Guru Besar Universitas Mulawarman',
                'tanggal_pelaksanaan' => '2023-11-20',
                'jenis' => 'Luring',
                'status' => 'archived',
            ],
            2024 => [
                'judul' => 'Orasi Guru Besar Universitas Mulawarman',
                'tanggal_pelaksanaan' => '2024-11-18',
                'jenis' => 'Luring',
                'status' => 'archived',
            ],
            2025 => [
                'judul' => 'Orasi Guru Besar Universitas Mulawarman',
                'tanggal_pelaksanaan' => '2025-11-15',
                'jenis' => 'Luring',
                'status' => 'published',
            ],
            2026 => [
                'judul' => 'Orasi Guru Besar Universitas Mulawarman',
                'tanggal_pelaksanaan' => '2026-11-14',
                'jenis' => 'Daring',
                'status' => 'draft',
                'pendaftaran_mulai' => '2026-08-01',
                'pendaftaran_selesai' => '2026-10-31',
            ],
        ];

        $map = [];

        foreach ($events as $tahun => $attrs) {
            $map[$tahun] = OrasiIlmiah::query()->updateOrCreate(
                ['tahun' => $tahun],
                array_merge($attrs, [
                    'tahun' => $tahun,
                    'pendaftaran_mulai' => $attrs['pendaftaran_mulai'] ?? null,
                    'pendaftaran_selesai' => $attrs['pendaftaran_selesai'] ?? null,
                ])
            );
        }

        return $map;
    }

    /**
     * @param  array<string, Fakultas>  $fakultasMap
     * @param  array<int, OrasiIlmiah>  $orasiMap
     */
    private function seedGuruBesar(array $fakultasMap, array $orasiMap): void
    {
        $ft = $fakultasMap['ft'];
        $fkip = $fakultasMap['fkip'];
        $fmipa = $fakultasMap['fmipa'];
        $fh = $fakultasMap['fh'];
        $feb = $fakultasMap['feb'];

        $ti = Prodi::query()->where('fakultas_id', $ft->id)->where('nama', 'Teknik Informatika')->first();
        $ts = Prodi::query()->where('fakultas_id', $ft->id)->where('nama', 'Teknik Sipil')->first();
        $pbi = Prodi::query()->where('fakultas_id', $fkip->id)->where('nama', 'Pendidikan Bahasa Inggris')->first();
        $mat = Prodi::query()->where('fakultas_id', $fmipa->id)->where('nama', 'Matematika')->first();
        $hk = Prodi::query()->where('fakultas_id', $fh->id)->where('nama', 'Hukum')->first();
        $man = Prodi::query()->where('fakultas_id', $feb->id)->where('nama', 'Manajemen')->first();

        $gurus = [
            [
                'pegawai_id' => '197503152005011001',
                'sumber' => GuruBesar::SUMBER_API,
                'nama' => 'Prof. Dr. Ir. Ahmad Wijaya, M.T.',
                'bidang_ilmu' => 'Teknik Informatika & Sistem Terdistribusi',
                'tmt' => '2018-04-01',
                'fakultas_id' => $ft->id,
                'prodi_id' => $ti?->id,
                'orasi_tahun' => 2025,
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ],
            [
                'pegawai_id' => '196812101990031002',
                'sumber' => GuruBesar::SUMBER_API,
                'nama' => 'Prof. Dr. Siti Rahmawati, M.Pd.',
                'bidang_ilmu' => 'Pendidikan Bahasa Inggris',
                'tmt' => '2015-09-01',
                'fakultas_id' => $fkip->id,
                'prodi_id' => $pbi?->id,
                'orasi_tahun' => 2025,
            ],
            [
                'pegawai_id' => '197201011998021003',
                'sumber' => GuruBesar::SUMBER_API,
                'nama' => 'Prof. Dr. Bambang Susilo, M.Si.',
                'bidang_ilmu' => 'Matematika Terapan',
                'tmt' => '2016-03-15',
                'fakultas_id' => $fmipa->id,
                'prodi_id' => $mat?->id,
                'orasi_tahun' => 2025,
            ],
            [
                'pegawai_id' => '197805202010011004',
                'sumber' => GuruBesar::SUMBER_API,
                'nama' => 'Prof. Dr. Dewi Lestari, S.H., M.H.',
                'bidang_ilmu' => 'Hukum Tata Negara',
                'tmt' => '2019-01-10',
                'fakultas_id' => $fh->id,
                'prodi_id' => $hk?->id,
                'orasi_tahun' => 2024,
            ],
            [
                'pegawai_id' => '198003122012011005',
                'sumber' => GuruBesar::SUMBER_API,
                'nama' => 'Prof. Dr. Eko Prasetyo, M.M.',
                'bidang_ilmu' => 'Manajemen Strategik',
                'tmt' => '2020-07-01',
                'fakultas_id' => $feb->id,
                'prodi_id' => $man?->id,
                'orasi_tahun' => 2024,
            ],
            [
                'pegawai_id' => '197604081999031006',
                'sumber' => GuruBesar::SUMBER_API,
                'nama' => 'Prof. Dr. Ir. Hendra Gunawan, M.T.',
                'bidang_ilmu' => 'Teknik Sipil & Struktur',
                'tmt' => '2014-11-20',
                'fakultas_id' => $ft->id,
                'prodi_id' => $ts?->id,
                'orasi_tahun' => 2023,
            ],
            [
                'pegawai_id' => '198509152018011007',
                'sumber' => GuruBesar::SUMBER_MANUAL,
                'nama' => 'Prof. Dr. Fitriani, M.Kom.',
                'bidang_ilmu' => 'Kecerdasan Buatan',
                'tmt' => '2022-05-01',
                'fakultas_id' => $ft->id,
                'prodi_id' => $ti?->id,
                'orasi_tahun' => null,
            ],
            [
                'pegawai_id' => '198712202020011008',
                'sumber' => GuruBesar::SUMBER_MANUAL,
                'nama' => 'Prof. Dr. Rizky Maulana, M.Si.',
                'bidang_ilmu' => 'Statistika & Analisis Data',
                'tmt' => '2023-08-15',
                'fakultas_id' => $fmipa->id,
                'prodi_id' => $mat?->id,
                'orasi_tahun' => null,
            ],
            [
                'pegawai_id' => null,
                'sumber' => GuruBesar::SUMBER_MANUAL,
                'nama' => 'Prof. Dr. M. Yusuf (Input Manual)',
                'bidang_ilmu' => 'Ekonomi Pembangunan',
                'tmt' => null,
                'fakultas_snapshot' => 'Fakultas Ekonomi dan Bisnis',
                'prodi_snapshot' => 'Ekonomi Pembangunan',
                'orasi_tahun' => null,
            ],
        ];

        foreach ($gurus as $row) {
            $orasiId = isset($row['orasi_tahun'], $orasiMap[$row['orasi_tahun']])
                ? $orasiMap[$row['orasi_tahun']]->id
                : null;

            $attrs = [
                'sumber' => $row['sumber'],
                'nama' => $row['nama'],
                'bidang_ilmu' => $row['bidang_ilmu'],
                'tmt' => $row['tmt'],
                'fakultas_id' => $row['fakultas_id'] ?? null,
                'prodi_id' => $row['prodi_id'] ?? null,
                'fakultas_snapshot' => $row['fakultas_snapshot'] ?? null,
                'prodi_snapshot' => $row['prodi_snapshot'] ?? null,
                'orasi_ilmiah_id' => $orasiId,
                'youtube_url' => $row['youtube_url'] ?? null,
            ];

            if (! empty($row['pegawai_id'])) {
                GuruBesar::query()->updateOrCreate(
                    ['pegawai_id' => $row['pegawai_id']],
                    $attrs
                );
            } else {
                GuruBesar::query()->updateOrCreate(
                    ['nama' => $row['nama'], 'pegawai_id' => null],
                    $attrs
                );
            }
        }
    }

    private function seedPengumuman(): void
    {
        $items = [
            [
                'judul' => 'Pendaftaran Orasi Guru Besar Tahun 2026 Dibuka',
                'slug' => 'pendaftaran-orasi-guru-besar-2026',
                'ringkasan' => 'Pendaftaran orasi ilmiah bagi guru besar Universitas Mulawarman periode 2026 telah dibuka.',
                'konten' => '<p>Seluruh guru besar yang akan mengikuti orasi ilmiah tahun 2026 diharapkan melengkapi berkas sesuai jadwal yang ditetapkan.</p>',
                'status' => 'published',
                'is_pinned' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'judul' => 'Jadwal Pelaksanaan Orasi Ilmiah 2025',
                'slug' => 'jadwal-orasi-ilmiah-2025',
                'ringkasan' => 'Informasi jadwal pelaksanaan orasi guru besar tahun 2025.',
                'konten' => '<p>Orasi ilmiah tahun 2025 akan dilaksanakan secara luring di Auditorium UNMUL.</p>',
                'status' => 'published',
                'is_pinned' => false,
                'published_at' => now()->subMonths(2),
            ],
            [
                'judul' => 'Pengumuman Arsip Orasi Tahun 2023–2024',
                'slug' => 'arsip-orasi-2023-2024',
                'ringkasan' => 'Arsip dokumentasi orasi guru besar tahun 2023 dan 2024 telah tersedia.',
                'konten' => '<p>Dokumentasi dapat diakses melalui menu arsip pada website orasi.</p>',
                'status' => 'published',
                'is_pinned' => false,
                'published_at' => now()->subMonths(6),
            ],
            [
                'judul' => 'Draft: Persiapan Orasi 2027',
                'slug' => 'draft-persiapan-orasi-2027',
                'ringkasan' => 'Catatan internal persiapan orasi tahun 2027.',
                'konten' => '<p>Belum dipublikasikan.</p>',
                'status' => 'draft',
                'is_pinned' => false,
                'published_at' => null,
            ],
        ];

        foreach ($items as $item) {
            Pengumuman::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
