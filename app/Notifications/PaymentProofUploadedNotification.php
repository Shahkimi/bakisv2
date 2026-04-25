<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

final class PaymentProofUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<int, string>  $ccAdminEmails
     */
    public function __construct(
        public Payment $payment,
        public array $ccAdminEmails = []
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
        $this->payment->loadMissing(['member', 'yuran']);

        $member = $this->payment->member;
        $nama = $member?->nama ?? 'Ahli';
        $jumlah = number_format($this->payment->jumlah, 2);

        $semakUrl = URL::route('semak.result', ['no_kp' => $member?->no_kp ?? '']);

        $mail = (new MailMessage)
            ->subject('Bukti pembayaran telah dihantar — '.config('app.name'))
            ->greeting('Helo '.$nama.',')
            ->line('Bukti pembayaran pembaharuan anda telah berjaya dimuat naik ke sistem.')
            ->line('Sila semak sama ada bayaran telah diterima dan status pembayaran dikemas kini selepas pengesahan admin.')
            ->line('Tahun bayaran: '.$this->payment->tahun_bayar)
            ->line('Jumlah: RM '.$jumlah)
            ->action('Semak status keahlian', $semakUrl)
            ->line('Jika anda tidak menghantar permohonan ini, sila hubungi pentadbir sistem.');

        $cc = $this->uniqueNonEmptyEmails($this->ccAdminEmails);
        if ($cc !== []) {
            $mail->cc($cc);
        }

        return $mail;
    }

    /**
     * @param  array<int, string>  $emails
     * @return array<int, string>
     */
    private function uniqueNonEmptyEmails(array $emails): array
    {
        $normalized = array_map(
            static fn (string $e): string => strtolower(trim($e)),
            $emails
        );

        return array_values(array_unique(array_filter($normalized, static fn (string $e): bool => $e !== '')));
    }
}
