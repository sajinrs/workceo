<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\ProjectMember;
use App\Setting;
use App\SlackSetting;
use App\SmtpSetting;
use App\Traits\SmtpSettings;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewProjectMember extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $user;
    private $member;
    private $emailSetting;

    public function __construct(ProjectMember $member) {
        $user = User::findOrFail($member->user_id);
        $this->user = $user;
        $this->member = $member;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Employee Assign to Project')->first();
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable) {

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
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {

        $serviceDate = date('M d, Y', strtotime($this->member->project->start_date) );
        $time        = date('h:i A', strtotime($this->member->project->start_time));
        return (new MailMessage)
            ->subject(__('email.newProjectMember.subject').' '.config('app.name'))
            ->greeting('<h1 style="text-align:center">Hello ' . ucwords($this->user->name) . '<br /><span style="color: #74787e;font-weight: normal;">You Have Been Assigned to a Job.</span></h1>')
            ->line(__('<span style="color: #000;">Job Title:</span> '.$this->member->project->project_name.'<br />
            <span style="color: #000;">Service Date:</span> '.$serviceDate.' at '.$time.'<br />
            <span style="color: #000;">Customer Name:</span> '.$this->member->project->client->name.'<br />
            <span style="color: #000;">Service Address:</span> '.$this->member->project->client->address.'<br />
            <span style="color: #000;">Phone:</span> '.$this->user->company->company_phone.'<br />
            <span style="color: #000;">Job Description:</span> <br /> '.$this->member->project->project_summary))

            ->action(__('email.loginDashboard'), url('/login'))
            ->level('secondary_footer')
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
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable) {

        return $this->member->toArray();
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
        if(count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))){
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content('You have been added as a member to the project - *' . ucwords($this->member->project->project_name) . '*');
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject(__('email.newProjectMember.subject'))
            ->body(ucwords($this->member->project->project_name));
    }
}
