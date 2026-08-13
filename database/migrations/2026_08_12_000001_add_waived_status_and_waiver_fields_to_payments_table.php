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
        if (! Schema::hasColumn('payments', 'waiver_requested_by')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('waiver_requested_by')->nullable()->after('catatan_admin')->constrained('users');
                $table->timestamp('waiver_requested_at')->nullable()->after('waiver_requested_by');
                $table->text('waiver_reason')->nullable()->after('waiver_requested_at');
                $table->foreignId('waived_by')->nullable()->after('waiver_reason')->constrained('users');
                $table->timestamp('waived_at')->nullable()->after('waived_by');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `payments` MODIFY `status` ENUM('pending','approved','rejected','waived') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('payments', fn (Blueprint $t) => $t->enum('status', ['pending', 'approved', 'rejected', 'waived'])->default('pending')->change());
        }
    }

    public function down(): void
    {
        DB::table('payments')->where('status', 'waived')->update(['status' => 'approved']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `payments` MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('payments', fn (Blueprint $t) => $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change());
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['waiver_requested_by']);
            $table->dropForeign(['waived_by']);
            $table->dropColumn([
                'waiver_requested_by',
                'waiver_requested_at',
                'waiver_reason',
                'waived_by',
                'waived_at',
            ]);
        });
    }
};
