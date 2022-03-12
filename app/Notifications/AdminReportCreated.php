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
    protected $message;
    protected $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(String $reason, String $message)
    {
        $this->reason = $reason;
        $this->message = $message;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New report submitted.")
            ->greeting("Hey Admin!")
            ->line("Reason: {$this->reason}.")
            ->line("Message: {$this->message}.");
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toArray()
    {
        return [
            'message' => "A new report submitted. {$this->reason}. {$this->message}.",
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
            'reason' => $this->reason,
            'message' => $this->message,
            'notifiable_id' => $notifiable->id,
            'timestamp' => now()->toISOString(),
        ];
    }
}
