<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FreeTrialSubscription extends Notification
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
        $notifiable->load(['userDetail']);

        $lang       =   'mail.free_trial';

        $userDetail =   $notifiable->userDetail;
        $status     =   $userDetail->status;

        $greeting   =   __('mail.greeting', ['name' => $notifiable->name]);
        $subject    =   __($lang . '.subject', ['status' => __('labels.' . $status)]);
        $line_1     =   __($lang . '.line_1');
        $line_2     =   __($lang . '.line_2');
        $line_3     =   __($lang . '.line_3');
        $line_4     =   __($lang . '.line_4');
        $action     =   __($lang . '.action');

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($line_1)
            ->line($line_2)
            ->line($line_3)
            ->line($line_4)
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
