<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Setting;
use App\SlackSetting;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class EmailVerification extends Notification
{
    use Queueable, SmtpSettings;
    private $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->password = $password;
        $this->user = $user;
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *t('mail::layout')
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['mail'];

        return $via;
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
            ->subject(__('email.emailVerify.subject'))
            ->greeting(__('email.hello').' '.ucwords($this->user->name).'!')
            ->line(__('email.newUser.text'))
            ->line('Email: '.$notifiable->email)
            ->line('Password:  '.$this->password)
            ->line(__('email.emailVerify.text'))
            ->action('Verify', route('front.get-email-verification', $this->user->email_verification_code))
            ->line(__('email.emailVerify.thankyouNote'))
            ->markdown('vendor.notifications.email', [
                'email'=>$this->user->email,
                'regards' => 'yes'
                ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $notifiable->toArray();
    }
}
