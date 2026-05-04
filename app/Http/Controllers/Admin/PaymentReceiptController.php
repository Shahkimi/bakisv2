<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

final class PaymentReceiptController extends Controller
{
    public function download(Payment $payment): Response
    {
        if ($payment->status !== Payment::STATUS_APPROVED) {
            abort(404);
        }

        $payment->load(['member.jabatan', 'member.jawatan', 'member.memberStatus', 'yuran']);
        $member = $payment->member;

        $user = auth()->user();
        if ($user instanceof User && $user->isUser()) {
            $memberKp = preg_replace('/\D/', '', (string) $member->no_kp);
            $userKp = preg_replace('/\D/', '', (string) $user->no_kp);
            if ($memberKp !== '' && $userKp !== '' && $memberKp !== $userKp) {
                abort(403);
            }
        }

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

        return $pdf->download($filename);
    }
}
