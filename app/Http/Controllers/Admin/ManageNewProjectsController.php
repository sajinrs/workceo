<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\DataTables\Admin\ProjectsDataTable;
use App\Expense;
use App\Helper\Reply;
use App\Http\Requests\Project\StoreProject;
use App\Payment;
use App\ProjectActivity;
use App\ProjectCategory;
use App\ProjectFile;
use App\ProjectMember;
use App\ProjectVehicle;
use App\ProjectTemplate;
use App\ProjectTimeLog;
use App\Task;
use App\Tax;
use App\TaskboardColumn;
use App\User;
use App\ClientDetails;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\ProductCategory;
use App\Product;
use App\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Project;
use App\Vehicle;
use App\ProjectMilestone;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ProjectProgress;
use App\PaymentGatewayCredentials;


class ManageNewProjectsController extends AdminBaseController
{

    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        //$this->pageTitle = 'app.menu.jobsArchive';
        $this->pageTitle = 'app.menu.jobs';
        $this->pageTitleArchive = 'app.menu.jobsArchive';
        $this->pageIcon = 'fas fa-list-alt';
        $this->middleware(function ($request, $next) {
            if (!in_array('projects', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectsDataTable $dataTable)
    {
        $this->clients = User::allClients();
        $this->totalProjects = Project::count();
        $this->finishedProjects = Project::finished()->count();
        $this->inProcessProjects = Project::inProcess()->count();
        $this->onHoldProjects = Project::onHold()->count();
        $this->canceledProjects = Project::canceled()->count();
        $this->notStartedProjects = Project::notStarted()->count();
        $this->overdueProjects = Project::overdue()->count();

        $this->employees = User::all();
        $this->productCategories = ProductCategory::all();
        $this->taxes = Tax::all();
        $this->countries = Country::all(['id', 'name']);
        $this->currencies = Currency::all();

        //Budget Total
        $this->projectBudgetTotal = Project::sum('project_budget');
        $this->categories = ProjectCategory::all();

        $this->projectEarningTotal = Payment::join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.status', 'complete')
            ->whereNotNull('projects.project_budget')
            ->whereNotNull('payments.project_id')
            ->sum('payments.amount');

        return $dataTable->render('admin.projects.index', $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive()
    {
        $this->totalProjects = Project::onlyTrashed()->count();
        $this->clients = User::allClients();
        return view('admin.projects.archive', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {       
        $this->clients = User::allActiveClients();
        $this->categories = ProjectCategory::all();
        $this->templates = ProjectTemplate::all();
        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->vehicles   = Vehicle::all();

        $this->employees = User::doesntHave('member', 'and', function($query){
            $query->where('project_id', "''");
        })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();

        $project = new Project();
        $this->productCategories = ProductCategory::all();
        
        $this->fields = $project->getCustomFieldGroupsWithFields()->fields;
        return view('admin.projects.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProject $request)
    {
        $memberExistsInTemplate = false;
        if ($request->total == 0 || $request->total == 0.00)
        { 
            

            return Reply::error(__('Invoice Missing'));
        }

        if (empty($request->item_name[0])) {
            return Reply::error(__('messages.itemBlank'));
        }

       
        $project = new Project();
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }
        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');

        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
        }

        $project->start_time = Carbon::createFromFormat($this->global->time_format, $request->start_time);
        $project->end_time = Carbon::createFromFormat($this->global->time_format, $request->end_time);

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }
        $project->client_id = $request->client_id;

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = "disable";
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = "disable";
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = "disable";
        }

        $project->project_budget = $request->project_budget;

        $project->currency_id = $request->currency_id;
        if(!$request->currency_id){
            $project->currency_id = $this->global->currency_id;
        }

        $project->hours_allocated = $request->hours_allocated;
        $project->status = $request->status;
        $project->job_status = $request->job_status;
        $project->completion_percent = $request->completion_percent;     

        $project->save();

        //Add Members process start
        /* if ($request->all_members) 
        {
            $employees = User::doesntHave('member', 'and', function($query){
                $query->where('project_id', "''");
            })
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                ->where('roles.name', 'employee')
                ->get();
            
                foreach($employees as $emp)
                {
                    $member = new ProjectMember();
                    $member->user_id = $emp->id;
                    $member->project_id = $project->id;
                    $member->save();
                    $this->logProjectActivity($project->id, ucwords($emp->name));
                }
        } else {
            $users = $request->user_id;
            if(!empty($users))
            {
                foreach($users as $user)
                {
                    $member = new ProjectMember();
                    $member->user_id = $user;
                    $member->project_id = $project->id;
                    $member->save();
                    $this->logProjectActivity($project->id, ucwords($member->user->name));
                } 
            }       
        } */

        $users = $request->user_id;
        if(!empty($users))
        {
            foreach($users as $user)
            {
                $member = new ProjectMember();
                $member->user_id = $user;
                $member->project_id = $project->id;
                $member->save();
                $this->logProjectActivity($project->id, ucwords($member->user->name));
            }   
        }  

        //End add members       

        $vehicles = $request->vehicle_id;
        if(!empty($vehicles))
        {
            foreach($vehicles as $vehicleID)
            {
                $vehicle = new ProjectVehicle();
                $vehicle->vehicle_id = $vehicleID;
                $vehicle->project_id = $project->id;
                $vehicle->save();
            }  
        }     


        //Invoice Process        
        if ($request->total !=0)
        {           
            $items = $request->item_name;
            $itemsSummary = $request->item_summary;
            $cost_per_item = $request->cost_per_item;
            $quantity = $request->quantity;
            $amount = $request->amount;
            $tax = $request->taxes;

            if($quantity)
            {   
                foreach ($quantity as $qty) {
                    if (!is_numeric($qty) && (intval($qty) < 1)) {
                        return Reply::error(__('messages.quantityNumber'));
                    }
                }
            }
            
            if($cost_per_item)
            {
                foreach ($cost_per_item as $rate) {
                    if (!is_numeric($rate)) {
                        return Reply::error(__('messages.unitPriceNumber'));
                    }
                }
            }
            
            if($amount)
            {
                foreach ($amount as $amt) {
                    if (!is_numeric($amt)) {
                        return Reply::error(__('messages.amountNumber'));
                    }
                }
            }
            
            if($items)
            {
                foreach ($items as $itm) {
                    if (is_null($itm)) {
                        return Reply::error(__('messages.itemBlank'));
                    }
                }
            }       

            $invoiceSetting = InvoiceSetting::first();
            $invoice = new Invoice();
            $invoice->project_id = $project->id;
            $invoice->client_id = $request->client_id;
            $invoice->invoice_number = Invoice::count() + 2;
            //$invoice->issue_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
            //$invoice->due_date = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');

            $invoice->issue_date = Carbon::today()->format('Y-m-d');
            $invoice->due_date = Carbon::today()->addDays($invoiceSetting->due_after)->format('Y-m-d');        
            $invoice->sub_total = round($request->sub_total, 2);
            $invoice->discount = round($request->discount_value, 2);
            $invoice->discount_type = $request->discount_type;
            $invoice->total = round($request->total, 2);
            $invoice->currency_id = $request->currency_id;
            $invoice->recurring = 'no';        
            $invoice->save();

            /* if ($request->estimate_id) {
                $estimate = Estimate::findOrFail($request->estimate_id);
                $estimate->status = 'accepted';
                $estimate->save();
            }
            if ($request->proposal_id) {
                $proposal = Proposal::findOrFail($request->proposal_id);
                $proposal->invoice_convert = 1;
                $proposal->save();
            } */
        
            if(!empty($items))
            {
                foreach ($items as $key => $item) :
                    if (!is_null($item)) {
        
                        $invoiceItems               = new InvoiceItems();
                        $invoiceItems->invoice_id   = $invoice->id;
                        $invoiceItems->item_name    = $item;
                        $invoiceItems->item_summary = $itemsSummary[$key] ? $itemsSummary[$key] : '';
                        $invoiceItems->type         = 'item';
                        $invoiceItems->quantity     = $quantity[$key];
                        $invoiceItems->unit_price   = round($cost_per_item[$key], 2);
                        $invoiceItems->amount       = round($amount[$key], 2);
                        $invoiceItems->taxes        = $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null;
                        $invoiceItems->save();
                        
                    }
                endforeach;
            }
        }     

        //End Invoice process

        

        if ($request->template_id) {
            $template = ProjectTemplate::findOrFail($request->template_id);
            foreach ($template->members as $member) {
                $projectMember = new ProjectMember();

                $projectMember->user_id    = $member->user_id;
                $projectMember->project_id = $project->id;
                $projectMember->save();

                if ($member->user_id == $this->user->id) {
                    $memberExistsInTemplate = true;
                }
            }
            foreach ($template->tasks as $task) {
                $projectTask = new Task();

                $projectTask->user_id     = $task->user_id;
                $projectTask->project_id  = $project->id;
                $projectTask->heading     = $task->heading;
                $projectTask->description = $task->description;
                $projectTask->due_date    = Carbon::now()->addDay()->format('Y-m-d');
                $projectTask->status      = 'incomplete';
                $projectTask->save();
            }
        }

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $project->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $selfMember = ProjectMember::where(['project_id' => $project->id, 'user_id' => $this->user->id])->count();
        // Assign Self as project member
        if ($request->has('default_project_member') && $request->default_project_member == 'true' && !$memberExistsInTemplate && $selfMember == 0) {
            $member = new ProjectMember();
            $member->user_id = $this->user->id;
            $member->project_id = $project->id;
            $member->save();

            $this->logProjectActivity($project->id, ucwords($this->user->name) . ' ' . __('messages.isAddedAsProjectMember'));
        }

        $this->logSearchEntry($project->id, 'Project: ' . $project->project_name, 'admin.projects.show', 'project');

        $this->logProjectActivity($project->id, ucwords($project->project_name) . ' ' . __("messages.addedAsNewProject"));

        //return Reply::dataOnly(['projectID' => $project->id]);
        if(isset($request->created_from)){
            return Reply::success(__('modules.projects.projectUpdated'));
        }
        
        return Reply::redirect(route('admin.projects.index'), __('modules.projects.projectUpdated'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project      = Project::findOrFail($id)->withCustomFields();
        $this->fields       = $this->project->getCustomFieldGroupsWithFields()->fields;
        $this->invoices     = $this->project->invoices->all();

        $this->taxes        = Tax::all();

        $this->activeTimers = ProjectTimeLog::projectActiveTimers($this->project->id);
        $this->openTasks = Task::projectOpenTasks($this->project->id);
        $this->openTasksPercent = (count($this->openTasks) == 0 ? "0" : (count($this->openTasks) / count($this->project->tasks)) * 100);
        $this->daysLeft = 0;
        $this->daysLeftFromStartDate = 0;
        $this->daysLeftPercent = 0;
        $this->credentials = PaymentGatewayCredentials::first();

        if (is_null($this->project->deadline)) {
            $this->daysLeft = 0;
        } else {
            if ($this->project->deadline->isPast()) {
                $this->daysLeft = 0;
            } else {
                $this->daysLeft = $this->project->deadline->diff(Carbon::now())->format('%d') + ($this->project->deadline->diff(Carbon::now())->format('%m') * 30) + ($this->project->deadline->diff(Carbon::now())->format('%y') * 12);
            }

            $this->daysLeftFromStartDate = $this->project->deadline->diff($this->project->start_date)->format('%d') + ($this->project->deadline->diff($this->project->start_date)->format('%m') * 30) + ($this->project->deadline->diff($this->project->start_date)->format('%y') * 12);

            $this->daysLeftPercent = ($this->daysLeftFromStartDate == 0 ? "0" : (($this->daysLeft / $this->daysLeftFromStartDate) * 100));
        }


        $this->hoursLogged = ProjectTimeLog::projectTotalMinuts($this->project->id);

        $hour = intdiv($this->hoursLogged, 60);
        $minute = 0;
        if (($this->hoursLogged % 60) > 0) {
            $minute = ($this->hoursLogged % 60);
            $this->hoursLogged = $hour . 'hrs ' . $minute . ' mins';
        } else {
            $this->hoursLogged = $hour;
        }

        $this->recentFiles = ProjectFile::where('project_id', $this->project->id)->orderBy('id', 'desc')->limit(10)->get();
        $this->activities = ProjectActivity::getProjectActivities($id, 10);
        //        $this->completedTasks = Task::projectCompletedTasks($this->project->id);
        $this->earnings = Payment::where('status', 'complete')
            ->where('project_id', $id)
            ->sum('amount');
        $this->expenses = Expense::where(['project_id' => $id, 'status' => 'approved'])->sum('price');
        $this->milestones = ProjectMilestone::with('currency')->where('project_id', $id)->get();

        if ($this->project->status == 'in progress') {
            $this->statusText = __('app.inProgress');
            $this->statusTextColor = 'text-info';
            $this->btnTextColor = 'btn-info';
        } else if ($this->project->status == 'on hold') {
            $this->statusText = __('app.onHold');
            $this->statusTextColor = 'text-warning';
            $this->btnTextColor = 'btn-warning';
        } else if ($this->project->status == 'not started') {
            $this->statusText = __('app.notStarted');
            $this->statusTextColor = 'text-warning';
            $this->btnTextColor = 'btn-warning';
        } else if ($this->project->status == 'canceled') {
            $this->statusText = __('app.canceled');
            $this->statusTextColor = 'text-danger';
            $this->btnTextColor = 'btn-danger';
        } else if ($this->project->status == 'finished') {
            $this->statusText = __('app.finished');
            $this->statusTextColor = 'text-success';
            $this->btnTextColor = 'btn-success';
        }

        return view('admin.projects.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->clients    = User::allActiveClients();
        $this->categories = ProjectCategory::all();
        $this->project    = Project::findOrFail($id)->withCustomFields();
        $this->fields     = $this->project->getCustomFieldGroupsWithFields()->fields;
        $this->currencies = Currency::all();
        $this->taxes      = Tax::all();
        $this->productCategories = ProductCategory::all();
        $this->vehicles   = Vehicle::all();
        $ivoiceId         = Invoice::select('id')->where('project_id',$id)->first();
        if($ivoiceId){
            $this->invoice    = Invoice::findOrFail($ivoiceId['id'] );
        }
        
        $this->employees  = User::doesntHave('member', 'and', function($query){
            $query->where('project_id', "''");
        })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        return view('admin.projects.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProject $request, $id)
    {
        $project  = Project::findOrFail($id);
        $ivoiceId = Invoice::select('id')->where('project_id',$id)->value('id');
        
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }
        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');

        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
        } else {
            $project->deadline = null;
        }

        $project->start_time = Carbon::createFromFormat($this->global->time_format, $request->start_time);
        $project->end_time = Carbon::createFromFormat($this->global->time_format, $request->end_time);

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = "disable";
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = "disable";
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = "disable";
        }        

        $project->client_id = ($request->client_id == 'null' || $request->client_id == '') ? null : $request->client_id;
        $project->feedback = $request->feedback;

        if ($request->calculate_task_progress) {
            $project->calculate_task_progress = $request->calculate_task_progress;
            $project->completion_percent = $this->calculateProjectProgress($id);
        } else {
            $project->calculate_task_progress = "false";
            $project->completion_percent = $request->completion_percent;
        }


        $project->project_budget = $request->project_budget;
        $project->currency_id = $request->currency_id;
        $project->hours_allocated = $request->hours_allocated;
        if($project->job_status == 'closed') {
            $project->status = 'awaiting invoice';
            $project->job_status = 'invoice';
        } else {
            $project->status = $request->status;
            $project->job_status = $request->job_status;
        }
        
        $project->completion_percent = $request->completion_percent;        

        $project->save();

        //Invoices Start
        $items = $request->input('item_name');
        $itemsSummary = $request->input('item_summary');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $amount = $request->input('amount');
        $tax = $request->input('taxes');

        if($quantity)
        {
            foreach ($quantity as $qty) {
                if (!is_numeric($qty) && $qty < 1) {
                    return Reply::error(__('messages.quantityNumber'));
                }
            }
        }
        
        if($cost_per_item){
            foreach ($cost_per_item as $rate) {
                if (!is_numeric($rate)) {
                    return Reply::error(__('messages.unitPriceNumber'));
                }
            }
        }
        
        if($amount){
            foreach ($amount as $amt) {
                if (!is_numeric($amt)) {
                    return Reply::error(__('messages.amountNumber'));
                }
            }
        }
        
        if($items){
            foreach ($items as $itm) {
                if (is_null($itm)) {
                    return Reply::error(__('messages.itemBlank'));
                }
            }
        }
         // To add custom fields data
        
         if ($request->get('custom_fields_data')) {
            $project->updateCustomFieldData($request->get('custom_fields_data'));
        }
        
        
        if(empty($ivoiceId))
        {
            $invoice = new Invoice();
            $invoice->project_id = $id;
            $invoice->client_id = $request->project_id == '' && $request->has('client_id') ? $request->client_id : null;
            $invoice->invoice_number = Invoice::count() + 2;
            $invoice->issue_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
            $invoice->due_date = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
            $invoice->sub_total = round($request->sub_total, 2);
            $invoice->discount = round($request->discount_value, 2);
            $invoice->discount_type = $request->discount_type;
            $invoice->total = round($request->total, 2);
            $invoice->currency_id = $request->currency_id;
            $invoice->save();   

            if(!empty($items))
            {
                foreach ($items as $key => $item) :
                    if (!is_null($item)) {
        
                        $invoiceItems               = new InvoiceItems();
                        $invoiceItems->invoice_id   = $invoice->id;
                        $invoiceItems->item_name    = $item;
                        $invoiceItems->item_summary = $itemsSummary[$key] ? $itemsSummary[$key] : '';
                        $invoiceItems->type         = 'item';
                        $invoiceItems->quantity     = $quantity[$key];
                        $invoiceItems->unit_price   = round($cost_per_item[$key], 2);
                        $invoiceItems->amount       = round($amount[$key], 2);
                        $invoiceItems->taxes        = $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null;
                        $invoiceItems->save();
                        
                    }
                endforeach;

                
            }

        } else {

            $invoice = Invoice::findOrFail($ivoiceId);

            
            if ($invoice->status == 'paid') {
                return Reply::error(__('messages.invalidRequest'));
            }              
            
            $invoice->project_id = $id;
            $invoice->client_id = $request->project_id == '' && $request->has('client_id') ? $request->client_id : null;
            $invoice->issue_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
            $invoice->due_date = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
            $invoice->sub_total = round($request->sub_total, 2);
            $invoice->discount = round($request->discount_value, 2);
            $invoice->discount_type = $request->discount_type;
            $invoice->total = round($request->total, 2);
            $invoice->currency_id = $request->currency_id;
            $invoice->save(); 
            
            // delete and create new
            InvoiceItems::where('invoice_id', $ivoiceId)->delete();

            if(!empty($items))
            {
                foreach ($items as $key => $item) :
                    InvoiceItems::create(
                        [
                            'invoice_id' => $invoice->id,
                            'item_name' => $item,
                            'item_summary' => $itemsSummary[$key],
                            'type' => 'item',
                            'quantity' => $quantity[$key],
                            'unit_price' => round($cost_per_item[$key], 2),
                            'amount' => round($amount[$key], 2),
                            'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                        ]
                    );
                endforeach;
            }
                   
        }
        
         
        //Invoices End

        //Add Members process start
        /*if ($request->user_id) {
            ProjectMember::where('project_id', $id)->delete();
        }
        

         if ($request->all_members) 
        {
            $employees = User::doesntHave('member', 'and', function($query){
                $query->where('project_id', "''");
            })
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                ->where('roles.name', 'employee')
                ->get();
            
                foreach($employees as $emp)
                {
                    $member = new ProjectMember();
                    $member->user_id = $emp->id;
                    $member->project_id = $project->id;
                    $member->save();
                    $this->logProjectActivity($project->id, ucwords($emp->name));
                }
        } else {
            $users = $request->user_id;
            if(!empty($users))
            {
                foreach($users as $user)
                {
                    $member = new ProjectMember();
                    $member->user_id = $user;
                    $member->project_id = $project->id;
                    $member->save();
                    $this->logProjectActivity($project->id, ucwords($member->user->name));
                }  
            }
                  
        } */

        $users = $request->user_id;
        if(!empty($users))
        {
            ProjectMember::where('project_id', $id)->delete();
            foreach($users as $user)
            {
                $member = new ProjectMember();
                $member->user_id = $user;
                $member->project_id = $project->id;
                $member->save();
                $this->logProjectActivity($project->id, ucwords($member->user->name));
            }   
        }   
        //End add members

        $vehicles = $request->vehicle_id;
        if(!empty($vehicles))
        {
            ProjectVehicle::where('project_id', $project->id)->delete();
            foreach($vehicles as $vehicleID)
            {
                $vehicle = new ProjectVehicle();
                $vehicle->vehicle_id = $vehicleID;
                $vehicle->project_id = $project->id;
                $vehicle->save();
            }  
        }       

        $this->logProjectActivity($project->id, ucwords($project->project_name) . __('modules.projects.projectUpdated'));
        return Reply::redirect(route('admin.projects.index'), __('messages.projectUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $project = Project::withTrashed()->findOrFail($id);
        $project->forceDelete();

        return Reply::success(__('messages.projectDeleted'));
    }

    public function showArchive($id)
    {
        $this->project      = Project::withTrashed()->findOrFail($id);      
        return view('admin.projects.archive-show', $this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function archiveDestroy($id)
    {

        Project::destroy($id);

        return Reply::success(__('messages.projectArchiveSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function archiveRestore($id)
    {

        $project = Project::withTrashed()->findOrFail($id);
        $project->restore();

        return Reply::success(__('messages.projectRevertSuccessfully'));
    }

    public function archiveData(Request $request)
    {
        $projects = Project::select('id', 'project_name', 'start_date', 'deadline', 'client_id', 'completion_percent');

        if (!is_null($request->status) && $request->status != 'all') {
            if ($request->status == 'incomplete') {
                $projects->where('completion_percent', '<', '100');
            } elseif ($request->status == 'complete') {
                $projects->where('completion_percent', '=', '100');
            }
        }

        if (!is_null($request->client_id) && $request->client_id != 'all') {
            $projects->where('client_id', $request->client_id);
        }

        $projects->onlyTrashed()->get();

        return DataTables::of($projects)
            ->addColumn('action', function ($row) {

                
                return '<div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                        <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <a href="' . route('admin.projects.archive-details', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . trans('app.view') .'</a> 

                                <a href="javascript:;" class="revert" data-user-id="' . $row->id . '" data-original-title="Restore"><i class="fa fa-undo"></i> Restore</a>
                                
                                <a href="javascript:;" data-user-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>
                             
                      </div>  </div>
                    </div>
                </div>';

                /* return '
                     <a href="javascript:;" class="btn btn-outline-info"
                    data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-outline-info"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Restore"><i class="fa fa-undo" aria-hidden="true"></i></a>
                       <a href="javascript:;" class="btn btn-outline-danger sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>'; */
            })
            ->addColumn('members', function ($row) {
                $members = '';

                if (count($row->members) > 0) {
                    foreach ($row->members as $member) {
                        $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->user->name) . '" src="' . $member->user->image_url . '"
                        alt="user" class="img-circle" width="30" height="30"> ';
                    }
                } else {
                    $members .= __('messages.noMemberAddedToProject');
                }
                return $members;
            })
            ->editColumn('project_name', function ($row) {
                return ucfirst($row->project_name);
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format('d M, Y');
            })
            ->editColumn('deadline', function ($row) {
                if ($row->deadline) {
                    return $row->deadline->format($this->global->date_format);
                }

                return '-';
            })
            ->editColumn('client_id', function ($row) {
                if (is_null($row->client_id)) {
                    return "";
                }
                return ucwords($row->client->name);
            })
            ->editColumn('completion_percent', function ($row) {
                if ($row->completion_percent < 50) {
                    $statusColor = 'danger';
                    $status = __('app.progress');
                } elseif ($row->completion_percent >= 50 && $row->completion_percent < 75) {
                    $statusColor = 'warning';
                    $status = __('app.progress');
                } else {
                    $statusColor = 'success';
                    $status = __('app.progress');

                    if ($row->completion_percent >= 100) {
                        $status = __('app.completed');
                    }
                }

                return '<h5>' . $status . '<span class="pull-right">' . $row->completion_percent . '%</span></h5><div class="progress">
                  <div class="progress-bar progress-bar-' . $statusColor . '" aria-valuenow="' . $row->completion_percent . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $row->completion_percent . '%" role="progressbar"> <span class="sr-only">' . $row->completion_percent . '% Complete</span> </div>
                </div>';
            })
            ->removeColumn('project_summary')
            ->removeColumn('notes')
            ->removeColumn('category_id')
            ->removeColumn('feedback')
            ->removeColumn('start_date')
            ->rawColumns(['project_name', 'action', 'completion_percent', 'members'])
            ->make(true);
    }

    public function export($status = null, $clientID = null)
    {
        $projects = Project::leftJoin('users', 'users.id', '=', 'projects.client_id')
            ->leftJoin('project_category', 'project_category.id', '=', 'projects.category_id')
            ->select(
                'projects.id',
                'projects.project_name',
                'users.name',
                'project_category.category_name',
                'projects.start_date',
                'projects.deadline',
                'projects.completion_percent',
                'projects.created_at'
            );
        if (!is_null($status) && $status != 'all') {
            if ($status == 'incomplete') {
                $projects = $projects->where('completion_percent', '<', '100');
            } elseif ($status == 'complete') {
                $projects = $projects->where('completion_percent', '=', '100');
            }
        }

        if (!is_null($clientID) && $clientID != 'all') {
            $projects = $projects->where('client_id', $clientID);
        }

        $projects = $projects->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Project Name', 'Client Name', 'Category', 'Start Date', 'Deadline', 'Completion Percent', 'Created at'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($projects as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('Projects', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Projects');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Projects file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));
                });
            });
        })->download('xlsx');
    }

    public function gantt($ganttProjectId = '')
    {

        $data = array();
        $links = array();

        $projects = Project::select('id', 'project_name', 'start_date', 'deadline', 'completion_percent');

        if($ganttProjectId != '')
        {
            $projects = $projects->where('id', '=', $ganttProjectId);
        }

        $projects = $projects->get();

        $id = 0; //count for gantt ids
        foreach ($projects as $project) {
            $id = $id + 1;
            $projectId = $id;

            // TODO::ProjectDeadline to do
            $projectDuration = 0;
            if ($project->deadline) {
                $projectDuration = $project->deadline->diffInDays($project->start_date);
            }

            $data[] = [
                'id' => $projectId,
                'text' => ucwords($project->project_name),
                'start_date' => $project->start_date->format('Y-m-d H:i:s'),
                'duration' => $projectDuration,
                'progress' => $project->completion_percent / 100
            ];

            $tasks = Task::projectOpenTasks($project->id);

            foreach ($tasks as $key => $task) {
                $id = $id + 1;

                $taskDuration = $task->due_date->diffInDays($task->start_date);
                $data[] = [
                    'id' => $id,
                    'text' => ucfirst($task->heading),
                    'start_date' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d H:i:s') : $task->due_date->format('Y-m-d H:i:s'),
                    'duration' => $taskDuration,
                    'parent' => $projectId
                ];

                $links[] = [
                    'id' => $id,
                    'source' => $project->id,
                    'target' => $task->id,
                    'type' => 1
                ];
            }

            $ganttData = [
                'data' => $data,
                'links' => $links
            ];
        }

        $this->ganttProjectId = $ganttProjectId;
        return view('admin.projects.gantt', $this->data);
    }

    public function ganttData($ganttProjectId = '')
    {

        $data = array();
        $links = array();

        $projects = Project::select('id', 'project_name', 'start_date', 'deadline', 'completion_percent');

        if($ganttProjectId != '')
        {
            $projects = $projects->where('id', '=', $ganttProjectId);
        }

        $projects = $projects->get();

        $id = 0; //count for gantt ids
        foreach ($projects as $project) {
            $id = $id + 1;
            $projectId = $id;

            // TODO::ProjectDeadline to do
            $projectDuration = 0;
            if ($project->deadline) {
                $projectDuration = $project->deadline->diffInDays($project->start_date);
            }

            $data[] = [
                'id' => $projectId,
                'text' => ucwords($project->project_name),
                'start_date' => $project->start_date->format('Y-m-d H:i:s'),
                'duration' => $projectDuration,
                'progress' => $project->completion_percent / 100,
                'project_id' => $project->id,
                'dependent_task_id' => null
            ];

            $tasks = Task::projectOpenTasks($project->id);

            foreach ($tasks as $key => $task) {
                $id = $id + 1;

                $taskDuration = $task->due_date->diffInDays($task->start_date);
                $taskDuration = $taskDuration + 1;

                $data[] = [
                    'id' => $task->id,
                    'text' => ucfirst($task->heading),
                    'start_date' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d') : $task->due_date->format('Y-m-d'),
                    'duration' => $taskDuration,
                    'parent' => $projectId,
                    'taskid' => $task->id,
                    'dependent_task_id' => $task->dependent_task_id
                ];

                $links[] = [
                    'id' => $id,
                    'source' => $task->dependent_task_id != '' ? $task->dependent_task_id : $projectId,
                    'target' => $task->id,
                    'type' => $task->dependent_task_id != '' ? 0 : 1
                ];
            }
        }

        $ganttData = [
            'data' => $data,
            'links' => $links
        ];

        return response()->json($ganttData);
    }

    public function updateTaskDuration(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
        $task->save();

        return Reply::success('messages.taskUpdatedSuccessfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $project = Project::find($id)
            ->update([
                'status' => $request->status
            ]);

        return Reply::dataOnly(['status' => 'success']);
    }

    public function ajaxCreate(Request $request, $projectId)
    {
        $this->pageName = 'ganttChart';

        $this->projectId = $projectId;
        $this->projects = Project::all();
        $this->employees = ProjectMember::byProject($projectId);

        $this->parentGanttId = $request->parent_gantt_id;
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        $this->allTasks = [];
        if($completedTaskColumn)
        {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $projectId)
                ->get();
        }

        return view('admin.tasks.ajax_create', $this->data);
    }

    public function burndownChart(Request $request, $id){

        $this->project = Project::with(['tasks' => function($query) use($request){
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $query->where(DB::raw('DATE(`start_date`)'), '>=', Carbon::createFromFormat($this->global->date_format, $request->startDate));
            }

            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $query->where(DB::raw('DATE(`due_date`)'), '<=', Carbon::createFromFormat($this->global->date_format, $request->endDate));
            }
        }])->find($id);

        $this->totalTask = $this->project->tasks->count();
        $datesArray = [];
        $startDate = $request->startDate ? Carbon::createFromFormat($this->global->date_format, $request->startDate) : Carbon::parse($this->project->start_date);
        if ($this->project->deadline){
            $endDate = $request->endDate ? Carbon::createFromFormat($this->global->date_format, $request->endDate) : Carbon::parse($this->project->deadline);
        }
        else{
            $endDate = $request->endDate ? Carbon::parse($request->endDate) : Carbon::now();
        }

        for ($startDate; $startDate<= $endDate; $startDate->addDay()){
            $datesArray[] = $startDate->format($this->global->date_format);
        }

        $uncompletedTasks = [];
        $createdTasks = [];
        $deadlineTasks = [];
        $deadlineTasksCount = [];
        $this->datesArray = json_encode($datesArray);
        foreach ($datesArray as $key => $value){
            if (Carbon::createFromFormat($this->global->date_format, $value)->lessThanOrEqualTo(Carbon::now())){
                $uncompletedTasks[$key] = $this->project->tasks->filter(function($task) use($value){
                    if (is_null($task->completed_on)){
                        return true;
                    }
                    return $task->completed_on ? $task->completed_on->greaterThanOrEqualTo(Carbon::createFromFormat($this->global->date_format, $value)) : false;
                })->count();
                $createdTasks[$key] = $this->project->tasks->filter(function ($task) use($value){
                    return Carbon::createFromFormat($this->global->date_format, $value)->startOfDay()->equalTo($task->created_at->startOfDay());
                })->count();
                if ($key > 0){
                    $uncompletedTasks[$key] += $createdTasks[$key];
                }
            }
            $deadlineTasksCount[] = $this->project->tasks->filter(function($task) use($value){
                return Carbon::createFromFormat($this->global->date_format, $value)->startOfDay()->equalTo($task->due_date->startOfDay());
            })->count();
            if ($key == 0){
                $deadlineTasks[$key] = $this->totalTask - $deadlineTasksCount[$key];
            }
            else{
                $newKey = $key -1;
                $deadlineTasks[$key] = $deadlineTasks[$newKey] - $deadlineTasksCount[$key];
            }
        }

        $this->uncompletedTasks = json_encode($uncompletedTasks);
        $this->deadlineTasks = json_encode($deadlineTasks);
        if ($request->ajax()){
            return $this->data;
        }

        $this->startDate = $request->startDate ? Carbon::parse($request->startDate)->format($this->global->date_format) : Carbon::parse($this->project->start_date)->format($this->global->date_format);
        $this->endDate = $endDate->format($this->global->date_format);

        return view('admin.projects.burndown', $this->data);
    }

    public function getActivities($limit = 10){
        $this->activities = ProjectActivity::getProjectActivities(null, $limit);
        return view('admin.projects.activity-ajax', $this->data);
    }

    public function jobStatus($id)
    {
        $this->project = Project::select('id', 'job_status')->where('id',$id)->get();
        return view('admin.projects.job-status', $this->data);
    }

    public function updateJobStatus(Request $request, $id)
    {
        $project = Project::find($id)
            ->update([
                'job_status' => $request->status,
                'status' => $request->projstatus,
                'completion_percent' => $request->percentage,
                'calculate_task_progress' => 'false'

            ]);

            return Reply::success('Status Updated');
    }



    /**
     * Show the form for copying the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function copy($id)
    {

        $this->clients    = User::allClients();
        $this->categories = ProjectCategory::all();
        $this->project    = Project::findOrFail($id)->withCustomFields();
        $this->fields     = $this->project->getCustomFieldGroupsWithFields()->fields;
        $this->currencies = Currency::all();
        $this->taxes      = Tax::all();
        $ivoiceId         = Invoice::select('id')->where('project_id',$id)->first();
        if($ivoiceId){
            $this->invoice    = Invoice::findOrFail($ivoiceId['id'] );
        }

        $this->employees  = User::doesntHave('member', 'and', function($query){
            $query->where('project_id', "''");
        })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        return view('admin.projects.copy', $this->data);
    }


    public function projectSignModal($id)
    {
        $this->project_id = $id;
        return view('admin.projects.signin', $this->data);
    }

    public function projectSign(Request $request, $id)
    {
        $image = $request->signature;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(32).'.'.'jpg';
        $time = Carbon::now()->toDateTimeString();


        if (!\File::exists(public_path('user-uploads/' . 'project-sign'))) {
            $result = \File::makeDirectory(public_path('user-uploads/project-sign'), 0775, true);
        }

        \File::put(public_path(). '/user-uploads/project-sign/' . $imageName, base64_decode($image));       

        $project = Project::find($id)
            ->update([ 'signature' => $imageName, 'signature_time' => $time ]);
            return Reply::success('Sign Created');

    }


}
