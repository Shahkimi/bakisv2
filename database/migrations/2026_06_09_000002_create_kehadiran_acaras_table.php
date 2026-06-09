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
        Schema::create('kehadiran_acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')->constrained('acaras')->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('no_kp');
            $table->string('nama');
            $table->timestamp('attended_at');
            $table->timestamps();

            $table->unique(['acara_id', 'no_kp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_acaras');
    }
};
