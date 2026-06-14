<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\UserInvitation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

final class UserInvitationNotification extends Notification
{
    public function __construct(
        public UserInvitation $invitation
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
        $acceptUrl = URL::temporarySignedRoute(
            'invitation.accept',
            $this->invitation->expires_at,
            ['token' => $this->invitation->token]
        );

        $roleLabel = $this->invitation->role === UserInvitation::ROLE_ADMIN ? 'Admin' : 'Pengguna';

        return (new MailMessage)
            ->subject('Jemputan akaun '.config('app.name'))
            ->greeting('Helo '.$this->invitation->name.',')
            ->line('Anda dijemput untuk mendaftar sebagai '.$roleLabel.' pada sistem '.config('app.name').'.')
            ->action('Terima jemputan & tetapkan kata laluan', $acceptUrl)
            ->line('Pautan ini tamat pada '.$this->invitation->expires_at->timezone(config('app.timezone'))->format('d/m/Y H:i').'.')
            ->line('Jika anda tidak menjangka e-mel ini, abaikan mesej ini.');
    }
}
