<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('yurans', function (Blueprint $table) {
            $table->unsignedTinyInteger('tempoh_tahun')->default(1)->after('jumlah');
        });

        // Ensure all existing yurans have tempoh_tahun = 1
        DB::table('yurans')->update(['tempoh_tahun' => 1]);

        // Insert "Pembaharuan 2 Tahun" (RM 20, 2 years) if not exists
        if (DB::table('yurans')->where('jumlah', 20.00)->where('tempoh_tahun', 2)->doesntExist()) {
            DB::table('yurans')->insert([
                'jenis_yuran' => 'Pembaharuan 2 Tahun',
                'jumlah' => 20.00,
                'tempoh_tahun' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('yurans', function (Blueprint $table) {
            $table->dropColumn('tempoh_tahun');
        });
    }
};
