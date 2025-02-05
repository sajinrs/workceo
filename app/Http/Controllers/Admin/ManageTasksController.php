<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Notifications\NewClientTask;
use App\Notifications\NewTask;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskUpdated;
use App\Notifications\TaskUpdatedClient;
use App\Project;
use App\SubTask;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\TaskUser;
use App\TaskFile;
use App\Traits\ProjectProgress;
use App\GroupTaskCategory;
use App\GroupTask;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageTasksController extends AdminBaseController
{

    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'fas fa-list-alt';
        $this->pageTitle = 'app.menu.jobs';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTask $request)
    {
        $task = new Task();
        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->project_id = $request->project_id;
        $task->priority = $request->priority;
        $task->board_column_id = $taskBoardColumn->id;
        $task->task_category_id = $request->category_id;
        $task->created_by = $this->user->id;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;

        if ($request->milestone_id != '') {
            $task->milestone_id = $request->milestone_id;
        }

        $task->save();

        $task->users()->attach($request->user_id);

        $this->project = Project::findOrFail($task->project_id);
        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.taskCreatedSuccessfully'), ['html' => $view]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = Project::findOrFail($id);
        $this->categories = TaskCategory::all();
        $this->taskCategory = GroupTaskCategory::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $id)
                ->get();
        } else {
            $this->allTasks = [];
        }
        return view('admin.projects.tasks.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->task = Task::findOrFail($id);
        $this->taskBoardColumns = TaskboardColumn::all();
        $this->categories = TaskCategory::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)
                ->where('id', '!=', $id);

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }
        $view = view('admin.projects.tasks.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTask $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->priority = $request->priority;
        $task->task_category_id = $request->category_id;
        $task->board_column_id = $request->status;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);
        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $task->completed_on = null;
        }

        if ($request->milestone_id != '') {
            $task->milestone_id = $request->milestone_id;
        }

        $task->save();

        // Sync task users
        $task->users()->sync($request->user_id);


        $this->project = Project::findOrFail($task->project_id);

        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.taskUpdatedSuccessfully'), ['html' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $taskId = $request->taskId;
        $status = $request->status;
        $taskBoardColumn = TaskboardColumn::where('slug', $status)->first();
        $task = Task::with('project')->findOrFail($taskId);
        $task->board_column_id = $taskBoardColumn->id;
        //        $task->status = $status;

        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $task->completed_on = null;
        }

        $task->save();

        if ($task->project != null) {
            if ($task->project->calculate_task_progress == "true") {
                //calculate project progress if enabled
                $this->calculateProjectProgress($task->project_id);
            }

            $this->project = Project::findOrFail($task->project_id);
            $this->project->tasks = Task::whereProjectId($this->project->id)->orderBy($request->sortBy, 'desc')->get();
        }
        $this->task = $task;

        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.taskUpdatedSuccessfully'), ['html' => $view, 'textColor' => $task->board_column->label_color, 'column' => $task->board_column->column_name]);
    }

    public function sort(Request $request)
    {
        $projectId = $request->projectId;
        $this->sortBy = $request->sortBy;
        $taskBoardColumn = TaskboardColumn::where('slug', 'completed')->first();
        $this->project = Project::findOrFail($projectId);
        if ($request->sortBy == 'due_date') {
            $order = "asc";
        } else {
            $order = "desc";
        }

        $tasks = Task::whereProjectId($projectId)->orderBy($request->sortBy, $order);

        if ($request->hideCompleted == '1') {
            $tasks->where('board_column_id', '!=', $taskBoardColumn->id);
        }

        $this->project->tasks = $tasks->get();

        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        return Reply::dataOnly(['html' => $view]);
    }

    public function checkTask($taskID)
    {
        $task = Task::findOrFail($taskID);
        $subTask = SubTask::where(['task_id' => $taskID, 'status' => 'incomplete'])->count();

        return Reply::dataOnly(['taskCount' => $subTask, 'lastStatus' => $task->board_column->slug]);
    }

    public function data(Request $request, $projectId = null)
    {

        $tasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'client.name as clientName', 'creator_user.name as created_by', 'creator_user.image as created_image', 'tasks.due_date', 'taskboard_columns.column_name', 'taskboard_columns.label_color', 'tasks.project_id')
            ->where('projects.id', $projectId)
            ->with('users')
            ->groupBy('tasks.id');

        $tasks->get();

        return DataTables::of($tasks)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:;" class="btn btn-outline-info edit-task btn-small"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Edit"><i class="fa fa-pen" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a href="javascript:;" class="btn btn-outline-danger sa-params btn-small"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('users', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members .= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members .= '</a>';
                }

                return $members;
            })
            ->editColumn('clientName', function ($row) {
                return ($row->clientName) ? ucwords($row->clientName) : '-';
            })
            ->editColumn('created_by', function ($row) {
                if (!is_null($row->created_by)) {
                    return ($row->created_image) ? '<img src="' . asset_url('avatar/' . $row->created_image) . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by) : '<img src="' . asset('img/default-profile-2.png') . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by);
                }
                return '-';
            })
            ->editColumn('heading', function ($row) {
                return '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';
            })
            ->editColumn('column_name', function ($row) {
                return '<label class="badge" style="color:#fff; background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })
            ->rawColumns(['column_name', 'action', 'clientName', 'due_date', 'created_by', 'heading', 'users'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->removeColumn('created_image')
            ->removeColumn('label_color')
            ->make(true);
    }


    public function bulkAction(Request $request)
    {
        if(!empty($request->action && $request->id))
        {
            $taskStatus = TaskboardColumn::pluck('id', 'slug')->toArray();

            if($request->action == 'incomplete')
            {
                foreach($request->id as $id)
                {
                    $task = Task::findOrFail($id);
                    $task->board_column_id = $taskStatus['incomplete'];
                    $task->save();
                }
            } elseif($request->action == 'complete') {

                foreach($request->id as $id)
                {
                    $task = Task::findOrFail($id);
                    $task->board_column_id = $taskStatus['completed'];
                    $task->save();
                }
            } elseif($request->action == 'archive') {

                foreach($request->id as $id)
                {
                    $task = Task::findOrFail($id);
                    $task->board_column_id = $taskStatus['completed'];
                    $task->save();
                    
                    Task::destroy($id);
                }
            } elseif($request->action == 'delete') {

                foreach($request->id as $id)
                {
                    $this->destoryMultipleTask($id);
                }
            }
            
        }        
        return Reply::success('Updated!');       
    }


    public function destoryMultipleTask($id)
    {
        $task = Task::withTrashed()->findOrFail($id);

        $taskFiles = TaskFile::where('task_id', $id)->get();

        foreach ($taskFiles as $file) {
            Files::deleteFile($file->hashname, 'task-files/' . $file->task_id);
            $file->delete();
        }

        $task->forceDelete();
    }

    
    //Group Task
    public function showCategoryTask($id, Request $request)
    {
        $this->project = Project::findOrFail($request->project_id);
        $this->tasks = GroupTask::where('task_category_id', $id)->get();
        $view = view('admin.projects.tasks.group-task-list-ajax', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function storeGroupTask(Request $request)
    {
        $titles     = $request->title;
        $users      = $request->user_id;
        $startDate  = $request->start_date;
        $dueDate    = $request->due_date;

        

        if($titles)
        {
            foreach ($titles as $key => $title) {
                if (is_null($title)) {
                    return Reply::error("Title can't be blank");
                }

                if(!isset($users[$key]))
                {
                    return Reply::error("Assiged to can't be blank");
                }
            }            
        } 

        if($startDate)
        {
            foreach ($startDate as $start) {
                if (is_null($start)) {
                    return Reply::error("Start date can't be blank");
                }
            }            
        } 

        if($dueDate)
        {
            foreach ($dueDate as $due) {
                if (is_null($due)) {
                    return Reply::error("Due date can't be blank");
                }
            }            
        }
        
        if($titles)
        {            
            foreach ($titles as $key => $title) {
                if (!is_null($title)) {
    
                    $task = new Task();
                    $task->heading = $title;
                    $task->description = $request->description[$key];
                    $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

                    $task->start_date = Carbon::createFromFormat($this->global->date_format, $startDate[$key])->format('Y-m-d');
                    $task->due_date = Carbon::createFromFormat($this->global->date_format, $dueDate[$key])->format('Y-m-d');
                    $task->project_id = $request->project_id;
                    $task->priority = $request->priority[$key];
                    $task->board_column_id = $taskBoardColumn->id;
                    //$task->task_category_id = $request->category_id;
                    $task->created_by = $this->user->id;
                    //$task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;

                    /* if ($request->milestone_id != '') {
                        $task->milestone_id = $request->milestone_id;
                    } */

                    $task->save();

                    $task->users()->attach($users[$key]);                 
                }
            }
        } 

        $this->project = Project::findOrFail($request->project_id);
        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.taskCreatedSuccessfully'), ['html' => $view]);
    }
    
}
