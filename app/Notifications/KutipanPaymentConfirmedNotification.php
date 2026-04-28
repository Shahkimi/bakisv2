<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent synchronously so confirmation reaches the member without a queue worker.
 * (Queued mail would require `php artisan queue:work` when QUEUE_CONNECTION is not `sync`.)
 */
final class KutipanPaymentConfirmedNotification extends Notification
{
    /**
     * @param  list<string>  $detailLines
     */
    public function __construct(
        public Member $member,
        public string $receiptNo,
        public array $detailLines,
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
        $nama = $this->member->nama;

        $mail = (new MailMessage)
            ->subject('Pengesahan pembayaran kutipan — '.config('app.name'))
            ->greeting('Helo '.$nama.',')
            ->line('Pembayaran telah direkodkan dalam sistem selepas kutipan.')
            ->line('No. resit sistem: '.$this->receiptNo);

        foreach ($this->detailLines as $line) {
            if ($line !== '') {
                $mail->line($line);
            }
        }

        return $mail
            ->line('Sila simpan e-mel ini sebagai rujukan.')
            ->salutation(config('app.name'));
    }
}
