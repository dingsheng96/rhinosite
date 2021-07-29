<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as NotificationsVerifyEmail;

class VerifyEmail extends NotificationsVerifyEmail
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
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        $lang = 'mail.verify_account.';

        $subnode = $notifiable->is_merchant ? 'merchant' : 'member';

        return (new MailMessage)
            ->subject(__($lang . 'subject'))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name . ',']))
            ->line(__($lang . $subnode . '.line_1'))
            ->action(__($lang . 'action'), $verificationUrl)
            ->line(__($lang . $subnode . '.line_2'))
            ->line(__($lang . $subnode . '.line_3'))
            ->line(__($lang . $subnode . '.line_4'));
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
