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

    private $type = 'schedule';
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Schedule successfully created.")
            ->greeting("You got a new Schedule!")
            ->line("{$this->schedule->user->name} created a schedule.")
            ->action('View', config('app.main_url'). "/schedule/{$this->schedule->id}")
            ->line('Thank you for using our application!');
    }

    protected function message(): string
    {
        return 'A new schedule created';
    }

    protected function url(): string
    {
        return 'schedule/' . $this->id;
    }

    public function toDatabase($notifiable)
    {
        return $this->data($notifiable);
    }

    public function data($notifiable)
    {
        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'message'           => $this->schedule->title,
            'notifiable_id'     => $notifiable->id,
            'timestamp'         => now()->toISOString(),
            'model'             => $this->schedule
        ];
    }
}
