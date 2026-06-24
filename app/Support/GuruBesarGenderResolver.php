<?php

namespace App\Support;

use App\Models\GuruBesar;

class GuruBesarGenderResolver
{
    /** @var array<string, string> */
    private const EXACT_MAP = [
        'Prof. Dr. Anindita Septiarini, S.T., M.Cs.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Azainil, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Bambang Irawan, S.Sos., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Darnah, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. dr. Swandari Paramita, M.Kes.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Dra. Laili Komariyah, M.Si.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Dra. Widyatmike Gede Mulawarman, M.Hum.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Drs. Bohari, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Drs. Didimus Tanah Boleng, M.Kes.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Drs. Laode Rijai, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Drs. Moh. Bahzar, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Drs. Muhammad Noor, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Drs. Saraka, M.Pd.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Drs. Warman, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Erwin, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Fahrul Agus, S.Si., M.T., MTA., MCE.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. H. Zeni Haryanto, M.Pd.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Hamdi Mayulu, S.Pt., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Hasbi Sjamsir, M.Hum.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Hetty Manurung, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Ir. H. A Syamad Ramayana, MP.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. H. Abdunnur, M.Si., IPU.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. H. Dharma Widada, M.T., IPU., ASEAN Eng., APEC Eng.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Hamdani, S.T., M.Cs., IPM.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Harjuni Hasan, M.Si., IPM., ASEAN Eng.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Hj. Andi Noor Asikin, M.Si.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Ir. Mulyadi, M.Sc.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Paulus Matius, M.Sc.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Surya Darma, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Tamrin, S.T., M.T.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Taufan Purwokusumaning Daru, M.P.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Ir. Zulkarnain, M.S.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Irwansyah, S.E., M.M.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Iwan Muhamad Ramdan, S.Kp., M.Kes.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Jiuhardi, S.E., M.M.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Karmini, S.P., M.P.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Karyati, S.Hut., M.P.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Muhammad Muhdar, S.H., M.Hum.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Nurlaili, M.P.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Priyagus, S.E., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. R.R. Harlinda Kuspradini, S.Hut., M.P.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Rahmawati, S.E., M.M.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Rudi Kartika, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Rudy Agung Nugroho, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Sifriyani, S.Pd., M.Si.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dr. Soerja Koesnarpadi, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Subur P. Pasaribu, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Teguh Wirawan, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Usman, S.Si., M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Yusak Hudiyono, M.Pd.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr. Zulkarnaen, M.Si.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Dr.sc.agr. Nurhasanah, S.P., M.Si.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Dyah Sunggingwati, S.Pd., M.Pd., Ph.D.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Ir. Ndan Imang, M.P., Ph.D.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Ir. Sopialena, M.P., Ph.D.' => GuruBesar::JENIS_KELAMIN_PEREMPUAN,
        'Prof. Ir. Suyadi, M.S., Ph.D.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
        'Prof. Widi Sunaryo, S.P., M.Si., Ph.D.' => GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
    ];

    /** @var list<string> */
    private const FEMALE_GIVEN_NAMES = [
        'anindita', 'darnah', 'swandari', 'laili', 'widyatmike', 'hetty', 'asikin',
        'karmini', 'karyati', 'nurlaili', 'harlinda', 'rahmawati', 'sifriyani',
        'nurhasanah', 'dyah', 'sopialena',         'siti', 'dewi', 'fitri', 'fitriani',
    ];

    public function resolve(?string $nama): ?string
    {
        $nama = trim((string) $nama);

        if ($nama === '') {
            return null;
        }

        if (isset(self::EXACT_MAP[$nama])) {
            return self::EXACT_MAP[$nama];
        }

        if (preg_match('/\bHj\.\s/i', $nama) || preg_match('/\bDra\.\s/i', $nama)) {
            return GuruBesar::JENIS_KELAMIN_PEREMPUAN;
        }

        if (preg_match('/\bH\.\s/i', $nama)) {
            return GuruBesar::JENIS_KELAMIN_LAKI_LAKI;
        }

        $givenName = $this->extractGivenName($nama);

        if (in_array($givenName, self::FEMALE_GIVEN_NAMES, true)) {
            return GuruBesar::JENIS_KELAMIN_PEREMPUAN;
        }

        return GuruBesar::JENIS_KELAMIN_LAKI_LAKI;
    }

    private function extractGivenName(string $nama): string
    {
        $clean = preg_replace('/\b(prof|dr|drs|dra|ir|h|hj|mt|mpd|msi|mh|mp|phd|sh|st|msc|m\.kes|ipu|ipm)\b\.?/i', ' ', $nama) ?: '';
        $clean = preg_replace('/[^a-z\s]/i', ' ', $clean) ?: '';
        $parts = preg_split('/\s+/', strtolower(trim($clean))) ?: [];

        return $parts[0] ?? '';
    }
}
