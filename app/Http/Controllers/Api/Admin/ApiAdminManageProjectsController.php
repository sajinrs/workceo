<?php

namespace App\Http\Controllers\Api\Admin;

use App\Currency;
use App\DataTables\Admin\ProjectsDataTable;
use App\Expense;
use App\Helper\Reply;
use App\Helper\Files;
use App\Http\Controllers\Api\Admin\ApiAdminBaseController;
use App\Http\Requests\Project\StoreProject;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Payment;
use App\ProjectActivity;
use App\ProjectCategory;
use App\ProjectFile;
use App\ProjectMember;
use App\ProjectTemplate;
use App\ProjectTimeLog;
use App\Task;
use App\Tax;
use App\TaskboardColumn;
use App\User;
use App\ClientDetails;
use App\Invoice;
use App\InvoiceItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\InvoiceSetting;
use App\Project;
use App\ProjectMilestone;
use App\ProjectVehicle;
use App\Vehicle;
use App\ProductCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ProjectProgress;
use App\Http\Resources\ApiResource;

class ApiAdminManageProjectsController extends ApiAdminBaseController
{

    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
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
    public function index()
    {
        return ProjectResource::collection(Project::with('files','members')->paginate(10));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'start_date' => 'required',
            'deadline' => 'required',
            /* 'client_id' => [
                'required',
                Rule::exists('users_id', 'id')
                    ->where('company_id', company()->id),
            ], */
            'client_id' => 'required',
            'category_id' => [
                'required',
                Rule::exists('project_category', 'id')
                    ->where('company_id', company()->id),
            ],
            'start_time' => 'required',
            'end_time' => 'required',
            'project_budget' => 'required',
            'currency_id' => 'required',
            'status'    => 'required',
            'job_status' => 'required',
            "completion_percent" => 'required',
        ]);

        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $memberExistsInTemplate = false;
        if ($request->total == 0 || $request->total == 0.00)
        { 
            $error = ['invoice' => ['Invoice Missing']];
            return response()->json($error);
        }

        if (empty($request->item_name[0])) {            
            $error = ['invoice' => ['Item name cannot be blank.']];
            return response()->json($error);
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

        $users = $request->user_id;
        if(!empty($users))
        {
            $users = json_decode($users);
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
            $vehicles = json_decode($vehicles);
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
                $quantity = json_decode($quantity);
                foreach ($quantity as $qty) {
                    if (!is_numeric($qty) && (intval($qty) < 1)) {
                        $error = ['invoice' => [__('messages.quantityNumber')]];
                        return response()->json($error);
                    }
                }
            }
            
            if($cost_per_item)
            {
                $cost_per_item = json_decode($cost_per_item);
                foreach ($cost_per_item as $rate) {
                    if (!is_numeric($rate)) {
                        $error = ['invoice' => [__('messages.unitPriceNumber')]];
                        return response()->json($error);
                    }
                }
            }
            
            if($amount)
            {
                $amount = json_decode($amount);
                foreach ($amount as $amt) {
                    if (!is_numeric($amt)) {
                        $error = ['invoice' => [__('messages.amountNumber')]];
                        return response()->json($error);
                    }
                }
            }
            
            if($items)
            {
                $items = json_decode($items);
                $itemsSummary = json_decode($itemsSummary);
                foreach ($items as $itm) {
                    if (is_null($itm)) {
                        $error = ['invoice' => [__('messages.itemBlank')]];
                        return response()->json($error);
                    }
                }
            }     
            
            if($tax){
                $tax = json_decode($tax);       
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
                        $invoiceItems->taxes        = $tax[$key] ? json_encode(array($tax[0])) : '';
                        //$invoiceItems->taxes        = $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null;                        
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
        /* if(isset($request->created_from)){
            return response()->json([ 'success' => 1, "message" => "Job Created"]);
            //return Reply::success(__('modules.projects.projectUpdated'));
        } */

        $result   = Project::with('files','members','tasks')->findOrFail($project->id)->withCustomFields();
        $response = [
            'success' => 1,
            "message" => 'Job Created',
            'data'    => $result
        ];

        return new ProjectResource($response);
        
        //return response()->json([ 'success' => 1, "message" => "Job Created"]);
        //return Reply::redirect(route('admin.projects.index'), __('modules.projects.projectUpdated'));
    }

    /*public function show($id)
    {
        $data['project']      = Project::with('invoices','currency', 'client')->findOrFail($id);
        $employees = $data['project']->members;
        foreach($employees as $emp)
        {
            $data['project']['members'] = [
                'name' => $emp->user->name
            ];
        }
       

        $data['fields']       = $data['project']->getCustomFieldGroupsWithFields()->fields;

        $data['taxes']        = Tax::all();

        $data['activeTimers'] = ProjectTimeLog::projectActiveTimers($data['project']->id);
        $data['openTasks'] = Task::projectOpenTasks($data['project']->id);
        $data['openTasksPercent'] = (count($data['openTasks']) == 0 ? "0" : (count($data['openTasks']) / count($data['project']->tasks)) * 100);
        $data['daysLeft'] = 0;
        $data['daysLeftFromStartDate'] = 0;
        $data['daysLeftPercent'] = 0;

        if (is_null($data['project']->deadline)) {
            $data['daysLeft'] = 0;
        } else {
            if ($data['project']->deadline->isPast()) {
                $data['daysLeft'] = 0;
            } else {
                $data['daysLeft'] = $data['project']->deadline->diff(Carbon::now())->format('%d') + ($data['project']->deadline->diff(Carbon::now())->format('%m') * 30) + ($data['project']->deadline->diff(Carbon::now())->format('%y') * 12);
            }

            $data['daysLeftFromStartDate'] = $data['project']->deadline->diff($data['project']->start_date)->format('%d') + ($data['project']->deadline->diff($data['project']->start_date)->format('%m') * 30) + ($data['project']->deadline->diff($data['project']->start_date)->format('%y') * 12);

            $data['daysLeftPercent'] = ($data['daysLeftFromStartDate'] == 0 ? "0" : (($data['daysLeft'] / $data['daysLeftFromStartDate']) * 100));
        }


        $data['hoursLogged'] = ProjectTimeLog::projectTotalMinuts($data['project']->id);

        $hour = intdiv($data['hoursLogged'], 60);
        $minute = 0;
        if (($data['hoursLogged'] % 60) > 0) {
            $minute = ($data['hoursLogged'] % 60);
            $data['hoursLogged'] = $hour . 'hrs ' . $minute . ' mins';
        } else {
            $data['hoursLogged'] = $hour;
        }

        $data['recentFiles'] = ProjectFile::where('project_id', $data['project']->id)->orderBy('id', 'desc')->limit(10)->get();
        $data['activities'] = ProjectActivity::getProjectActivities($id, 10);
        // $this->completedTasks = Task::projectCompletedTasks($this->project->id);
        $data['earnings'] = Payment::where('status', 'complete')
            ->where('project_id', $id)
            ->sum('amount');
        $data['expenses'] = Expense::where(['project_id' => $id, 'status' => 'approved'])->sum('price');
        $data['milestones'] = ProjectMilestone::with('currency')->where('project_id', $id)->get();

        if ($data['project']->status == 'in progress') {
            $data['statusText'] = __('app.inProgress');
            $data['statusTextColor'] = 'text-info';
            $data['btnTextColor'] = 'btn-info';
        } else if ($data['project']->status == 'on hold') {
            $data['statusText'] = __('app.onHold');
            $data['statusTextColor'] = 'text-warning';
            $data['btnTextColor'] = 'btn-warning';
        } else if ($data['project']->status == 'not started') {
            $data['statusText'] = __('app.notStarted');
            $data['statusTextColor'] = 'text-warning';
            $data['btnTextColor'] = 'btn-warning';
        } else if ($data['project']->status == 'canceled') {
            $data['statusText'] = __('app.canceled');
            $data['statusTextColor'] = 'text-danger';
            $data['btnTextColor'] = 'btn-danger';
        } else if ($data['project']->status == 'finished') {
            $data['statusText'] = __('app.finished');
            $data['statusTextColor'] = 'text-success';
            $data['btnTextColor'] = 'btn-success';
        }


//        $data = [
//            'project' => $this->project
//        ];

        return new ProjectResource($data);

    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data    = Project::with('files','members.userDetails','tasks','vehicles','invoices','client')->findOrFail($id)->withCustomFields();

       // $data['clients']    = User::allClients();
       // $data['categories'] = ProjectCategory::all();

     //   $data['fields']     = $data['project']->getCustomFieldGroupsWithFields()->fields;
     //   $data['currencies'] = Currency::all();
     //   $data['taxes']      = Tax::all();
     //   $data['productCategories'] = ProductCategory::all();
     //   $data['vehicles']   = Vehicle::all();
    //    $ivoiceId         = Invoice::select('id')->where('project_id',$id)->first();
//        if($ivoiceId){
//            $data['invoice']    = Invoice::findOrFail($ivoiceId['id'] );
//        }
        
//        $data['employees']  = User::doesntHave('member', 'and', function($query){
//            $query->where('project_id', "''");
//        })
//            ->join('role_user', 'role_user.user_id', '=', 'users.id')
//            ->join('roles', 'roles.id', '=', 'role_user.role_id')
//            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
//            ->where('roles.name', 'employee')
//            ->get();
        return new ProjectResource($data);
       // return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'start_date' => 'required',
            'deadline' => 'required',
            'client_id' => 'required',
            'category_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'project_budget' => 'required',
            'currency_id' => 'required',
            'status'    => 'required',
            'job_status' => 'required',
            "completion_percent" => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
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
        $project->status = $request->status;
        $project->job_status = $request->job_status;
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
            $quantity = json_decode($quantity); 
            foreach ($quantity as $qty) {
                if (!is_numeric($qty) && $qty < 1) {
                    $error = ['invoice' => [__('messages.quantityNumber')]];
                    return response()->json($error);
                }
            }
        }
        
        if($cost_per_item){
            $cost_per_item = json_decode($cost_per_item); 
            foreach ($cost_per_item as $rate) {
                if (!is_numeric($rate)) {
                    $error = ['invoice' => [__('messages.unitPriceNumber')]];
                    return response()->json($error);
                }
            }
        }
        
        if($amount){
            $amount = json_decode($amount); 
            foreach ($amount as $amt) {
                if (!is_numeric($amt)) {
                    $error = ['invoice' => [__('messages.amountNumber')]];
                    return response()->json($error);
                }
            }
        }
        
        if($items){            
            $items = json_decode($items); 
            $itemsSummary = json_decode($itemsSummary);           
            foreach ($items as $itm) {
                if (is_null($itm)) {
                    $error = ['invoice' => [__('messages.itemBlank')]];
                    return response()->json($error);
                }
            }
        }

        if($tax){
            $tax = json_decode($tax);       
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
                        //$invoiceItems->taxes        = $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null;
                        $invoiceItems->taxes        = $tax[$key] ? json_encode(array($tax[0])) : '';
                        $invoiceItems->save();
                        
                    }
                endforeach;
            }

        } else {

            $invoice = Invoice::findOrFail($ivoiceId);

            
            if ($invoice->status == 'paid') {
                $error = ['invoice' => [__('messages.invalidRequest')]];
                return response()->json($error);
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
                            'taxes' => $tax[$key] ? json_encode(array($tax[$key])) : '',
                            //'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                        ]
                    );
                endforeach;
            }
                   
        }       
         
        $users = $request->user_id;
        if(!empty($users))
        {
            $users = json_decode($users);
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
            $vehicles = json_decode($vehicles);
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

        $result   = Project::with('files','members','tasks')->findOrFail($id)->withCustomFields();
        $response = [
            'success' => 1,
            "message" => __('messages.projectUpdated'),
            'data'    => $result
        ];

        return new ProjectResource($response);
        //return response()->json([ 'success' => 1, "message" => __('messages.projectUpdated')]);
        //return Reply::redirect(route('admin.projects.index'), __('messages.projectUpdated'));
    }

    public function destroy($id)
    {

        $project = Project::withTrashed()->findOrFail($id);
        $project->forceDelete();

        $response = [
            'success' => 1,
            "message" => __('messages.projectDeleted'),
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.projectDeleted')]);
    }

    public function showProjectTask($id)
    {        
        $data = Task::where('project_id', $id)->get();
        return new ApiResource($data);
    }

    public function projectSign(Request $request, $id)
    {
       
        $time = Carbon::now()->toDateTimeString(); 

        if ($request->hasFile('signature')) {
            $imageName = Files::upload($request->signature, 'project-sign');   

            $project = Project::find($id)
                ->update([ 'signature' => $imageName, 'signature_time' => $time ]);

                $response = Project::find($id)->setVisible(['signature', 'signature_time']);

                $response = [
                    'success' => 1,
                    "message" => 'Sign Created',
                    'data'    => $response
                ];
        } else {
            
            $response = [
                "message" => 'Something went wrong',
               
            ];
        }
        return new ApiResource($response);

            //return Reply::success('Sign Created');
    }

    public function createJobCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "category_name"         => "required",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = new ProjectCategory();
        $category->category_name = $request->category_name;
        $category->save();

        $category = ProjectCategory::all()->makeHidden(['company_id']);

        $response = [
            'success' => 1,
            "message" => 'Job Category Created',
            'data'    => $category
        ];

        return new ApiResource($response);
    }

    public function destroyJobCategory($id)
    {
        ProjectCategory::destroy($id);
        $response = [
            'success' => 1,
            "message" => 'Category Deleted',
        ];

        return new ApiResource($response);
    }

    public function updateJobStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "job_status"         => "required",
            "status"             => "required",
            "completion_percent" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        Project::find($id)
                    ->update([
                        'job_status' => $request->status,
                        'status' => $request->projstatus,
                        'completion_percent' => $request->percentage,
                        'calculate_task_progress' => 'false'

                    ]);

            $response = [
                'success' => 1,
                "message" => 'Job Status Updated',
            ];
    
            return new ApiResource($response);

    }

}

    