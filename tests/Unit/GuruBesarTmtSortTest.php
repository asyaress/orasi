<?php

namespace Tests\Unit;

use App\Models\GuruBesar;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GuruBesarTmtSortTest extends TestCase
{
    public function test_sort_by_tmt_ascending_puts_earliest_dates_first_and_nulls_last(): void
    {
        $january = new GuruBesar(['nama' => 'Januari', 'tmt' => '2024-01-15']);
        $march = new GuruBesar(['nama' => 'Maret', 'tmt' => '2024-03-01']);
        $february = new GuruBesar(['nama' => 'Februari', 'tmt' => '2024-02-01']);
        $withoutTmt = new GuruBesar(['nama' => 'Tanpa TMT', 'tmt' => null]);

        $sorted = GuruBesar::sortByTmtAscending(new Collection([
            $march,
            $withoutTmt,
            $january,
            $february,
        ]));

        $this->assertSame(
            ['Januari', 'Februari', 'Maret', 'Tanpa TMT'],
            $sorted->pluck('nama')->all()
        );
    }
}
