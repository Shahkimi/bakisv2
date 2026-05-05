<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Payment;
use App\Services\PaymentReceiptPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class PaymentApprovedReceiptNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payment $payment
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->payment->loadMissing(['member.jabatan', 'member.jawatan', 'member.memberStatus', 'yuran']);

        $member = $this->payment->member;
        $nama = $member?->nama ?? 'Ahli';

        /** @var PaymentReceiptPdfService $pdfService */
        $pdfService = app(PaymentReceiptPdfService::class);
        $out = $pdfService->render($this->payment);

        return (new MailMessage)
            ->subject('Resit pembayaran disahkan — '.config('app.name'))
            ->greeting('Helo '.$nama.',')
            ->line('Pembayaran anda telah disahkan. Dilampirkan resit rasmi PDF bagi rekod anda.')
            ->line('Simpan resit ini sebagai bukti pembayaran yuran keahlian.')
            ->line('Jika anda mempunyai pertanyaan, sila hubungi pentadbir sistem.')
            ->attachData($out['content'], $out['filename'], [
                'mime' => 'application/pdf',
            ]);
    }
}
