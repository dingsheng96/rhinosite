<?php

namespace App\Notifications;

use App\Models\UserDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountVerified extends Notification implements ShouldQueue
{
    use Queueable;

    public $user_details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserDetail $user_details)
    {
        $this->user_details = $user_details;
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
        return (new MailMessage)
            ->subject(__('mail.merchant_verified.subject', ['status' => __('labels.' . $this->user_details->status)]))
            ->greeting(__('mail.merchant_verified.greetings', ['name' => $notifiable->name]))
            ->line(__('mail.merchant_verified.' . $this->user_details->status))
            ->action(__('mail.merchant_verified.action'), route('dashboard'));
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
