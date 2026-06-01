<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Yuran;
use Illuminate\Database\Seeder;

class YuranSeeder extends Seeder
{
    public function run(): void
    {
        Yuran::firstOrCreate(
            ['code' => Member::YURAN_CODE_PENDAFTARAN],
            [
                'jenis_yuran' => 'Pendaftaran Keahlian',
                'jumlah' => 12.00,
                'tempoh_tahun' => 1,
                'is_active' => true,
                'is_show' => true,
            ]
        );

        Yuran::firstOrCreate(
            ['code' => Member::YURAN_CODE_PEMBAHARUAN],
            [
                'jenis_yuran' => 'Pembaharuan Keahlian',
                'jumlah' => 10.00,
                'tempoh_tahun' => 1,
                'is_active' => true,
                'is_show' => true,
            ]
        );

        Yuran::firstOrCreate(
            ['code' => Member::YURAN_CODE_PEMBAHARUAN_2_TAHUN],
            [
                'jenis_yuran' => 'Pembaharuan 2 Tahun',
                'jumlah' => 20.00,
                'tempoh_tahun' => 2,
                'is_active' => true,
                'is_show' => true,
            ]
        );
    }
}
