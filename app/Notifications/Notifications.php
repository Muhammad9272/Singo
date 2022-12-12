<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class Notifications extends Notification
{
    use Queueable;
    public $name, $route, $type, $message, $route_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $route, $type, $message, $route_id)
    {
        //
        $this->name = $name;
        $this->route = $route;
        $this->type = $type;
        $this->message = $message;
        $this->route_id = $route_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
        // ,'broadcast'
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }
    public function toDatabase($notifiable)
    {
        return [
           'name'   => $this->name,
           'route'   => $this->route ,
           'type'    => $this->type,
           'message' => $this->message ,
           'route_id' => $this->route_id ,
           'done_by' => 91,
        ];
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
