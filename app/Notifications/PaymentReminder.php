<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Invoice;
use App\Http\Controllers\Admin\ManageAllInvoicesController;
use App\SlackSetting;
use App\Task;
use App\Traits\SmtpSettings;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class PaymentReminder extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;
    private $user;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->user = User::findOrFail($invoice->project ? $invoice->project->client_id : $invoice->client_id);
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $invoiceController = new ManageAllInvoicesController();
        $pdfOption = $invoiceController->domPdfObjectForDownload($this->invoice->id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        $url = url('/login');
        $paymentUrl = route('front.invoice', [md5($this->invoice->id)]);
        $serviceDate = date('M d, Y', strtotime($this->invoice->project->start_date) );
        /* if ($this->invoice->project) {
            $content = 'Payment for ' . ucfirst($this->invoice->project->project_name) . ' invoice no. ' . ucfirst($this->invoice->invoice_number) . '<p>
            <b style="color: green">Due On: ' . $this->invoice->due_date->format('d M, Y') . '</b>
        </p>';
        } else {
            $content = 'Payment for invoice no. ' . ucfirst($this->invoice->invoice_number) . '<p>
            <b style="color: green">Due On: ' . $this->invoice->due_date->format('d M, Y') . '</b>
        </p>';
        } */
        /* return (new MailMessage)
            ->subject(__('email.paymentReminder.subject') . ' - ' . config('app.name') . '!')
            ->greeting(__('email.hello') . ' ' . ucwords($this->user->name) . '!')
            ->markdown('mail.payment.reminder', ['url' => $url, 'paymentUrl' => $paymentUrl, 'content' => $content]); */

        return (new MailMessage)
                ->subject('Payment Reminder '.$this->invoice->invoice_number.' is Ready to be Viewed Online.')
                ->greeting('<h1 style="text-align:center">Hello ' . ucwords($this->user->name) . '<br /><span style="color: #74787e;font-weight: normal;">Payment Reminder from '.$this->user->company->company_name.'</span></h1>')
                ->line('Payment reminder for your project on ' .$serviceDate. ' has been created as attached. Thank You. Please click on the link to view your invoice.')
                ->line(__('<span style="color: #000;">Invoice Number:</span> '.$this->invoice->invoice_number.'<br />
                            <span style="color: #000;">Service Date:</span> '.$serviceDate.'<br />
                            <span style="color: #000;">Customer Name:</span> '.$this->invoice->project->client->name.'<br />
                            <span style="color: #000;">Service Address:</span> '.$this->invoice->project->client->address.'<br />
                            <span style="color: #000;">Job Description:</span> <br /> '.$this->invoice->project->project_summary.'<br >
                            <span style="color: #000;">Amount Due:</span> <br /> <span style="font-size:20px;">'.htmlentities($this->invoice->currency->currency_symbol).$this->invoice->total.'</span>'))
                ->line('<p style="text-align:center"><a href="'.$paymentUrl.'" style="background-color: #00b47f;border-top: 10px solid #00b47f;border-right: 18px solid #00b47f;border-bottom: 10px solid #00b47f;border-left: 18px solid #00b47f;color: #FFF;text-decoration: none;font-size: 14px;border-radius: 3px;padding: 0px 20px;">Pay Invoice</a></p>')
                ->action('Login To Dashboard', route('login'))
                //->markdown('mail.payment.reminder', ['url' => $url, 'paymentUrl' => $paymentUrl, 'content' => $content])
                ->level('secondary_footer')
                ->attachData($pdf->output(), $filename . '.pdf')
                ->markdown('vendor.notifications.email', [
                    'email'       =>  $this->user->email,
                    'cmp_email'   =>  $this->user->company->company_email,
                    'cmp_phone'   =>  $this->user->company->company_phone,
                    'cmp_web'     =>  $this->user->company->website,
                    'cmp_address' =>  $this->user->company->address
                ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->invoice->id,
            'created_at' => $this->invoice->created_at->format('Y-m-d H:i:s'),
            'heading' => $this->invoice->invoice_number
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $slack = SlackSetting::first();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(__('email.paymentReminder.subject'));
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject(__('email.paymentReminder.subject'))
            ->body($this->invoice->heading);
    }
}
