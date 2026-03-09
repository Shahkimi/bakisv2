<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MemberStatus;
use Illuminate\Database\Seeder;

class MemberStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Aktif', 'code' => 'aktif', 'is_active' => true],
            ['name' => 'Tidak Aktif', 'code' => 'tidak_aktif', 'is_active' => true],
            ['name' => 'Meninggal', 'code' => 'meninggal', 'is_active' => true],
            ['name' => 'Menunggu Kelulusan', 'code' => 'pending', 'is_active' => true],
        ];

        foreach ($statuses as $status) {
            MemberStatus::firstOrCreate(
                ['code' => $status['code']],
                $status
            );
        }
    }
}
