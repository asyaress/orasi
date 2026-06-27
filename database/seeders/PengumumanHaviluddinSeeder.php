<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Services\PengumumanContentSanitizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PengumumanHaviluddinSeeder extends Seeder
{
    public function run(): void
    {
        $coverPath = 'images/pengumuman/foto-sampul-conten-haviluddin.jpeg';

        Pengumuman::query()
            ->where('slug', '!=', 'universitas-mulawarman-apresiasi-prestasi-prof-haviluddin-ilmuwan-top-dunia')
            ->get()
            ->each(function (Pengumuman $announcement): void {
                if (filled($announcement->cover_path)) {
                    Storage::disk('public')->delete($announcement->cover_path);
                }

                $announcement->delete();
            });

        $content = <<<'HTML'
<figure style="margin: 0 0 32px; text-align: center;">
    <img src="/images/pengumuman/foto-sampul-conten-haviluddin.jpeg" alt="Ucapan selamat kepada Prof. Ir. Haviluddin sebagai World's Top 5% Scientist pada 2025 SCIRank Global Registry" style="max-width: 760px; width: 100%; height: auto;">
</figure>

<p>Universitas Mulawarman kembali menorehkan kebanggaan melalui capaian salah satu sivitas akademik terbaiknya. Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM., Guru Besar Fakultas Teknik Universitas Mulawarman, berhasil mendapatkan pengakuan sebagai salah satu ilmuwan yang termasuk dalam <strong>World’s Top 5% Scientist</strong> pada <strong>2025 SCIRank Global Registry</strong>.</p>

<p>Capaian ini menjadi bukti nyata bahwa kontribusi akademik dan riset yang dilakukan oleh Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM. terus berkembang dan mampu bersaing di tingkat global. Pengakuan tersebut tidak hanya menjadi prestasi pribadi bagi Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM., tetapi juga membawa nama baik Fakultas Teknik dan Universitas Mulawarman di kancah internasional.</p>

<p>Rektor Universitas Mulawarman, Prof. Dr. Ir. H. Abdunnur, M.Si., IPU., ASEAN Eng., beserta seluruh sivitas akademika menyampaikan ucapan selamat dan apresiasi setinggi-tingginya atas pencapaian Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM. Prestasi ini diharapkan dapat menjadi inspirasi bagi seluruh dosen, peneliti, tenaga kependidikan, serta mahasiswa Universitas Mulawarman untuk terus meningkatkan kualitas diri, memperkuat budaya akademik, dan berani berkontribusi melalui karya-karya ilmiah yang berdampak.</p>

<p>Bagi mahasiswa, pencapaian Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM. menjadi motivasi bahwa proses belajar, riset, dan ketekunan dalam mengembangkan ilmu pengetahuan dapat membuka jalan menuju pengakuan yang lebih luas. Prestasi Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM. menunjukkan bahwa sivitas akademika Universitas Mulawarman memiliki peluang besar untuk berdaya saing, tidak hanya di tingkat nasional, tetapi juga di tingkat dunia.</p>

<p>Pengakuan terhadap Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM. juga menjadi bagian dari komitmen Universitas Mulawarman dalam mendorong budaya akademik yang unggul, produktif, dan berorientasi pada kontribusi nyata. Melalui pencapaian ini, Universitas Mulawarman semakin memperkuat posisinya sebagai perguruan tinggi yang terus bergerak maju menuju reputasi akademik yang lebih luas.</p>

<p><strong>Selamat kepada Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM.</strong> Semoga prestasi ini menjadi pemantik semangat bagi seluruh sivitas akademika, khususnya mahasiswa, untuk terus belajar, berkarya, meneliti, dan memberikan kontribusi terbaik bagi ilmu pengetahuan, Universitas Mulawarman, serta bangsa Indonesia.</p>
HTML;

        $announcement = Pengumuman::query()->firstOrNew([
            'slug' => 'universitas-mulawarman-apresiasi-prestasi-prof-haviluddin-ilmuwan-top-dunia',
        ]);

        $announcement->fill([
            'judul' => 'Universitas Mulawarman Sampaikan Apresiasi atas Prestasi Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM. sebagai Ilmuwan Top Dunia',
            'ringkasan' => 'Prof. Ir. Haviluddin, S.Kom., M.Kom., Ph.D., IPM., Guru Besar Fakultas Teknik Universitas Mulawarman, meraih pengakuan sebagai World’s Top 5% Scientist pada 2025 SCIRank Global Registry.',
            'konten' => app(PengumumanContentSanitizer::class)->sanitize($content),
            'cover_path' => $coverPath,
            'tags' => ['Prestasi', 'Guru Besar', 'Riset', 'Internasional'],
            'status' => 'published',
            'is_pinned' => true,
            'published_at' => $announcement->published_at ?? now(),
        ]);

        $announcement->save();
    }
}
