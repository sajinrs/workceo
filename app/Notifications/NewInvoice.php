<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Http\Controllers\Admin\ManageAllInvoicesController;
use App\Invoice;
use App\SlackSetting;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use App\User;

class NewInvoice extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;
    private $emailSetting;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Invoice Create/Update Notification')->first();
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
        $via = ['database'];

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
        $url = route('front.invoice', md5($this->invoice->id));

        if (($this->invoice->project && !is_null($this->invoice->project->client)) || !is_null($this->invoice->client_id)) {
            // For Sending pdf to email
            $invoiceController = new ManageAllInvoicesController();
            $pdfOption = $invoiceController->domPdfObjectForDownload($this->invoice->id);
            $pdf = $pdfOption['pdf'];
            $filename = $pdfOption['fileName'];
            $serviceDate = date('M d, Y', strtotime($this->invoice->project->start_date) );
            return (new MailMessage)
                ->subject('Your New Invoice '.$this->invoice->invoice_number.' is Ready to be Viewed Online.')
                ->greeting('<h1 style="text-align:center">Hello ' . ucwords($notifiable->name) . '<br /><span style="color: #74787e;font-weight: normal;">New Invoice from '.$notifiable->company->company_name.'</span></h1>')
                ->line('A new invoice for your project on ' .$serviceDate. ' has been created as attached. Thank You. Please click on the link to view your invoice.')
                ->line(__('<span style="color: #000;">Invoice Number:</span> '.$this->invoice->invoice_number.'<br />
                            <span style="color: #000;">Service Date:</span> '.$serviceDate.'<br />
                            <span style="color: #000;">Customer Name:</span> '.$this->invoice->project->client->name.'<br />
                            <span style="color: #000;">Service Address:</span> '.$this->invoice->project->client->address.'<br />
                            <span style="color: #000;">Job Description:</span> <br /> '.$this->invoice->project->project_summary.'<br >
                            <span style="color: #000;">Amount Due:</span> <br /> <span style="font-size:20px;">'.htmlentities($this->invoice->currency->currency_symbol).$this->invoice->total.'</span>'))
                ->line('<p style="text-align:center"><a href="'.route('client.invoices.index').'" style="background-color: #00b47f;border-top: 10px solid #00b47f;border-right: 18px solid #00b47f;border-bottom: 10px solid #00b47f;border-left: 18px solid #00b47f;color: #FFF;text-decoration: none;font-size: 14px;border-radius: 3px;padding: 0px 20px;">Pay Invoice</a></p>')
                ->action('Login To Dashboard', route('login'))
                ->level('secondary_footer')
                ->attachData($pdf->output(), $filename . '.pdf')
                ->markdown('vendor.notifications.email', [
                    'email'       =>  $notifiable->email,
                    'cmp_email'   =>  $notifiable->company->company_email,
                    'cmp_phone'   =>  $notifiable->company->company_phone,
                    'cmp_web'     =>  $notifiable->company->website,
                    'cmp_address' =>  $notifiable->company->address
                ]);
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
        if(!is_null($this->invoice->project_id)){
            return [
                'project_name' => $this->invoice->project->project_name,
            ];
        }
        else{
            return [
                'invoice_number' => $this->invoice->invoice_number,
            ];
        }
        return $this->invoice->toArray();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
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
                ->content('Your New Invoice '.$this->invoice->invoice_number.' is Ready to be Viewed Online.');
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }
}
