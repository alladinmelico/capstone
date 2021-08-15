<?php

namespace App\Notifications;

use App\Traits\Notifications\BroadcastsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotificationDeleted extends Notification
{
    use Queueable;
    use BroadcastsNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public string $notificationId
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'deleted_id' => $this->notificationId,
        ];
    }
}
