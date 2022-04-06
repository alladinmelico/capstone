<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $type = 'ticket_update';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public String $ticket_id,
        public String $status
    ){}

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Report ticket updated.")
            ->greeting("Ticket ID: {$this->ticket_id}")
            ->line("Current Status: {$this->status}");
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray()
    {
        return [
            'message' => "Ticket updated. {$this->ticket_id}. {$this->status}.",
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
            'ticket_id' => $this->ticket_id,
            'status' => $this->status,
            'notifiable_id' => $notifiable->id,
            'timestamp' => now()->toISOString(),
        ];
    }
}
