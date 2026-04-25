<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Payment;

final class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if ($payment->yuran_id && ($payment->tahun_mula === null || $payment->tahun_tamat === null)) {
            $yuran = $payment->yuran ?? $payment->yuran()->first();
            $tempoh = $yuran ? (int) $yuran->tempoh_tahun : 1;
            $tahunBayar = (int) $payment->tahun_bayar;

            if ($payment->tahun_mula === null) {
                $payment->tahun_mula = $tahunBayar;
            }
            if ($payment->tahun_tamat === null) {
                $payment->tahun_tamat = $tahunBayar + $tempoh - 1;
            }
        }

        // When a payment is created already-approved (admin collect flow), treat it as "approved now".
        if ($payment->status === Payment::STATUS_APPROVED && empty($payment->no_resit_sistem)) {
            $payment->no_resit_sistem = $this->generateReceiptNumber($payment);
        }
    }

    public function updating(Payment $payment): void
    {
        // Generate receipt only when transitioning to approved.
        if (
            $payment->isDirty('status')
            && $payment->status === Payment::STATUS_APPROVED
            && empty($payment->no_resit_sistem)
        ) {
            $payment->no_resit_sistem = $this->generateReceiptNumber($payment);
        }
    }

    private function generateReceiptNumber(Payment $payment): string
    {
        $year = (int) ($payment->approved_at?->year ?? now()->year);
        $type = $payment->jenis === 'pendaftaran_baru' ? 'PB' : 'PM';

        $last = Payment::query()
            ->where('no_resit_sistem', 'like', 'BAKIS/'.$type.'/'.$year.'/%')
            ->whereNotNull('no_resit_sistem')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $nextSeq = 1;
        if ($last?->no_resit_sistem) {
            if (preg_match('~/(\d+)\s*$~', (string) $last->no_resit_sistem, $m) === 1) {
                $nextSeq = ((int) $m[1]) + 1;
            }
        }

        return sprintf('BAKIS/%s/%d/%05d', $type, $year, $nextSeq);
    }
}
