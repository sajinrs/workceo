<?php

namespace App\Http\Controllers\Api\Admin;

use App\DataTables\Admin\EmployeesDataTable;
use App\Designation;
use App\EmployeeDetails;
use App\EmployeeDocs;
use App\EmployeeSkill;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Admin\Employee\StoreRequest;
use App\Http\Requests\Admin\Employee\UpdateRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\UserResource;
use App\Leave;
use App\LeaveType;
use App\Project;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Role;
use App\RoleUser;
use App\Skill;
use App\Task;
use App\TaskboardColumn;
use App\Team;
use App\UniversalSearch;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiAdminManageEmployeesController extends ApiAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.employees';
        $this->pageIcon = 'fas fa-address-card';
        $this->middleware(function ($request, $next) {
            if (!in_array('employees', $this->user->modules)) {
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
        //return new EmployeeCollection(User::allEmployeesByPage(10));
        return ApiResource::collection(User::allEmployeesByPage(10));
    }

    public function index2(EmployeesDataTable $dataTable)
    {
        $this->employees = User::allEmployees();
        $this->skills = Skill::all();
        $this->departments = Team::all();
        $this->designations = Designation::all();
        $this->totalEmployees = count($this->employees);
        $this->roles = Role::where('roles.name', '<>', 'client')->get();
        $whoseProjectCompleted = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
            ->join('users', 'users.id', '=', 'project_members.user_id')
            ->select('users.*')
            ->groupBy('project_members.user_id')
            ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100")
            ->orderBy('users.id')
            ->get();

        $notAssignedProject = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name')->whereNotIn('users.id', function ($query) {

                $query->select('user_id as id')->from('project_members');
            })
            ->where('roles.name', '<>', 'client')
            ->get();

        $this->freeEmployees = $whoseProjectCompleted->merge($notAssignedProject)->count();

        // return view('admin.employees.index', $this->data);
        return $dataTable->render('admin.employees.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = new EmployeeDetails();
        $this->fields = $employee->getCustomFieldGroupsWithFields()->fields;
        //$this->skills = Skill::all()->pluck('name')->toArray();
        $this->skills = Skill::all();
        $this->teams = Team::all();
        $this->designations = Designation::all();
        return view('admin.employees.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            /* 'employee_id' => [
                'required',
                Rule::unique('employee_details')->where(function($query) {
                    $query->where(['employee_id' => $request->employee_id, 'company_id' => company()->id]);
                })
            ], */
            //"name" => "required",
            "employee_id" => "required",
            'first_name'  => 'required',
            "last_name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            'slack_username' => 'nullable|unique:employee_details,slack_username',
            'hourly_rate' => 'nullable|numeric',
            'joining_date' => 'required',
            'department' => 'required',
            'designation' => 'required',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $company = company();

        if (!is_null($company->employees) && $company->employees->count() >= $company->package->max_employees) {
            //return Reply::error(__('messages.upgradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees]));
            return response()->json([ 'error' => 1, "message" => __('messages.upgradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees])]);
        }

        if (!is_null($company->employees) && $company->package->max_employees < $company->employees->count()) {
            return response()->json([ 'error' => 1, "message" => __('messages.downGradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees])]);
            //return Reply::error(__('messages.downGradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees]));
        }
        DB::beginTransaction();
        try {
            $user = new User();
            $fullName = $request->input('first_name').' '.$request->input('last_name');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->name = $fullName;
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->mobile = $request->input('mobile');
            $user->gender = $request->input('gender');
            $user->login = $request->login;

            if ($request->hasFile('image')) {
                /* $crop = [
                    'width' => $request->input('width'),
                    'height'=> $request->input('height'),
                    'x'     => $request->input('x'),
                    'y'     => $request->input('y')
                ];
                $user->image = Files::upload($request->image, 'avatar',300, false, $crop); */
                $user->image = Files::upload($request->image, 'avatar', 300);
            }

            $user->save();

            $tags = $request->input('tags');
            $tags = json_decode($tags);
            //print_r($tags);
            if (!empty($tags)) {
                EmployeeSkill::where('user_id', $user->id)->delete();
                foreach ($tags as $tag) {
                    
                    // Store user skills
                    $skill = new EmployeeSkill();
                    $skill->user_id = $user->id;
                    $skill->skill_id = $tag;
                    $skill->save();
                }
            }            

            if ($user->id) {
                $employee = new EmployeeDetails();
                $employee->user_id = $user->id;
                $employee->employee_id = $request->employee_id;
                $employee->address = $request->address;
                $employee->hourly_rate = $request->hourly_rate;
                $employee->slack_username = $request->slack_username;
                $employee->joining_date = Carbon::createFromFormat($this->global->date_format, $request->joining_date)->format('Y-m-d');
                if ($request->last_date != '') {
                    $employee->last_date = Carbon::createFromFormat($this->global->date_format, $request->last_date)->format('Y-m-d');
                }
                $employee->department_id = $request->department;
                $employee->designation_id = $request->designation;
                $employee->save();
            }

            // To add custom fields data
            if ($request->get('custom_fields_data')) {
                $employee->updateCustomFieldData($request->get('custom_fields_data'));
            }


            $role = Role::where('name', 'employee')->first();
            $user->attachRole($role->id);
            DB::commit();

        } catch (\Swift_TransportException $e) {
            // Rollback Transaction
            DB::rollback();
            return response()->json([ 'error' => 1, "message" => 'Please configure SMTP details to add employee. Visit Settings -> Email setting to set SMTP','smtp_error']);
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollback();
            return response()->json([ 'error' => 1, "message" => 'Some error occured when inserting the data. Please try again or contact support']);
        }
        $this->logSearchEntry($user->id, $user->name, 'admin.employees.show', 'employee');

        //Retrieve Data
        $data['userDetail'] = User::withoutGlobalScope('active')->findOrFail($user->id);
        $data['employeeDetail'] = EmployeeDetails::where('user_id', '=', $data['userDetail']->id)->first();       
        if (!is_null($data['employeeDetail'])) {
            $data['employeeDetail'] = $data['employeeDetail']->withCustomFields();
            
        }

        $response = [
            'success' => 1,
            "message" => __('messages.employeeAdded'),
            'data'    => $data['employeeDetail']
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.employeeAdded')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        $data['employee'] = User::with(['employeeDetail', 'employeeDetail.designation', 'employeeDetail.department', 'company'])->withoutGlobalScope('active')->findOrFail($id);
        $data['employeeDetail'] = EmployeeDetails::where('user_id', '=', $data['employee']->id)->first();
        //$this->employeeDocs = EmployeeDocs::where('user_id', '=', $this->employee->id)->get();

        $data['employeeDetail']['designation'] = $data['employeeDetail']->designation->name;
        $data['employeeDetail']['skills']      = implode(', ',$data['employee']->skills()); 

        
        if (!is_null($data['employeeDetail'])) {
            $data['employeeDetail'] = $data['employeeDetail']->withCustomFields();
            $data['fields'] = $data['employeeDetail']->getCustomFieldGroupsWithFields()->fields;
        }

        $completedTaskColumn = TaskboardColumn::where('slug', 'completed')->first();

        $data['employeeDetail']['taskCompleted'] = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $id)
            ->where('tasks.board_column_id', $completedTaskColumn->id)
            ->count();

        $data['projects'] = Project::select('projects.id', 'projects.project_name', 'projects.deadline', 'projects.completion_percent')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', $id)
            ->get();
            //->paginate(6);

        //$data['projects']['completetion'] =  $data['projects']->completion_percent;

        $data['tasks'] = $this->tasks($id);
        

        

        $hoursLogged = ProjectTimeLog::where('user_id', $id)->sum('total_minutes');

        $timeLog = intdiv($hoursLogged, 60) . ' hrs ';

        if (($hoursLogged % 60) > 0) {
            $timeLog .= ($hoursLogged % 60) . ' mins';
        }

        $this->hoursLogged = $timeLog;

        $this->activities = UserActivity::where('user_id', $id)->orderBy('id', 'desc')->get();
       
        $this->leaves = Leave::byUser($id);
        $this->leavesCount = Leave::byUserCount($id);

        $this->leaveTypes = LeaveType::byUser($id);
        $this->allowedLeaves = LeaveType::sum('no_of_leaves');

        return new UserResource($data);
    }*/

    public function tasks($userId)
    {
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.due_date', 'tasks.status', 'tasks.project_id', 'taskboard_columns.column_name', 'taskboard_columns.label_color')
            ->where('task_users.user_id', $userId);      

        return $tasks->paginate(6);

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['userDetail'] = User::findOrFail($id);
        $data['employeeDetail'] = EmployeeDetails::with('user')->where('user_id', '=', $data['userDetail']->id)->first();
        $data['employeeDetail']->user->makeHidden(['unreadNotifications','modules','role','user_other_role']);
        //$this->skills = Skill::all()->pluck('name')->toArray();
//        $data['skills'] = Skill::all();
//        $data['teams'] = Team::all();
//        $data['designations'] = Designation::all();
        if (!is_null($data['employeeDetail'])) {
            $data['employeeDetail'] = $data['employeeDetail']->withCustomFields();
            //$data['fields'] = $data['employeeDetail']->getCustomFieldGroupsWithFields()->fields;
        }

        return new ApiResource($data['employeeDetail']);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            /* 'employee_id' => [
                'required',
                Rule::unique('employee_details')->where(function($query) {
                    $query->where(['employee_id' => $request->employee_id, 'company_id' => company()->id]);
                })
            ], */
            //"name" => "required",            

            'employee_id' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'slack_username' => 'nullable|unique:employee_details',
            'first_name'  => 'required',
            "last_name" => "required",
            'hourly_rate' => 'nullable|numeric',
            'department' => 'required',
            'designation' => 'required',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $fullName = $request->input('first_name').' '.$request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->name = $fullName;
        $user->email = $request->input('email');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->mobile = $request->input('mobile');
        $user->gender = $request->input('gender');
        $user->status = $request->input('status');
        $user->login = $request->login;        

        if ($request->hasFile('image')) {
            $crop = [
                'width' => $request->input('width'),
                'height'=> $request->input('height'),
                'x'     => $request->input('x'),
                'y'     => $request->input('y')
            ];
            $user->image = Files::upload($request->image, 'avatar',300, false, $crop);
        }

        $user->save();

        $tags = $request->input('tags');        
        $tags = json_decode($tags);
        /* print_r($tags);
        die('d'); */
        EmployeeSkill::where('user_id', $user->id)->delete();
        if (!empty($tags)) {
            
            foreach ($tags as $tag) {
                // check or store skills
                //$skillData = Skill::firstOrCreate(['name' => strtolower($tag->value)]);

                // Store user skills
                $skill = new EmployeeSkill();
                $skill->user_id = $user->id;
                $skill->skill_id = $tag;
                $skill->save();
            }
        }


        $employee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        if (empty($employee)) {
            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
        }
        $employee->employee_id = $request->employee_id;
        $employee->address = $request->address;
        $employee->hourly_rate = $request->hourly_rate;
        $employee->slack_username = $request->slack_username;
        $employee->joining_date = Carbon::createFromFormat($this->global->date_format, $request->joining_date)->format('Y-m-d');

        $employee->last_date = null;

        if ($request->last_date != '') {
            $employee->last_date = Carbon::createFromFormat($this->global->date_format, $request->last_date)->format('Y-m-d');
        }

        $employee->department_id = $request->department;
        $employee->designation_id = $request->designation;
        $employee->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $employee->updateCustomFieldData($request->get('custom_fields_data'));
        }

        //Retrieve Data
        $data['userDetail'] = User::withoutGlobalScope('active')->findOrFail($user->id);
        $data['employeeDetail'] = EmployeeDetails::where('user_id', '=', $data['userDetail']->id)->first();       
        if (!is_null($data['employeeDetail'])) {
            $data['employeeDetail'] = $data['employeeDetail']->withCustomFields();
            
        }

        $response = [
            'success' => 1,
            "message" => __('messages.employeeUpdated'),
            'data'    => $data['employeeDetail']
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.employeeUpdated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);

        if ($user->id == 1) {
            return Reply::error(__('messages.adminCannotDelete'));
        }

        $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'employee')->get();
        if ($universalSearches){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        User::destroy($id);
        return response()->json([ 'success' => 1, "message" => __('messages.employeeDeleted')]);
    }

    

    public function timeLogs($userId)
    {
        $timeLogs = ProjectTimeLog::join('projects', 'projects.id', '=', 'project_time_logs.project_id')
            ->select('project_time_logs.id', 'projects.project_name', 'project_time_logs.start_time', 'project_time_logs.end_time', 'project_time_logs.total_hours', 'project_time_logs.memo', 'project_time_logs.project_id', 'project_time_logs.total_minutes')
            ->where('project_time_logs.user_id', $userId);
        $timeLogs->get();

        return DataTables::of($timeLogs)
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>Active</label>";
                }
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                }

                return $timeLog;
            })
            ->rawColumns(['end_time', 'project_name'])
            ->removeColumn('project_id')
            ->make(true);
    }

    public function export($status, $employee, $role)
    {
        if ($role != 'all' && $role != '') {
            $userRoles = Role::findOrFail($role);
        }
        $rows = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->withoutGlobalScope('active')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '<>', 'client')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')

            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'designations.name as designation_name',
                'employee_details.address',
                'employee_details.hourly_rate',
                'users.created_at',
                'roles.name as roleName'
            );
        if ($status != 'all' && $status != '') {
            $rows = $rows->where('users.status', $status);
        }

        if ($employee != 'all' && $employee != '') {
            $rows = $rows->where('users.id', $employee);
        }

        if ($role != 'all' && $role != '' && $userRoles) {
            if ($userRoles->name == 'admin') {
                $rows = $rows->where('roles.id', $role);
            } elseif ($userRoles->name == 'employee') {
                $rows =  $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role)
                    ->having('roleName', '<>', 'admin');
            } else {
                $rows = $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role);
            }
        }
        $attributes =  ['roleName'];
        $rows = $rows->groupBy('users.id')->get()->makeHidden($attributes);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Email', 'Mobile', 'Designation', 'Address', 'Hourly Rate', 'Created at', 'Role'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = [
                "id" => $row->id,
                "name" => $row->name,
                "email" => $row->email,
                "mobile" => $row->mobile,
                "Designation" => $row->designation_name,
                "address" => $row->address,
                "hourly_rate" => $row->hourly_rate,
                "created_at" => $row->created_at->format('Y-m-d h:i:s a'),
                "roleName" => $row->roleName
            ];
        }

        // Generate and return the spreadsheet
        Excel::create('Employees', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Employees');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Employees file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });
            });
        })->download('xlsx');
    }

    public function assignRole(Request $request)
    {
        $userId = $request->userId;
        $roleId = $request->role;
        $employeeRole = Role::where('name', 'employee')->first();
        $user = User::findOrFail($userId);

        RoleUser::where('user_id', $user->id)->delete();
        $user->roles()->attach($employeeRole->id);
        if ($employeeRole->id != $roleId) {
            $user->roles()->attach($roleId);
        }

        return Reply::success(__('messages.roleAssigned'));
    }

    public function assignProjectAdmin(Request $request)
    {
        $userId = $request->userId;
        $projectId = $request->projectId;
        $project = Project::findOrFail($projectId);
        $project->project_admin = $userId;
        $project->save();

        return Reply::success(__('messages.roleAssigned'));
    }

    public function docsCreate(Request $request, $id)
    {
        $this->employeeID = $id;
        return view('admin.employees.docs-create', $this->data);
    }

    public function freeEmployees()
    {
        if (\request()->ajax()) {

            $whoseProjectCompleted = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->select('users.*')
                ->groupBy('project_members.user_id')
                ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100")
                ->orderBy('users.id')
                ->get();

            $notAssignedProject = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*')
                ->whereNotIn('users.id', function ($query) {

                    $query->select('user_id as id')->from('project_members');
                })
                ->where('roles.name', '<>', 'client')
                ->get();

            $freeEmployees = $whoseProjectCompleted->merge($notAssignedProject);

            return DataTables::of($freeEmployees)
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.employees.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="' . route('admin.employees.show', [$row->id]) . '" class="btn btn-success btn-circle"
                      data-toggle="tooltip" data-original-title="View Employee Details"><i class="fa fa-search" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                })
                ->editColumn(
                    'created_at',
                    function ($row) {
                        return Carbon::parse($row->created_at)->format($this->global->date_format);
                    }
                )
                ->editColumn(
                    'status',
                    function ($row) {
                        if ($row->status == 'active') {
                            return '<label class="label label-success">' . __('app.active') . '</label>';
                        } else {
                            return '<label class="label label-danger">' . __('app.inactive') . '</label>';
                        }
                    }
                )
                ->editColumn('name', function ($row) {
                    $image = '<img src="' . $row->image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
                    return '<a href="' . route('admin.employees.show', $row->id) . '">' . $image . ' ' . ucwords($row->name) . '</a>';
                })
                ->rawColumns(['name', 'action', 'role', 'status'])
                ->removeColumn('roleId')
                ->removeColumn('roleName')
                ->removeColumn('current_role')
                ->make(true);
        }

        return view('admin.employees.free_employees', $this->data);
    }


    public function createSkill()
    {
        $this->skills = Skill::all();
        return view('admin.employees.create-skill', $this->data);
    }

    public function storeSkill(Request $request)
    {
        $skill = new Skill();
        $this->validate($request, [
            'skill_name' => 'required'
        ]);
        $skill->name = $request->skill_name;
        $skill->save();
        $skillData = Skill::all();
        return Reply::successWithData(__('messages.skillAdded'),['data' => $skillData]);
    }

    public function destroySkill(Request $request)
    {
        Skill::destroy($request->id);
        $skillData = Skill::all();
        return Reply::successWithData(__('messages.skillDeleted'),['data' => $skillData]);
    }

}
