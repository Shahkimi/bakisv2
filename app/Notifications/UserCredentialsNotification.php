<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

final class UserCredentialsNotification extends Notification
{
    public function __construct(
        public User $user,
        public string $temporaryPassword,
        public bool $isReset = false
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
        $roleLabel = $this->user->role === User::ROLE_ADMIN ? 'Admin' : 'Pengguna';

        $intro = $this->isReset
            ? 'Kata laluan akaun '.config('app.name').' anda telah ditetapkan semula oleh pentadbir.'
            : 'Akaun anda telah dicipta sebagai '.$roleLabel.' pada sistem '.config('app.name').'.';

        return (new MailMessage)
            ->subject($this->isReset
                ? 'Kata laluan '.config('app.name').' anda telah ditetapkan semula'
                : 'Akaun '.config('app.name').' anda telah dicipta')
            ->greeting('Helo '.$this->user->name.',')
            ->line($intro)
            ->line('Berikut adalah maklumat log masuk anda:')
            ->line('**E-mel:** '.$this->user->email)
            ->line('**Kata laluan sementara:** '.$this->temporaryPassword)
            ->action('Log masuk', URL::route('login'))
            ->line('Atas sebab keselamatan, anda akan diminta menukar kata laluan sementara ini selepas log masuk kali pertama.')
            ->line('Jika anda tidak menjangka e-mel ini, sila abaikan mesej ini.');
    }
}
