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
     * @param  list<int>|null  $semakRenewalYears  Tahun pembaharuan dipilih (semak awam); jika null, guna satu rekod pembayaran sahaja.
     */
    public function __construct(
        public Payment $payment,
        public ?array $semakRenewalYears = null,
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

        $semakUrl = URL::route('semak.result', ['no_kp' => $member?->no_kp ?? '']);

        $mail = (new MailMessage)
            ->subject('Bukti pembayaran telah dihantar — '.config('app.name'))
            ->greeting('Helo '.$nama.',')
            ->line('Bukti pembayaran pembaharuan anda telah berjaya dimuat naik ke sistem.')
            ->line('Sila semak sama ada bayaran telah diterima dan status pembayaran dikemas kini selepas pengesahan admin.');

        $renewalYears = $this->semakRenewalYears;
        if ($renewalYears !== null && $renewalYears !== []) {
            $years = array_map(static fn (int $y): int => $y, $renewalYears);
            sort($years);
            $yearLabel = implode(', ', array_map(static fn (int $y): string => (string) $y, $years));
            $perYear = (float) $this->payment->jumlah;
            $total = number_format(count($years) * $perYear, 2);
            $mail->line('Tahun pembaharuan dipilih: '.$yearLabel);
            $mail->line('Jumlah: RM '.$total.' (RM '.number_format($perYear, 2).' × '.count($years).' tahun)');
        } else {
            $jumlah = number_format($this->payment->jumlah, 2);
            $mail->line('Tahun bayaran: '.$this->payment->tahun_bayar);
            $mail->line('Jumlah: RM '.$jumlah);
        }

        return $mail
            ->action('Semak status keahlian', $semakUrl)
            ->line('Jika anda tidak menghantar permohonan ini, sila hubungi pentadbir sistem.');
    }
}
