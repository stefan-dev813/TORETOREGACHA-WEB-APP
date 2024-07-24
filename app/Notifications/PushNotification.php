<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class PushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    protected $title;
    protected $body;

    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
    */

    public function toFcm($notifiable)
    {
        // return [
        //     'notification' => [
        //         'title' => $this->title,
        //         'body' => $this->body,
        //         'sound' => 'default',
        //     ],
        //     'data' => [
        //         'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        //         'id' => '1',
        //         'status' => 'done'
        //     ],
        // ]; 
        return "1111";
        return FcmMessage::create()
            ->setContent([
                'title' => $this->title,
                'body' => $this->body,
                'sound' => 'default'
            ])
            ->setData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'id' => '1',
                'status' => 'done'
            ])
            ->setPriority('high');
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            //
        ];
    }
}
