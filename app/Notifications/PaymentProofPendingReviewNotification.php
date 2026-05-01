<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

/**
 * E-mel kepada pentadbir / pegawai panel untuk semak bukti pembayaran semak awam.
 */
final class PaymentProofPendingReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  list<int>|null  $semakRenewalYears
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
        $noKp = $member?->no_kp ?? '—';

        $adminPembayaranUrl = URL::route('admin.pembayaran.index');
        $userPembayaranUrl = URL::route('user.pembayaran.index');

        $mail = (new MailMessage)
            ->subject('Bukti pembayaran menunggu semakan — '.config('app.name'))
            ->greeting('Salam,')
            ->line('Ahli telah menghantar bukti pembayaran pembaharuan melalui halaman semak awam. Sila semak dan luluskan atau tolak rekod berkaitan.')
            ->line('Nama: '.$nama)
            ->line('No. KP: '.$noKp)
            ->line('ID pembayaran (rekod pertama): #'.$this->payment->id);

        $renewalYears = $this->semakRenewalYears;
        if ($renewalYears !== null && $renewalYears !== []) {
            $years = array_map(static fn (int|string $y): int => (int) $y, $renewalYears);
            sort($years);
            $yearLabel = implode(', ', array_map(static fn (int $y): string => (string) $y, $years));
            $perYear = (float) $this->payment->jumlah;
            $total = number_format(count($years) * $perYear, 2);
            $mail->line('Tahun pembaharuan: '.$yearLabel);
            $mail->line('Jumlah anggaran: RM '.$total.' (RM '.number_format($perYear, 2).' × '.count($years).' tahun)');
        } else {
            $mail->line('Tahun bayaran: '.$this->payment->tahun_bayar);
            $mail->line('Jumlah: RM '.number_format($this->payment->jumlah, 2));
        }

        $mail
            ->line('Senarai pembayaran (pentadbir): '.$adminPembayaranUrl)
            ->line('Senarai pembayaran (panel pengguna): '.$userPembayaranUrl)
            ->line('Jika pautan tidak berfungsi, log masuk ke panel yang sesuai dan buka menu Pembayaran.')
            ->salutation('Sistem '.config('app.name'));

        return $mail;
    }
}
