<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TimeLogs\StoreTimeLog;
use App\Http\Requests\TimeLogs\UpdateTimeLog;
use App\LogTimeFor;
use App\Project;
use App\ProjectTimeLog;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManageTimeLogsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.jobs';
        $this->pageIcon = 'fas fa-list-alt';
        $this->middleware(function ($request, $next) {
            if (!in_array('timelogs', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function show($id)
    {
        $this->project = Project::findOrFail($id);
        $this->tasks   = $this->project->tasks;
        if (!$this->tasks) {
            $this->tasks = [];
        }
        $this->logTimeFor = LogTimeFor::first();
        return view('admin.projects.time-logs.show', $this->data);
    }

    public function store(StoreTimeLog $request)
    {
        
        $this->logTimeFor = LogTimeFor::first();

        $start_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
        $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $start_time, $this->global->timezone)->setTimezone('UTC');
        $end_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');
        $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $end_time, $this->global->timezone)->setTimezone('UTC');

        $activeTimer = ProjectTimeLog::with('user')
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('end_time', [$start_time->format('Y-m-d H:i:s'), $end_time->format('Y-m-d H:i:s')])
                    ->orWhereNull('end_time');
            })
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $this->user->id)->first();

        if (is_null($activeTimer)) {
            $timeLog = new ProjectTimeLog();

            $this->logTimeFor = LogTimeFor::first();

            if ($this->logTimeFor->log_time_for == 'task') {
                $timeLog->task_id = $request->task_id;
                $timeLog->user_id = $this->user->id;
            } else {
                $timeLog->project_id = $request->project_id;
                $timeLog->user_id = $request->user_id;
            }

            $timeLog->start_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
            $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, $this->global->timezone)->setTimezone('UTC');
            $timeLog->end_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');
            $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, $this->global->timezone)->setTimezone('UTC');
            $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
            $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));

            $timeLog->memo = $request->memo;
            $timeLog->edited_by_user = $this->user->id;
            $timeLog->save();

            return Reply::success(__('messages.timeLogAdded'));
        }

        return Reply::error(__('messages.timelogAlreadyExist'));
    }

    public function data($id)
    {

        $this->logTimeFor = LogTimeFor::first();

        if ($this->logTimeFor->log_time_for == 'task') {
            $taskIDs = Task::where('project_id', $id)->pluck('id')->toArray();
            $timeLogs = ProjectTimeLog::with(['user', 'editor'])->whereIn('task_id', $taskIDs)->get();
        } else {
            $timeLogs = ProjectTimeLog::with(['user', 'editor'])->where('project_id', $id)->get();
        }

        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:;" class="btn btn-outline-info edit-time-log"
                      data-toggle="tooltip" data-time-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pen" aria-hidden="true"></i></a>

                    <a href="javascript:;" class="btn btn-outline-danger sa-params"
                      data-toggle="tooltip" data-time-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>" . __('app.active') . "</label>";
                }
            })
            ->editColumn('user_id', function ($row) {
                return ucwords($row->user->name);
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                }

                return $timeLog;
            })
            ->editColumn('edited_by_user', function ($row) {
                if (!is_null($row->edited_by_user)) {
                    return ucwords($row->editor->name);
                }
            })
            ->rawColumns(['end_time', 'action'])
            ->removeColumn('project_id')
            ->make(true);
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        ProjectTimeLog::destroy($id);
        return Reply::success(__('messages.timeLogDeleted'));
    }

    public function edit($id)
    {

        $this->timeLog = ProjectTimeLog::findOrFail($id);
        $this->logTimeFor = LogTimeFor::first();

        if ($this->timeLog->task_id) {
            $this->task = Task::findOrFail($this->timeLog->task_id);
            $this->tasks = Task::where('project_id', $this->task->project_id)->get();
        } else {
            $this->project =  Project::findOrFail($this->timeLog->project_id);
            $this->timeLogProjects = Project::all();;
        }
        return view('admin.projects.time-logs.edit', $this->data);
    }

    public function update(UpdateTimeLog $request, $id)
    {

        $timeLog = ProjectTimeLog::findOrFail($id);

        $start_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
        $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $start_time, $this->global->timezone)->setTimezone('UTC');
        $end_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');
        $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $end_time, $this->global->timezone)->setTimezone('UTC');

        if ($request->has('task_id')) {
            $timeLog->task_id = $request->task_id;
            $userID = $timeLog->user_id;
        } else {
            $timeLog->user_id = $request->user_id;
            $timeLog->project_id = $request->project_id;
            $userID = $request->user_id;
        }
        $activeTimer = ProjectTimeLog::with('user')
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('end_time', [$start_time->format('Y-m-d H:i:s'), $end_time->format('Y-m-d H:i:s')])
                    ->orWhereNull('end_time');
            })
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $userID)
            ->where('project_time_logs.id', '!=', $id)
            ->first();

        if (is_null($activeTimer)) {
            $timeLog->start_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
            $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, $this->global->timezone)->setTimezone('UTC');
            $timeLog->end_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');
            $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, $this->global->timezone)->setTimezone('UTC');
            $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
            $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));

            $timeLog->memo = $request->memo;
            $timeLog->edited_by_user = $this->user->id;
            $timeLog->save();

            return Reply::success(__('messages.timeLogUpdated'));
        }

        return Reply::error(__('messages.timelogAlreadyExist'));
    }

    public function stopTimer(Request $request)
    {
        $timeId = $request->timeId;
        $timeLog = ProjectTimeLog::findOrFail($timeId);
        $timeLog->end_time = Carbon::now();
        $timeLog->edited_by_user = $this->user->id;
        $timeLog->save();

        $timeLog->total_hours = ($timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24) + ($timeLog->end_time->diff($timeLog->start_time)->format('%H'));

        if ($timeLog->total_hours == 0) {
            $timeLog->total_hours = round(($timeLog->end_time->diff($timeLog->start_time)->format('%i') / 60), 2);
        }

        $timeLog->save();

        $this->activeTimers = ProjectTimeLog::projectActiveTimers($timeLog->project_id);
        $view = view('admin.projects.time-logs.active-timers', $this->data)->render();
        return Reply::successWithData(__('messages.timerStoppedSuccessfully'), ['html' => $view]);
    }
}
