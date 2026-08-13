<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('programs', 'acara_id')) {
            return;
        }

        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('acara_id')
                ->nullable()
                ->after('is_active')
                ->constrained('acaras')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('acara_id');
        });
    }
};
