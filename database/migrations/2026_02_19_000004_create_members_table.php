<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('no_ahli')->unique()->nullable();

            $table->foreignId('jabatan_id')->constrained('jabatans');
            $table->foreignId('jawatan_id')->constrained('jawatans');
            $table->foreignId('member_status_id')->constrained('member_statuses');

            $table->string('nama');
            $table->string('no_kp', 12)->unique();
            $table->string('email')->nullable();
            $table->enum('jantina', ['L', 'P']);

            $table->string('alamat1')->nullable();
            $table->string('alamat2')->nullable();
            $table->string('poskod', 10)->nullable();
            $table->string('bandar')->nullable();
            $table->string('negeri')->nullable();

            $table->string('no_tel')->nullable();
            $table->string('no_hp')->nullable();

            $table->string('gambar')->nullable();
            $table->text('catatan')->nullable();

            $table->date('tarikh_daftar');
            $table->timestamps();
            $table->softDeletes();

            $table->index('no_kp');
            $table->index('no_ahli');
            $table->index('jabatan_id');
            $table->index('jawatan_id');
            $table->index('member_status_id');
            $table->index('tarikh_daftar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
