<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table): void {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('settings', 'key')) {
                $table->string('key')->unique();
            }
            if (! Schema::hasColumn('settings', 'value')) {
                $table->text('value')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table): void {
            if (Schema::hasColumn('settings', 'key')) {
                $table->dropUnique(['key']);
            }
        });

        Schema::table('settings', function (Blueprint $table): void {
            if (Schema::hasColumn('settings', 'value')) {
                $table->dropColumn('value');
            }
            if (Schema::hasColumn('settings', 'key')) {
                $table->dropColumn('key');
            }
        });
    }
};
