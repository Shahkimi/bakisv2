<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_acara');
            $table->string('lokasi');
            $table->string('waktu');
            $table->string('code', 12)->unique();
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acaras');
    }
};
