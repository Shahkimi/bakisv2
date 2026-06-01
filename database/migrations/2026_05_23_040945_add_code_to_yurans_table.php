<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<string, string> */
    private array $jenisToCode = [
        'Pendaftaran Keahlian' => 'pendaftaran_keahlian',
        'Pembaharuan Keahlian' => 'pembaharuan_tahunan',
        'Pembaharuan 2 Tahun' => 'pembaharuan_2_tahun',
    ];

    public function up(): void
    {
        Schema::table('yurans', function (Blueprint $table): void {
            $table->string('code', 50)->nullable()->after('jenis_yuran');
        });

        foreach ($this->jenisToCode as $jenisYuran => $code) {
            DB::table('yurans')
                ->where('jenis_yuran', $jenisYuran)
                ->whereNull('code')
                ->update(['code' => $code]);
        }

        Schema::table('yurans', function (Blueprint $table): void {
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('yurans', function (Blueprint $table): void {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
