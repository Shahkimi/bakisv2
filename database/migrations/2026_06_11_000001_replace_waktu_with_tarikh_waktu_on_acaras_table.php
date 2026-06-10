<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            $table->date('tarikh')->nullable()->after('lokasi');
            $table->string('waktu_mula')->nullable()->after('tarikh');
            $table->string('waktu_tamat')->nullable()->after('waktu_mula');
        });

        // Migrate existing free-text waktu into waktu_mula, then drop the column.
        DB::statement('UPDATE acaras SET waktu_mula = waktu WHERE waktu IS NOT NULL');

        Schema::table('acaras', function (Blueprint $table) {
            $table->dropColumn('waktu');
        });
    }

    public function down(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            $table->string('waktu')->nullable()->after('lokasi');
        });

        DB::statement('UPDATE acaras SET waktu = waktu_mula WHERE waktu_mula IS NOT NULL');

        Schema::table('acaras', function (Blueprint $table) {
            $table->dropColumn(['tarikh', 'waktu_mula', 'waktu_tamat']);
        });
    }
};
