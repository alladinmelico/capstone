<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

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
        return [PusherChannel::class];
    }

    public function toPushNotification($notifiable)
    {
         $image_url = public_path() . '/icon.png';

        return PusherMessage::create()
            ->android()
            ->icon($image_url)
            ->title("Overstay reminder")
            ->body("Hi ${$this->user->name}! to keep the our campus from overcrowding, kindly leave the campus immediately.");
    }

     /**
     * @param $notifiable
     * @return FcmMessage
     */
    public function toFcm($notifiable)
    {
        $data = $this->data($notifiable);
        $image_url = public_path() . '/icon.png';

        $fcmNotification = FcmNotification::create()
            ->setTitle("Overstay reminder")
            ->setBody("Hi ${$this->user->name}! to keep the our campus from overcrowding, kindly leave the campus immediately.")
            ->setImage($image_url);

        return FcmMessage::create()
            ->setData([
                'payload' => json_encode($data),
            ])
            ->setNotification($fcmNotification);
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
