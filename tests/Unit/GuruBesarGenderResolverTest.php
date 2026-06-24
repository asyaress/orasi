<?php

namespace Tests\Unit;

use App\Models\GuruBesar;
use App\Support\GuruBesarGenderResolver;
use PHPUnit\Framework\TestCase;

class GuruBesarGenderResolverTest extends TestCase
{
    public function test_it_maps_known_female_professors(): void
    {
        $resolver = new GuruBesarGenderResolver();

        $this->assertSame(
            GuruBesar::JENIS_KELAMIN_PEREMPUAN,
            $resolver->resolve('Prof. Dr. Hetty Manurung, S.Si., M.Si.')
        );
        $this->assertSame(
            GuruBesar::JENIS_KELAMIN_PEREMPUAN,
            $resolver->resolve('Prof. Dr. Ir. Hj. Andi Noor Asikin, M.Si.')
        );
    }

    public function test_it_maps_known_male_professors(): void
    {
        $resolver = new GuruBesarGenderResolver();

        $this->assertSame(
            GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
            $resolver->resolve('Prof. Dr. H. Zeni Haryanto, M.Pd.')
        );
        $this->assertSame(
            GuruBesar::JENIS_KELAMIN_LAKI_LAKI,
            $resolver->resolve('Prof. Dr. Fahrul Agus, S.Si., M.T., MTA., MCE.')
        );
    }
}
