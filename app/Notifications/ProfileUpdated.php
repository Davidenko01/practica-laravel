<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<int, string>  $changes  The attributes that were modified.
     */
    public function __construct(protected array $changes = [])
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $labels = [
            'username' => 'nombre',
            'email' => 'email',
            'password' => 'contrasena',
        ];

        $changed = array_map(fn (string $change) => $labels[$change] ?? $change, $this->changes);

        return (new MailMessage)
            ->subject('Actualizaste tu perfil')
            ->greeting('Hola '.$notifiable->username.'!')
            ->line('Los datos de tu cuenta fueron actualizados.')
            ->when($changed !== [], fn (MailMessage $mail) => $mail->line('Cambiaste: '.implode(', ', $changed).'.'))
            ->action('Ver mi perfil', route('profile.edit'))
            ->line('Si no fuiste vos, cambia tu contrasena cuanto antes.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'changes' => $this->changes,
        ];
    }
}
