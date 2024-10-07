<?php

namespace App\Http\Controllers\Api\Admin;

use App\AttendanceSetting;
use App\Company;
use App\Currency;
use App\DashboardWidget;
use App\Helper\Reply;
use App\Http\Resources\ProjectCollection;
use App\Invoice;
use App\LeadFollowUp;
use App\Leave;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAdminDashboardController extends ApiAdminBaseController
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
        $company = Company::findOrFail(company()->id);
        $taskBoardColumn = TaskboardColumn::all();

        $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'incomplete';
        })->first();
        
        $this->counts = DB::table('users')
        ->select(
            DB::raw('(select count(client_details.id) from `client_details` inner join role_user on role_user.user_id=client_details.user_id inner join users on client_details.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "client" AND roles.company_id = ' . $this->user->company_id . ' AND client_details.company_id = ' . $this->user->company_id . ' and users.status = "active") as totalClients'),
            DB::raw('(select count(DISTINCT(users.id)) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" AND users.company_id = ' . $this->user->company_id . ' and users.status = "active") as totalEmployees'),
            DB::raw('(select count(projects.id) from `projects` WHERE projects.company_id = ' . $this->user->company_id . ') as totalProjects'),
            DB::raw('(select count(invoices.id) from `invoices` where status = "unpaid" AND invoices.company_id = ' . $this->user->company_id . ') as totalUnpaidInvoices'),
            DB::raw('(select sum(project_time_logs.total_minutes) from `project_time_logs` WHERE project_time_logs.company_id = ' . $this->user->company_id . ') as totalHoursLogged'),
            DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id=' . $incompletedTaskColumn->id . ' AND tasks.company_id = ' . $this->user->company_id . ') as totalPendingTasks'),
            DB::raw('(select count(attendances.id) from `attendances` inner join users as atd_user on atd_user.id=attendances.user_id where DATE(attendances.clock_in_time) = CURDATE()  AND attendances.company_id = ' . $this->user->company_id . ' and atd_user.status = "active") as totalTodayAttendance'),
            DB::raw('(select count(tickets.id) from `tickets` where (status="open" or status="pending") AND tickets.company_id = ' . $this->user->company_id . ') as totalUnResolvedTickets'),
            DB::raw('(select count(tickets.id) from `tickets` where (status="resolved" or status="closed") AND tickets.company_id = ' . $this->user->company_id . ') as totalResolvedTickets')
        )
        ->first();
        return ['data' => $this->counts];
    }

    /**
     * @return ProjectCollection
     */
    public function upcomingJobs()
    {$currentDate = date('Y-m-d');
        return new ProjectCollection(Project::with('client')->where('start_date', '>', $currentDate)->get());
    }

    public function updateLocation(Request $request){
        $current_location = json_encode($request->get('current_location'));
        $userExtra = UserExtra::where('user_id',auth('api')->user()->id)->where('key_name','CURRENT_GPS_LOCATION')->first();
        if(!$userExtra){
            $userExtra = new UserExtra();
        }

        $userExtra->user_id = auth('api')->user()->id;
        $userExtra->key_name = 'CURRENT_GPS_LOCATION';
        $userExtra->key_value = $current_location;
        $userExtra->updated_at = date('Y-m-d H:i:s');
        $userExtra->save();
        return response()->json($userExtra);
    }

    
}
