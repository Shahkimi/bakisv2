<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('programs')) {
            return;
        }

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->date('tarikh');
            $table->string('waktu_mula', 20)->nullable();
            $table->string('waktu_tamat', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('tarikh');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
