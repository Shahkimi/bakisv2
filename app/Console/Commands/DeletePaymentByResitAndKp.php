<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

final class DeletePaymentByResitAndKp extends Command
{
    protected $signature = 'payment:delete-by-resit-kp
                            {no_resit : No resit/rujukan (e.g. 16494562)}
                            {no_kp : No KP ahli (e.g. 851002025136)}';

    protected $description = 'Delete payment(s) matching no_resit_transfer and member no_kp (one-off admin use)';

    public function handle(): int
    {
        $noResit = (string) $this->argument('no_resit');
        $noKp = (string) $this->argument('no_kp');

        $query = Payment::query()
            ->where('no_resit_transfer', $noResit)
            ->whereHas('member', fn ($q) => $q->where('no_kp', $noKp));

        $count = $query->count();
        $ids = $query->pluck('id')->toArray();

        if ($count === 0) {
            $this->warn("No payment found with no_resit {$noResit} and no_kp {$noKp}.");

            return self::SUCCESS;
        }

        $query->delete();
        $this->info("Deleted {$count} payment record(s): id(s) ".implode(', ', $ids));

        return self::SUCCESS;
    }
}
