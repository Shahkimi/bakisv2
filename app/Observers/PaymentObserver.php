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
    }
}
