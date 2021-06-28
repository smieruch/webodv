<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class DownloadFinished extends Notification implements ShouldQueue
{
    use Queueable;

    //from https://laracasts.com/discuss/channels/laravel/queued-notifications
    public $data;
    //

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        //Log::info("toMail");
        //Log::info(print_r($this,1));
        //Log::info(print_r($notifiable,1));
        $intro = $this->data->intro;
        $url = $this->data->url;
        $subject = $this->data->subject;
        $action_text = $this->data->action_text;
        $bcc = $this->data->bcc;

        //restart worker if you change something here
        
        if ($bcc != "dummy"){
            return (new MailMessage)
                ->line($intro)
                ->subject($subject)
                ->action($action_text, $url)
                ->bcc($bcc)
                ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
                ->line($intro)
                ->subject($subject)
                ->action($action_text, $url)
                ->line('Thank you for using our application!');
        }


        
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
