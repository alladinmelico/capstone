<?php

namespace App\Notifications;

use App\Models\Schedule;
use App\Models\User;
use App\Traits\Notifications\BroadcastsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleDeleted extends Notification implements ShouldQueue
{
    use Queueable;
    use BroadcastsNotification;

    private $type = 'schedule';
    public $schedule;
    public $user;

    /**
     * Delete a new notification instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule, User $user)
    {
        $this->schedule = $schedule;
        $this->user = $user;
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
            ->subject("Schedule has been Deleted.")
            ->greeting("Schedule title: {$this->schedule->title}")
            ->line("One of your schedules has been deleted by {$this->user->name}")
            ->line('Thank you for using our application!');
    }

    protected function message(): string
    {
        return 'A new schedule Deleted';
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
