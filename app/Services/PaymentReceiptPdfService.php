<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

final readonly class PaymentReceiptPdfService
{
    /**
     * @return array{content: string, filename: string}
     */
    public function render(Payment $payment): array
    {
        $payment->load(['member.jabatan', 'member.jawatan', 'member.memberStatus', 'yuran']);
        $member = $payment->member;

        $pdf = Pdf::loadView('admin.members.receipt-pdf', [
            'member' => $member,
            'payment' => $payment,
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isFontSubsettingEnabled' => true,
            ]);

        $safeNoAhli = preg_replace('/[^a-zA-Z0-9_-]+/', '-', trim((string) ($member->no_ahli ?? 'ahli')));
        $safeNoAhli = trim($safeNoAhli, '-') ?: 'ahli';
        $filename = sprintf(
            'resit-yuran-%s-%d-%d.pdf',
            $safeNoAhli,
            $payment->tahun_bayar,
            $payment->id
        );

        return [
            'content' => $pdf->output(),
            'filename' => $filename,
        ];
    }
}
