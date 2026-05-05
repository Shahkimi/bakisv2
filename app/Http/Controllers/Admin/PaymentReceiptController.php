<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentReceiptPdfService;
use Illuminate\Http\Response;

final class PaymentReceiptController extends Controller
{
    public function download(Payment $payment, PaymentReceiptPdfService $paymentReceiptPdfService): Response
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

        ['content' => $content, 'filename' => $filename] = $paymentReceiptPdfService->render($payment);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
