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

class EmailVerificationSuccess extends Notification
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
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
        $headImg = '<div style="position: relative;top: 32px !important;margin: 0;"><a href="http://workceo.com/welcome"><img src="'.asset('img/mail/welcome_header.jpg').'" style="width:100%;" alt="" /></a></div>';

        return (new MailMessage)
            ->subject('You’re In! Let’s Get to WorkCEO')
            ->greeting(__('<h1 style="text-align:center">'.ucwords($this->user->first_name).', thank you for<br /> choosing WorkCEO to Grow Your Business!</h1>'))
            ->line('Thank you for verifying your email. Click the link below to login and view your custom dashboard where you can create quotes, schedule, invoice, and get paid')
            ->action('Login To Dashboard', route('login'))
            ->line(__("<p style='text-align:center;font-size:14px;'>Our software helps your service business quote, schedule, invoice, and get paid faster. Whether you're looking to get organized, take your operations to the next level, manage your team, or impress your clients, you're in good hands with WorkCEO.</p>"))
            ->line(__('<p style="text-align:center;font-size:14px;"><b>Have questions? We’re Here to Help.</b><br />
            Prefer a quick walk through over the phone with a real person? No problem! <br />
            Give us a call at <a href="tel:1-888-340-9675">1-888-340-9675</a> or <a>book a demonstration</a></p>'))
            ->markdown('vendor.notifications.email', [
                'email'   => $this->user->email,
                'regards' => 'yes',
                'head_img' => $headImg
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
