<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class DeduplicatePayments extends Command
{
    protected $signature = 'payments:deduplicate';

    protected $description = 'Remove duplicate payment rows, keeping the first (lowest id) per member+tahun_bayar+status';

    public function handle(): int
    {
        $groups = DB::table('payments')
            ->select(
                'member_id',
                'tahun_bayar',
                'status',
                DB::raw('MIN(id) as keep_id'),
                DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('member_id', 'tahun_bayar', 'status')
            ->having('cnt', '>', 1)
            ->get();

        $totalToDelete = $groups->sum(fn ($g) => (int) $g->cnt - 1);
        $groupCount = $groups->count();

        if ($groupCount === 0) {
            $this->info('No duplicate payment groups found. Nothing to do.');

            return self::SUCCESS;
        }

        $this->warn("Found {$groupCount} duplicate group(s) affecting {$totalToDelete} row(s) to be removed (keeping 1 per group).");
        if (! $this->confirm('Proceed with deletion?', true)) {
            $this->info('Aborted.');

            return self::SUCCESS;
        }

        $deleted = 0;
        foreach ($groups as $group) {
            $removed = Payment::query()
                ->where('member_id', $group->member_id)
                ->where('tahun_bayar', $group->tahun_bayar)
                ->where('status', $group->status)
                ->where('id', '!=', $group->keep_id)
                ->delete();
            $deleted += $removed;
        }

        $this->info("Done. Deleted {$deleted} duplicate payment row(s).");

        return self::SUCCESS;
    }
}
