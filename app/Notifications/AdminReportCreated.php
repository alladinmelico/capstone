<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminReportCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private $type = 'admin_report';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public String $ticket_id,
        public String $title,
        public String $category,
        public String $message
    ){}

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New report submitted.")
            ->greeting("Ticket ID: {$this->ticket_id}")
            ->line("Title: {$this->title}.")
            ->line("Category: {$this->category}.")
            ->line("Message: {$this->message}.");
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray()
    {
        return [
            'message' => "A new report submitted. {$this->ticket_id}. {$this->message}.",
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
            'title' => $this->title,
            'category' => $this->category,
            'message' => $this->message,
            'notifiable_id' => $notifiable->id,
            'timestamp' => now()->toISOString(),
        ];
    }
}
