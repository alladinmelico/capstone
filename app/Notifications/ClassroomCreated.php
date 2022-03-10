<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Classroom;

class ClassroomCreated extends Notification implements ShouldQueue
{
    use Queueable;
    public $classroom;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Classroom $classroom)
    {
        $this->classroom = $classroom;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->subject("You were invited to a Classroom.")
            ->greeting("New Classroom Invite!")
            ->line("You were invited to: {$this->classroom->name}.")
            ->action('Join', config('app.main_url'). '/classroom?invite='.$this->classroom->invite_code)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'A new classroom created.',
            'url' => '',
        ];
    }

    protected function message(): string
    {
        return 'A new classroom created';
    }
}
