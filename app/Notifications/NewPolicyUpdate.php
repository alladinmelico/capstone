<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPolicyUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    private $type = 'policy';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Terms of Service and Privacy Policy Update notice.")
            ->line("Terms of Service: https://www.sscsystem.tech/terms-of-service.pdf")
            ->line("Privacy Policy: https://www.sscsystem.tech/privacy-policy.pdf")
            ->action('Visit Website', 'https://www.sscsystem.tech');
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray()
    {
        return [
            'message' => "Terms of service and Privacy policy has been updated.",
        ];
    }

    public function toDatabase($notifiable)
    {
        return $this->data($notifiable);
    }

    public function data($notifiable)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'message' => "Terms of service and Privacy policy has been updated.",
            'notifiable_id' => $notifiable->id,
            'timestamp' => now()->toISOString(),
        ];
    }
}
