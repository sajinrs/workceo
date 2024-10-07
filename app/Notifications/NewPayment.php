<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Payment;
use App\Invoice;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;

class NewPayment extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $payment;
    private $emailSetting;
    //private $invoice;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        //$this->invoice = $invoice;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Payment Create/Update Notification')->first();
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = [];

        if($this->emailSetting->send_email == 'yes'){
            array_push($via, 'mail');
        }

        if($this->emailSetting->send_slack == 'yes'){
            array_push($via, 'slack');
        }

        if($this->emailSetting->send_push == 'yes'){
            array_push($via, OneSignalChannel::class);
        }

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
//        $url = route('client.payments.index');
        $serviceDate = date('M d, Y', strtotime($this->payment->project->start_date) );

        if (($this->payment->project_id && $this->payment->project->client_id != null) || ($this->payment->invoice_id && $this->payment->invoice->client_id != null)) {
            $url = route('front.invoice', md5($this->payment->invoice_id));

            if($this->payment->invoice)
            {
                return (new MailMessage)
                    ->subject('Thank You. Payment Received!')
                    ->greeting('<h1 style="text-align:center">Hello ' . ucwords($notifiable->name) . '<br /><span style="color: #74787e;font-weight: normal;">Payment Received for '.$this->payment->invoice->invoice_number.' from '.$notifiable->company->company_name.'</span></h1>')
                    ->line('This is notification that payment for your project on '.$serviceDate.' has been received. Thank You.')
                    ->line(__('<span style="color: #000;">Invoice Number:</span> '.$this->payment->invoice->invoice_number.'<br />
                    <span style="color: #000;">Service Date:</span> '.$serviceDate.'<br />
                    <span style="color: #000;">Customer Name:</span> '.$this->payment->project->client->name.'<br />
                    <span style="color: #000;">Service Address:</span> '.$this->payment->project->client->address.'<br />
                    <span style="color: #000;">Job Description:</span> <br /> '.$this->payment->project->project_summary.'<br >
                    <span style="color: #000;">Amount:</span> <br /> <span style="font-size:20px;">'.htmlentities($this->payment->currency->currency_symbol).$this->payment->amount.'</span>'))
                    ->action('Login To Dashboard', route('login'))
                    ->level('secondary_footer')
                    ->markdown('vendor.notifications.email', [
                        'email'       =>  $notifiable->email,
                        'cmp_email'   =>  $notifiable->company->company_email,
                        'cmp_phone'   =>  $notifiable->company->company_phone,
                        'cmp_web'     =>  $notifiable->company->website,
                        'cmp_address' =>  $notifiable->company->address
                    ]);
            } else {
                return (new MailMessage)
                    ->subject('Thank You. Payment Received!')
                    ->greeting('<h1 style="text-align:center">Hello ' . ucwords($notifiable->name) . '<br /><span style="color: #74787e;font-weight: normal;">from '.$notifiable->company->company_name.'</span></h1>')
                    ->line('This is notification that payment for your project on '.$serviceDate.' has been received. Thank You.')
                    ->line(__('<span style="color: #000;">Service Date:</span> '.$serviceDate.'<br />
                    <span style="color: #000;">Customer Name:</span> '.$this->payment->project->client->name.'<br />
                    <span style="color: #000;">Service Address:</span> '.$this->payment->project->client->address.'<br />
                    <span style="color: #000;">Job Description:</span> <br /> '.$this->payment->project->project_summary.'<br >
                    <span style="color: #000;">Amount:</span> <br /> <span style="font-size:20px;">'.htmlentities($this->payment->currency->currency_symbol).$this->payment->amount.'</span>'))
                    ->action('Login To Dashboard', route('login'))
                    ->level('secondary_footer')
                    ->markdown('vendor.notifications.email', [
                        'email'       =>  $notifiable->email,
                        'cmp_email'   =>  $notifiable->company->company_email,
                        'cmp_phone'   =>  $notifiable->company->company_phone,
                        'cmp_web'     =>  $notifiable->company->website,
                        'cmp_address' =>  $notifiable->company->address
                    ]);
            }
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
        return $this->payment->toArray();
    }
}
