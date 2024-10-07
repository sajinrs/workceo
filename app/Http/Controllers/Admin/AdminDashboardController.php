<?php

namespace App\Http\Controllers\Admin;

use App\AttendanceSetting;
use App\Company;
use App\Currency;
use App\DashboardWidget;
use App\Helper\Reply;
use App\Invoice;
use App\LeadFollowUp;
use App\Leave;
use App\Libraries\WceoZohoSubscriptions;
use App\LogTimeFor;
use App\Payment;
use App\Project;
use App\ProjectActivity;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Task;
use App\TaskboardColumn;
use App\Ticket;
use App\Traits\CurrencyExchange;
use App\User;
use App\UserActivity;
use App\UserExtra;
use App\OnBoarding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.dashboard';
        $this->pageIcon = 'icon-speedometer';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // invoice block total  due amount
        $this->total_due = 0;
        $invoices = Invoice::whereIn('status',['unpaid','partial'])
            ->leftJoin('currencies', 'invoices.currency_id', '=', 'currencies.id')
            ->get([
                'invoices.*',
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate']);
        foreach ($invoices as $invoice){
            $this->total_due += $this->processAmount($invoice, $invoice->amountDue());
        }
        $company = Company::findOrFail(company()->id);
       

        $this->industries = DB::table('industries')->get();
        $this->findus     = DB::table('find_us')->get();
        $this->interests  = DB::table('interests')->get();

        $this->currency = $company->currency;
        $this->total_due = $company->currency->currency_symbol.$this->total_due;
        // Getting Attendance setting data
        $this->attendanceSettings = AttendanceSetting::first();

        $taskBoardColumn = TaskboardColumn::all();

        $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'incomplete';
        })->first();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        //Getting Maximum Check-ins in a day
        $this->maxAttandenceInDay = $this->attendanceSettings->clockin_in_day;

        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(client_details.id) from `client_details` inner join role_user on role_user.user_id=client_details.user_id inner join users on client_details.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "client" AND roles.company_id = ' . $this->user->company_id . ' AND client_details.company_id = ' . $this->user->company_id . ' and users.status = "active") as totalClients'),
                DB::raw('(select count(DISTINCT(users.id)) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" AND users.company_id = ' . $this->user->company_id . ' and users.status = "active") as totalEmployees'),
                DB::raw('(select count(projects.id) from `projects` WHERE projects.company_id = ' . $this->user->company_id . ') as totalProjects'),
                DB::raw('(select count(invoices.id) from `invoices` where status = "unpaid" AND invoices.company_id = ' . $this->user->company_id . ') as totalUnpaidInvoices'),
                DB::raw('(select sum(project_time_logs.total_minutes) from `project_time_logs` WHERE project_time_logs.company_id = ' . $this->user->company_id . ') as totalHoursLogged'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id=' . $completedTaskColumn->id . ' AND tasks.company_id = ' . $this->user->company_id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id=' . $incompletedTaskColumn->id . ' AND tasks.company_id = ' . $this->user->company_id . ') as totalPendingTasks'),
                DB::raw('(select count(attendances.id) from `attendances` inner join users as atd_user on atd_user.id=attendances.user_id where DATE(attendances.clock_in_time) = CURDATE()  AND attendances.company_id = ' . $this->user->company_id . ' and atd_user.status = "active") as totalTodayAttendance'),
                //                DB::raw('(select count(issues.id) from `issues` where status="pending") as totalPendingIssues'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="open" or status="pending") AND tickets.company_id = ' . $this->user->company_id . ') as totalUnResolvedTickets'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="resolved" or status="closed") AND tickets.company_id = ' . $this->user->company_id . ') as totalResolvedTickets')
            )
            ->first();

        $timeLog = intdiv($this->counts->totalHoursLogged, 60) . ' ' .__('modules.hrs');

        if (($this->counts->totalHoursLogged % 60) > 0) {
            $timeLog .= ($this->counts->totalHoursLogged % 60) . ' '. __('modules.mins');
        }

        $this->counts->totalHoursLogged = $timeLog;

        $this->pendingTasks = Task::with('project')
            ->where('tasks.board_column_id', $incompletedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->orderBy('due_date', 'desc')
            ->get();
        $this->pendingLeadFollowUps = LeadFollowUp::with('lead')->where(DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->where('leads.next_follow_up', 'yes')
            ->where('leads.company_id', company()->id)
            ->get();

        $this->newTickets = Ticket::where('status', 'open')
            ->orderBy('id', 'desc')->get();

        $this->projectActivities = ProjectActivity::with('project')
            ->join('projects', 'projects.id', '=', 'project_activity.project_id')
            ->whereNull('projects.deleted_at')->select('project_activity.*')
            ->limit(15)->orderBy('id', 'desc')->get();
        $this->userActivities = UserActivity::with('user')->limit(15)->orderBy('id', 'desc')->get();

        $this->feedbacks = Project::with('client')->whereNotNull('feedback')->limit(5)->get();



        // earning chart
        $this->currencies = Currency::all();
        $this->currentCurrencyId = $this->global->currency_id;

        $this->fromDate = Carbon::today()->timezone($this->global->timezone)->subDays(60);
        $this->toDate = Carbon::today()->timezone($this->global->timezone);
        $invoices = DB::table('payments')
            ->join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->where('paid_on', '>=', $this->fromDate)
            ->where('paid_on', '<=', $this->toDate)
            ->where('payments.status', 'complete')
            ->where('payments.company_id', company()->id)
            ->groupBy('paid_on')
            ->orderBy('paid_on', 'ASC')
            ->get([
                DB::raw('DATE_FORMAT(paid_on,"%Y-%m-%d") as date'),
                DB::raw('sum(amount) as total'),
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate'
            ]);

        $chartData = array();
        foreach ($invoices as $chart) {
            if ($chart->currency_code != $this->global->currency->currency_code) {
                if ($chart->is_cryptocurrency == 'yes') {
                    if ($chart->exchange_rate == 0) {
                        if ($this->updateExchangeRates()) {
                            $usdTotal = ($chart->total * $chart->usd_price);
                            $chartData[] = ['date' => $chart->date, 'total' => floor($usdTotal / $chart->exchange_rate)];
                        }
                    } else {
                        $usdTotal = ($chart->total * $chart->usd_price);
                        $chartData[] = ['date' => $chart->date, 'total' => floor($usdTotal / $chart->exchange_rate)];
                    }
                } else {
                    if ($chart->exchange_rate == 0) {
                        if ($this->updateExchangeRates()) {
                            $chartData[] = ['date' => $chart->date, 'total' => floor($chart->total / $chart->exchange_rate)];
                        }
                    } else {
                        $chartData[] = ['date' => $chart->date, 'total' => floor($chart->total / $chart->exchange_rate)];
                    }
                }
            } else {
                $chartData[] = ['date' => $chart->date, 'total' => round($chart->total, 2)];
            }
        }

        $this->chartData = json_encode($chartData);
        $this->leaves = Leave::where('status', '<>', 'rejected')->get();


        $this->logTimeFor = LogTimeFor::first();

        $this->activeTimerCount = ProjectTimeLog::with('user')
            ->whereNull('project_time_logs.end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id');

        if ($this->logTimeFor != null && $this->logTimeFor->log_time_for == 'task') {
            $this->activeTimerCount = $this->activeTimerCount->join('tasks', 'tasks.id', '=', 'project_time_logs.task_id');
            $projectName = 'tasks.heading as project_name';
        } else {
            $this->activeTimerCount = $this->activeTimerCount->join('projects', 'projects.id', '=', 'project_time_logs.project_id');
            $projectName = 'projects.project_name';
        }

        $this->activeTimerCount = $this->activeTimerCount
            ->select('project_time_logs.*', $projectName, 'users.name')
            ->count();

        //$this->widgets       = DashboardWidget::all();
        $this->widgets       =  DashboardWidget::orderBy('sort_order','asc')->get();
        
        
        //Add Action bar widget
        $actionBar = DashboardWidget::where('widget_name', 'user_action_menu_bar')->count();
        if($actionBar == 0)
        {
            $actionBarWidget = new DashboardWidget;
            $actionBarWidget->company_id  = company()->id;
            $actionBarWidget->widget_name = 'user_action_menu_bar';
            $actionBarWidget->status = 0;
            $actionBarWidget->save();
        }

        $this->activeWidgets = DashboardWidget::where('status', 1)->get()->pluck('widget_name')->toArray();

        if($company->package->default == 'yes'){
            // get subscription details
            $zoho_subscription = DB::table("zoho_subscriptions")
                ->join('packages', 'packages.id', 'zoho_subscriptions.package_id')
                ->where('zoho_subscriptions.company_id', company()->id)
                ->orderByDesc('zoho_subscriptions.id')
                ->first();
                
                $date_now = date("Y-m-d");
                if ($date_now > $zoho_subscription->next_billing_date) {
                    $this->planExpire = 'yes';
                }else{
                    $this->planExpire = 'no';
                }

                //$this->planExpire = 'no';
               
            if($zoho_subscription) {
                if ($zoho_subscription->subscription_id) {
                    $zohoSubscription = WceoZohoSubscriptions::getSubscriptionById($zoho_subscription->subscription_id);
                    if ($zohoSubscription['status'] == 'success') {
                        $this->zoho_subscription_details = $zohoSubscription['data'];
                    }
                }
            }

        }

        return view('admin.dashboard.index', $this->data);
    }
    

    public function widget(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        DashboardWidget::where('status', 1)->update(['status' => 0]);

        foreach ($data as $key => $widget) {
            DashboardWidget::where('widget_name', $key)->update(['status' => 1]);
        }

        return Reply::redirect(route('admin.dashboard'), __('messages.updatedSuccessfully'));
    }
    

    public function jobBlock($date_range, Request $request){
        //die($date_range);
        if(!$request->ajax()){
            return Reply::dataOnly(['status' => 'failed']);
        }
        // calculate YTD
        $query_ytd = Project::where(function ($q) {
                        $q->where('start_date', '<=',Carbon::now()->startOfYear());
                        $q->orWhere('start_date', '<=',Carbon::today());
                    })->where(function ($q) {
                        $q->where('deadline', '>=',Carbon::now()->startOfYear());
                        $q->orWhere('deadline', '>=',Carbon::today());
                    });
        $this->ytd = $query_ytd->count();
        if($date_range <= 0){
            $d_r = $date_range* -1;
            // prior period count
            $query_p_p =  Project::where(function ($q) use($d_r) {
                $q->where('start_date', '<=',Carbon::now()->subDays(($d_r*2)));
                $q->orWhere('start_date', '<=',Carbon::now()->subDays($d_r));
            })->where(function ($q) use($d_r) {
                $q->where('deadline', '>=',Carbon::now()->subDays(($d_r*2)));
                $q->orWhere('deadline', '>=',Carbon::now()->subDays($d_r));
            });
        }else{
            $d_r = $date_range* 1;
            // prior period count
            $query_p_p =  Project::where(function ($q) use($d_r) {
                $q->where('start_date', '>=',Carbon::today());
                $q->orWhere('start_date', '<=',Carbon::now()->addDays($d_r));
            })->where(function ($q) use($d_r) {
                $q->where('deadline', '>=',Carbon::today());
               // $q->orWhere('deadline', '>=',Carbon::now()->subDays($d_r));
            });
        }


        $this->project_p_p_count = $project_p_p_count = $query_p_p->count();

        //project count & graph data
        $this->project_count = 0;
        if($date_range <= 0) {
            $d_r = $date_range* -1;
            $query_d_g = Project::groupBy('category_id')
                ->selectRaw('count(*) as total, category_id')
                ->where(function ($q) use ($d_r) {
                    $q->where('start_date', '<=', Carbon::now()->subDays($d_r));
                    $q->orWhere('start_date', '<=', Carbon::today());
                })->where(function ($q) use ($d_r) {
                    $q->where('deadline', '>=', Carbon::now()->subDays($d_r));
                    $q->orWhere('deadline', '>=', Carbon::today());
                });
        }else{
            $d_r = $date_range* 1;
            $query_d_g = Project::groupBy('category_id')
                ->selectRaw('count(*) as total, category_id')
                ->where(function ($q) use ($d_r) {
                    $q->where('start_date', '>=', Carbon::today());
                    $q->orWhere('start_date', '<=', Carbon::now()->addDays($d_r));
                })->where(function ($q) use ($d_r) {
                    $q->where('deadline', '>=', Carbon::today());
                   // $q->orWhere('deadline', '>=', Carbon::today());
                });
        }
        $project_d_g =  $query_d_g->get();
        $graph_data = array();
        foreach ($project_d_g as $p_d_g){
            $hex = '#';
            // random color
            foreach(array('r', 'g', 'b') as $color){
                //Random number between 0 and 255.
                $val = mt_rand(100, 255);
                //Convert the random number into a Hex value.
                $dechex = dechex($val);
                //Pad with a 0 if length is less than 2.
                if(strlen($dechex) < 2){
                    $dechex = "0" . $dechex;
                }
                //Concatenate
                $hex .= $dechex;
            }


            $graph_data[] = ['value'=>$p_d_g->total, 'label'=> (!is_null($p_d_g->category))?$p_d_g->category->category_name:'nil', 'color'=> "$hex"];

            $this->project_count += $p_d_g->total;
        }
        $this->graph_data = json_encode($graph_data);

        // % from prior period calculation
        $pp_count_diff = $this->project_count - $project_p_p_count;
        if($project_p_p_count != 0){
            $rate = ($pp_count_diff/$project_p_p_count)*100;
        }else{
            $rate = $pp_count_diff*100;
        }
        $rate = round($rate,2);
        if($pp_count_diff > 0){
            $rate = '<span class="txt-success">+'.$rate.'%</span>';
        }else if($pp_count_diff < 0){
            $rate = '<span class="txt-danger">'.$rate.'%</span>';
        }else{
            $rate = '0%';
        }
        $this->pp_rate = $rate;

        $this->style = "";
        if($this->project_count > 99){
            $this->style = "font-size:3rem";
        }
        if($this->project_count > 999){
            $this->style = "font-size:2.2rem";
        }

        $view = view('admin.dashboard.jobs_block', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function revenueBlock($date_range, Request $request){
        if(!$request->ajax()){
            return Reply::dataOnly(['status' => 'failed']);
        }
        // calculate YTD
        /*$query_ytd = Payment::select(DB::raw('sum(amount / currencies.exchange_rate) as total'))
            ->leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where(function ($q) use($date_range) {
                $q->where('paid_on', '>=',Carbon::now()->startOfYear());
                $q->where('paid_on', '<=',Carbon::today());
            })->where('payments.status', 'complete')
            ->first();
        $this->ytd = round($query_ytd->total,2);*/


        $this->ytd = 0;
        $payments_ytd = Payment::leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where(function ($q) use($date_range) {
                $q->where('paid_on', '>=',Carbon::now()->startOfYear());
                $q->where('paid_on', '<=',Carbon::now());
            })
            ->where('payments.status', 'complete')
            ->get([
                'payments.*',
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate'
            ]);

        foreach($payments_ytd as $payment){
            $this->ytd += $this->processAmount($payment, $payment->amount);
        }


        /*$payment_res = Payment::select(DB::raw('sum(amount / currencies.exchange_rate) as total'))
            ->leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where(function ($q) use($date_range) {
            $q->where('paid_on', '>=',Carbon::now()->subDays($date_range));
            $q->where('paid_on', '<=',Carbon::today());
        })
        ->first();

        $this->payment_total = (round($payment_res->total,0))??0;*/

        $this->payment_total = 0;
        $payment_res = Payment::leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where(function ($q) use($date_range) {
                if($date_range <= 0) {
                    $d_r = $date_range * -1;
                    $q->where('paid_on', '>=', Carbon::now()->subDays($d_r));
                    $q->where('paid_on', '<=', Carbon::now());
                }else{
                    $d_r = $date_range * 1;
                    $q->where('paid_on', '>=', Carbon::now());
                    $q->where('paid_on', '<=', Carbon::now()->addDays($d_r));
                }
            })
            ->where('payments.status', 'complete')
            ->get([
                'payments.*',
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate'
            ]);


        foreach($payment_res as $payment){
            $this->payment_total += $this->processAmount($payment, $payment->amount);
        }


        // prior period total
        /*$payment_p_p = Payment::select(DB::raw('sum(amount / currencies.exchange_rate) as total'))
            ->leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where(function ($q) use($date_range) {
                $q->where('paid_on', '>=',Carbon::now()->subDays($date_range*2));
                $q->where('paid_on', '<=',Carbon::now()->subDays($date_range));
            })
            ->first();

        $payment_total_p_p = (round($payment_p_p->total,0))??0;*/

        $payment_total_p_p = 0;
        $payment_p_p = Payment::leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
            ->where(function ($q) use($date_range) {
                if($date_range <= 0) {
                    $d_r = $date_range * -1;
                    $q->where('paid_on', '>=', Carbon::now()->subDays($d_r * 2));
                    $q->where('paid_on', '<=', Carbon::now()->subDays($d_r));
                }else{
                    $d_r = $date_range * 1;
                    $q->where('paid_on', '>=', Carbon::now()->subDays($d_r));
                    $q->where('paid_on', '<=', Carbon::now());
                }
            })
            ->where('payments.status', 'complete')
            ->get([
                'payments.*',
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate'
            ]);

        foreach($payment_p_p as $payment){
            $payment_total_p_p += $this->processAmount($payment, $payment->amount);
        }

        // % from prior period calculation
        $pp_count_diff = $this->payment_total - $payment_total_p_p;
        if($payment_total_p_p != 0){
            $rate = ($pp_count_diff/$payment_total_p_p)*100;
        }else{
            $rate = $pp_count_diff*100;
        }

        if($pp_count_diff > 0){
            $rate = '<span class="txt-success">+'.round($rate,2).'%</span>';
        }else if($pp_count_diff < 0){
            $rate = '<span class="txt-danger">'.round($rate,2).'%</span>';
        }else{
            $rate = '0%';
        }
        $this->pp_rate = $rate;


        // default currency
        $company = Company::findOrFail(company()->id);
        $this->currency = $company->currency;

        // change font size
        $this->style = "";
        if($this->payment_total > 999){
            $this->style = "font-size:4.3rem";
        }
        if($this->payment_total > 9999){
            $this->style = "font-size:3.5rem";
        }

        $view = view('admin.dashboard.revenue_block', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function leadboardBlock($date_range, Request $request){
        if(!$request->ajax()){
            return Reply::dataOnly(['status' => 'failed']);
        }

        if($date_range == 'jobs'){
            /*$this->employees  = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->join('employee_details', 'employee_details.user_id', '=', 'users.id' )
               // ->select('users.*')
                ->groupBy('project_members.user_id')
                ->orderBy('projects.id', 'DESC')
                ->limit(20)
                ->get();*/

            $this->employees = User::with(['member', 'employeeDetail.designation'])
                ->has('member')
                ->limit(100)
                ->get();


        }else{
            /*$this->employees = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->join('employee_details', 'employee_details.user_id', '=', 'users.id' )
                //->select('users.id', 'users.name', 'users.email', 'users.created_at')
                ->where('roles.name', '<>', 'client')
                ->groupBy('users.id')
                ->limit(20)
                ->get();*/

            $this->employees = User::with(['employeeDetail.designation'])
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.name', '<>', 'client')
                ->select('users.*', 'roles.name as role_nm')
                ->groupBy('users.id')
                ->orderBy('users.id','DESC')
                ->limit(100)
                ->get();
        }

        $view = view('admin.dashboard.leadboard_block', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function invoiceBlock($date_range, Request $request){
        if(!$request->ajax()){
            return Reply::dataOnly(['status' => 'failed']);
        }
        //paid chart
        $chartDataArray = [];
        if($date_range == 'paid'){
            $this->barColor = '#2750fe';
            $paid_invoices = Invoice::whereIn('invoices.status',['paid'])
                ->leftJoin('payments','payments.invoice_id','=','invoices.id')
                ->whereNotNull('payments.invoice_id')
                ->groupBy('payments.invoice_id')
                ->limit(5)
                ->orderBy('paid_on','DESC')->get([
                    'invoices.*',
                    'payments.paid_on',
                ]);
            foreach ($paid_invoices as $invoice){
                $client_name = '';
                if($invoice->project_id && isset($invoice->project->client)){
                    $client_name .= $invoice->project->client->name;

                }elseif($invoice->client_id && isset($invoice->client)){
                    $client_name .=  $invoice->client->name;
                }
                $chartDataArray[] = ['client'=>$client_name, 'date'=>date('d/m/y',strtotime($invoice->paid_on)), 'total'=>$invoice->amountPaid(), 'currency_symbol'=>$invoice->currency->currency_symbol];
            }
            $this->chartData = json_encode($chartDataArray);
        }

        //due chart
        if($date_range == 'due'){
            $this->barColor = '#e20b0b';
            $unpaid_invoices = Invoice::whereIn('invoices.status',['unpaid'])
                ->leftJoin('payments','payments.invoice_id','=','invoices.id')
                ->whereNull('payments.invoice_id')
                //->groupBy('payments.invoice_id')
                ->orderBy('invoices.updated_at','DESC')
               ->limit(5)
               ->get([
                   'invoices.*'
               ]);

            foreach ($unpaid_invoices as $invoice){
                $client_name = '';
                if($invoice->project_id && isset($invoice->project->client->name)){
                    $client_name .= $invoice->project->client->name;

                }elseif($invoice->client_id){
                    $client_name .=  $invoice->client->name;
                }
                $chartDataArray[] = ['client'=>$client_name, 'date'=>date('d/m/y',strtotime($invoice->updated_at)), 'total'=>$invoice->amountDue(), 'currency_symbol'=>$invoice->currency->currency_symbol];
            }

            $this->chartData = json_encode($chartDataArray);
        }


        $view = view('admin.dashboard.invoice_block', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function mapBlock($date_range, Request $request){
        if(!$request->ajax()){
            return Reply::dataOnly(['status' => 'failed']);
        }
        if($date_range <= 0) {
            $d_r = $date_range * -1;
            // jobs in selected period
            $query_jobs = Project::where(function ($q) use ($d_r) {
                $q->where('start_date', '<=', Carbon::now()->subDays($d_r));
                $q->orWhere('start_date', '<=', Carbon::today());
            })->where(function ($q) use ($d_r) {
                $q->where('deadline', '>=', Carbon::now()->subDays($d_r));
                $q->orWhere('deadline', '>=', Carbon::today());
            });
        }else{
            $d_r = $date_range * 1;
            // jobs in selected period
            $query_jobs = Project::where(function ($q) use ($d_r) {
                $q->where('start_date', '>=', Carbon::today());
                $q->orWhere('start_date', '<=', Carbon::now()->addDays($d_r));
            })->where(function ($q) use ($date_range) {
                //$q->where('deadline', '>=', Carbon::now()->subDays($date_range));
                $q->orWhere('deadline', '>=', Carbon::today());
            });
        }

        $jobs = $query_jobs->get();
        $jobsDataArray = [];
        $membersDataArray = [];
        foreach ($jobs as $job){
            $jobsDataArray[] = ['title'=>$job->project_name,'client_name'=>$job->client->name,'address'=>$job->client->address,'url'=>route('admin.projects.show', [$job->id])];
            if($job->members){
                
                foreach ($job->members as $member){
                    $membersDataArray[$member->user->id]['projects'][] = ['title'=>$job->project_name,'url'=>route('admin.projects.show', [$job->id])];
                    //$membersDataArray[$member->id] = ['member_name'=>$member->user->name,'address'=>$member->user->employeeDetail->address, 'url'=>route('admin.employees.show', [$member->user->id])];
                    $membersDataArray[$member->user->id]['member_name'] = $member->user->name;
                    $membersDataArray[$member->user->id]['address'] = $member->user->employeeDetail->address;
                    $membersDataArray[$member->user->id]['url'] = route('admin.employees.show', [$member->user->id]);
                }
            }
        }
        return Reply::dataOnly(['status' => 'success', 'jobsData' => $jobsDataArray, 'membersData'=>$membersDataArray]);
    }

    private function processAmount($payment, $amount){
        $total = $amount;
        if ($payment->is_cryptocurrency == 'yes') {
            if ($payment->exchange_rate == 0) {
                if ($this->updateExchangeRates()) {
                    $usdTotal = ($amount * $payment->usd_price);
                    $total = floor($usdTotal / $payment->exchange_rate);
                }
            } else {
                $usdTotal = ($amount * $payment->usd_price);
                $total = floor($usdTotal / $payment->exchange_rate);
            }
        } else {
            if ($payment->exchange_rate == 0) {
                if ($this->updateExchangeRates()) {
                    $total = floor($amount / $payment->exchange_rate);
                }
            } else {
                $total = floor($amount / $payment->exchange_rate);
            }
        }

        return $total;
    }

    public function updateCompany(Request $request)
    {        
        $this->validate($request, [
            'source' => 'required',
            'industry' => 'required',
            'company_size' => 'required',
        ]);

        $userDetail = $this->user;
        $company = Company::where('id', '=', $userDetail->company_id)->first();
        $company->company_name  = $request->company_name;
        $company->company_phone = $request->mobile;
        $company->company_size  = $request->company_size;
        $company->industry      = $request->industry;
        $company->address       = $request->cmp_address;
        $company->source        = $request->source;
        $company->interest      = $request->features;
        $company->save();

        //User
        $user         = User::where('id', '=', $userDetail->id)->first();
        $user->name   = $request->name;
        $user->mobile = $request->mobile;
        $user->update();

        // update zoho customer
        $customer_id = WceoZohoSubscriptions::getCustomerId($userDetail->id);
        $customer_data = $user;
        $customer_data->company_name = $company->company_name;
        $zoho_customer_resp = WceoZohoSubscriptions::updateCustomer($customer_id,$customer_data);
        $user_extra = UserExtra::where('user_id',$userDetail->id)->where('key_name','ZOHO_CUSTOMER_DATA')->first();

        if($zoho_customer_resp['status'] == 'success' && $user_extra) {
            // save customer to db
            $user_extra->key_value = json_encode($zoho_customer_resp['data']);
            $user_extra->save();
        }

        return Reply::success(__('messages.uploadSuccess'));
    }


    public function checklistSetup()
    {
        $this->checklistPercentage = 0;
        $this->checklistIDs        = [];
        $this->checklists          = OnBoarding::get();

        $checklistIDs = UserExtra::where(['key_name' => 'ONBOARDING_CHECKLIST', 'user_id' => $this->user->id ])->get()->pluck('key_value')->first();     

        if(!empty($checklistIDs))
        {
            foreach($this->checklists as $boarding)
            {
                $boardingID [] = $boarding->id; 
            }

            $checklistIDExplode        = explode(',',$checklistIDs);
            $deletedBoardID            = array_diff($checklistIDExplode, $boardingID);
            $this->checklistIDs        = array_diff($checklistIDExplode, $deletedBoardID );
            $this->checklistPercentage = 100/count($this->checklists) * count($this->checklistIDs);
        }    
        
        /* echo '<pre>';
        print_r($boardingID);
        print_r($this->checklistIDs); */
        
        

        return view('admin.onboarding.checklist-setup', $this->data);
    }

    public function addChecklist(Request $request)
    {
        $checklist  = UserExtra::where(['key_name' => 'ONBOARDING_CHECKLIST', 'user_id' => $request->user_id ])->first();

        if(!empty($request->checklist_id))
        {
            $checklistIDs = implode(',',$request->checklist_id);
        } else {
            $checklistIDs = '';
        }
        
        if(!empty($checklist))
        {
            $checklistData       = UserExtra::where(['key_name' => 'ONBOARDING_CHECKLIST', 'user_id' => $request->user_id ])->first();
            $checklistData->key_value = $checklistIDs;
            $checklistData->save();

        } else {
            $userExtra = new UserExtra();
            $userExtra->user_id = $request->user_id;
            $userExtra->key_name = 'ONBOARDING_CHECKLIST';
            $userExtra->key_value = $checklistIDs;
            $userExtra->save();
        }

        $checklistCount   = DB::table('onboardings')->count();
        $checklistIDs     = UserExtra::where(['key_name' => 'ONBOARDING_CHECKLIST', 'user_id' => $this->user->id ])->get()->pluck('key_value')->first();
        $checklistIDCount = 0;
        if(!empty($checklistIDs))
        {
            $checklistIDCount = count(explode(',',$checklistIDs));
        }        
        
        $checklistPercentage = 100/$checklistCount * $checklistIDCount;

        return Reply::dataOnly(['status' => 'success', 'message' => 'Checklist Updated!', 'count' => $checklistIDCount, 'percentage' => $checklistPercentage]);
    }


    public function viewOnBoarding($id)
    {
        $this->boarding = OnBoarding::find($id);
        return view('admin.onboarding.show-boarding', $this->data);
    }

}
