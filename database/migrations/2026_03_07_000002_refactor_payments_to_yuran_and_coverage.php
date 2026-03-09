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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('yuran_id')->nullable()->after('member_id')->constrained('yurans');
            $table->year('tahun_mula')->nullable()->after('tahun_bayar');
            $table->year('tahun_tamat')->nullable()->after('tahun_mula');
        });

        $this->backfillYuranIdAndCoverage();

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['jumlah', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('jumlah', 8, 2)->nullable()->after('tahun_tamat');
            $table->enum('jenis', ['pendaftaran_baru', 'pembaharuan'])->default('pembaharuan')->after('jumlah');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['yuran_id']);
            $table->dropColumn(['yuran_id', 'tahun_mula', 'tahun_tamat']);
        });
    }

    private function backfillYuranIdAndCoverage(): void
    {
        $yurans = DB::table('yurans')->get()->keyBy('id');

        // Build map: (jumlah, jenis) -> yuran_id (prefer exact match)
        $pendaftaranId = $yurans->first(fn ($y) => (float) $y->jumlah === 12.0)?->id;
        $pembaharuan10Id = $yurans->first(fn ($y) => (float) $y->jumlah === 10.0 && (int) $y->tempoh_tahun === 1)?->id;
        $pembaharuan20Id = $yurans->first(fn ($y) => (float) $y->jumlah === 20.0 && (int) $y->tempoh_tahun === 2)?->id;

        $defaultYuranId = $pembaharuan10Id ?? $yurans->keys()->first();

        $payments = DB::table('payments')->get();

        foreach ($payments as $p) {
            $jumlah = (float) $p->jumlah;
            $jenis = (string) $p->jenis;

            $yuranId = match (true) {
                $jenis === 'pendaftaran_baru' && $jumlah === 12.0 => $pendaftaranId,
                $jenis === 'pembaharuan' && $jumlah === 20.0 => $pembaharuan20Id,
                $jenis === 'pembaharuan' => $pembaharuan10Id,
                default => $defaultYuranId,
            } ?? $defaultYuranId;

            $yuran = $yurans->firstWhere('id', $yuranId);
            $tempoh = $yuran ? (int) $yuran->tempoh_tahun : 1;
            $tahunBayar = (int) $p->tahun_bayar;
            $tahunMula = $tahunBayar;
            $tahunTamat = $tahunBayar + $tempoh - 1;

            DB::table('payments')->where('id', $p->id)->update([
                'yuran_id' => $yuranId,
                'tahun_mula' => $tahunMula,
                'tahun_tamat' => $tahunTamat,
            ]);
        }
    }
};
