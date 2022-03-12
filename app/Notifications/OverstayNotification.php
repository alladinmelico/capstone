<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class OverstayNotification extends Notification
{
    use Queueable;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject("Overstay reminder")
            ->setIcon(public_path() . '/icon.png')
            ->setBody("Hi {$this->user->name}! to keep the our campus from overcrowding, kindly leave the campus immediately.");
    }

    private function data($notifiable)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Send report if notification failed;
     */
    public function failed($e)
    {
        report($e);
    }
}
