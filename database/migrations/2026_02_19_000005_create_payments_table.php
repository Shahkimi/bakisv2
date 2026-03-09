<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members');

            $table->year('tahun_bayar');
            $table->decimal('jumlah', 8, 2);
            $table->enum('jenis', ['pendaftaran_baru', 'pembaharuan'])->default('pembaharuan');

            $table->string('no_resit_transfer')->nullable();
            $table->string('no_resit_sistem')->nullable();
            $table->string('bukti_bayaran')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_admin')->nullable();

            $table->timestamps();

            $table->index(['member_id', 'tahun_bayar']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
