<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBypassCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private $type = 'user_bypass';
    protected $user;
    protected $message;
    protected $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, String $reason, String $message)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->message = $message;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New user bypass submitted.")
            ->greeting("Hey Admin!")
            ->line("User: {$this->user->name}.")
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
            'message' => "A new user[{$this->user->name}] bypass submitted. {$this->reason}. {$this->message}.",
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
            'user_id' => $this->user->id,
            'reason' => $this->reason,
            'message' => $this->message,
            'notifiable_id' => $notifiable->id,
            'timestamp' => now()->toISOString(),
        ];
    }
}
