<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Yuran;
use Illuminate\Database\Seeder;

class YuranSeeder extends Seeder
{
    public function run(): void
    {
        // Pendaftaran (annual)
        Yuran::firstOrCreate(
            ['jenis_yuran' => 'Pendaftaran Keahlian'],
            ['jumlah' => 12.00, 'tempoh_tahun' => 1, 'is_active' => true]
        );

        // Pembaharuan Tahunan (RM 10, 1 year)
        Yuran::firstOrCreate(
            ['jenis_yuran' => 'Pembaharuan Keahlian'],
            ['jumlah' => 10.00, 'tempoh_tahun' => 1, 'is_active' => true]
        );

        // Pembaharuan 2 Tahun (RM 20, 2 years)
        Yuran::firstOrCreate(
            ['jenis_yuran' => 'Pembaharuan 2 Tahun'],
            ['jumlah' => 20.00, 'tempoh_tahun' => 2, 'is_active' => true]
        );
    }
}
