<?php

namespace App\Notifications;

use App\Models\Schedule;
use App\Traits\Notifications\BroadcastsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleCreated extends Notification implements ShouldQueue
{
    use Queueable;
    use BroadcastsNotification;

    public $schedule;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
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
            'message' => 'A new schedule created.',
            'url' => '',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->line('Thank you for using our application!');
    }

    protected function message(): string
    {
        return 'A new schedule created';
    }

    public function broadcastType()
    {
        return 'new-schedule';
    }
}
