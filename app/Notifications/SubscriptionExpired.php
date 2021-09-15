<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpired extends Notification
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
        $lang       =   'mail.expired';

        $greeting   =   __('mail.greeting', ['name' => $notifiable->name]);
        $subject    =   __($lang . '.subject');
        $line_1     =   __($lang . '.line_1');
        $line_2     =   __($lang . '.line_2');
        $action     =   __($lang . '.action');

        return (new MailMessage)
            ->cc(config('mail.cc'))
            ->subject($subject)
            ->greeting($greeting)
            ->line($line_1)
            ->line($line_2)
            ->action($action, route('merchant.login'));
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
