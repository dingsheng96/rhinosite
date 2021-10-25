<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FreeTierAccount extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $lang       =   'mail.free_tier';

        $greeting   =   __('mail.greeting', ['name' => $notifiable->name]);
        $subject    =   __($lang . '.subject');
        $line_1     =   __($lang . '.line_1');
        $line_2     =   __($lang . '.line_2');
        $line_3     =   __($lang . '.line_3');
        $line_4     =   __($lang . '.line_4');
        $line_5     =   __($lang . '.line_5');
        $line_6     =   __($lang . '.line_6');
        $line_7     =   __($lang . '.line_7');
        $line_8     =   __($lang . '.line_8');
        $line_9     =   __($lang . '.line_9');

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($line_1)
            ->line($line_2)
            ->line($line_3)
            ->line($line_4)
            ->line($line_5)
            ->line($line_6)
            ->line($line_7)
            ->line($line_8)
            ->line($line_9);
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
