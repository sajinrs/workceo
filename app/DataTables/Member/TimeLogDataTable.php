<?php

namespace App\DataTables\Member;

use App\DataTables\BaseDataTable;
use App\Http\Requests\TimeLogs\StoreTimeLog;
use App\LogTimeFor;
use App\Project;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Task;
use App\TaskUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;

class TimeLogDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                if ($this->user->can('delete_timelogs')) {
                    return '<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-time-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
            })
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>" . __('app.active') . "</label>";
                }
            })
            ->editColumn('project_name', function ($row) {
                $this->logTimeFor = LogTimeFor::first();
                $logTimeFor = $this->logTimeFor;
                if ($row != null && $logTimeFor->log_time_for == 'task') {
                    return '<a href="javascript:;">' . ucfirst($row->project_name) . '</a>';
                } else {
                    return '<a href="' . route('member.projects.show', $row->id) . '">' . ucfirst($row->project_name) . '</a>';
                }
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                }

                return $timeLog;
            })
            ->rawColumns(['end_time', 'action', 'project_name'])
            ->removeColumn('project_id')
            ->removeColumn('task_id')
            ->removeColumn('total_minutes')
            ->addIndexColumn();

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProjectTimeLog $timeLogs)
    {
        $request = $this->request();
        $projectId = $request->projectID;
        $employee = $request->employeeId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;      
        
        $this->logTimeFor = LogTimeFor::first();
        $logTimeFor = $this->logTimeFor;
        
        $projectName = 'projects.project_name';

        $timeLogs = $timeLogs->join('users', 'users.id', '=', 'project_time_logs.user_id');

        if ($logTimeFor != null && $logTimeFor->log_time_for == 'task') {
            $timeLogs = $timeLogs->join('tasks', 'tasks.id', '=', 'project_time_logs.task_id');
            $projectName = 'tasks.heading as project_name';
        } else {
            $timeLogs = $timeLogs->join('projects', 'projects.id', '=', 'project_time_logs.project_id');
        }
        if (!$this->user->can('view_timelogs')) {
            $timeLogs->where('project_time_logs.user_id', $this->user->id);
        }

        $timeLogs->select('project_time_logs.id', $projectName, 'project_time_logs.start_time', 'project_time_logs.end_time', 'project_time_logs.total_hours', 'project_time_logs.total_minutes', 'project_time_logs.memo', 'project_time_logs.project_id', 'project_time_logs.task_id', 'users.name');

        
        if (!is_null($employee) && $employee !== 'all') {
            $timeLogs->where('project_time_logs.user_id', $employee);
        }

        if (!is_null($startDate)) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->format('Y-m-d');
            $timeLogs->where(DB::raw('DATE(project_time_logs.`start_time`)'), '>=', $startDate);
        }

        if (!is_null($endDate)) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->format('Y-m-d');
            $timeLogs->where(DB::raw('DATE(project_time_logs.`end_time`)'), '<=', $endDate);
        }


        if ($projectId != 0) {
            if ($logTimeFor != null && $logTimeFor->log_time_for == 'task') {
                $timeLogs->where('project_time_logs.task_id', '=', $projectId);
            } else {
                $timeLogs->where('project_time_logs.project_id', '=', $projectId);
            }
        }

        $timeLogs->get();
        
        return $timeLogs;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('timelog-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-10'Bl><'col-md-2'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv', 'pdf']]))
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["timelog-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'select\').select2({minimumResultsForSearch: -1});
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $this->logTimeFor = LogTimeFor::first();
        $logTimeFor = $this->logTimeFor;
        if($logTimeFor->log_time_for == 'task'){
            $projHead = 'app.task';
        } else {
            $projHead = 'app.project';
        }

        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'exportable' => false, 'printable' => false, 'visible' => false],
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __($projHead)  => ['data' => 'project_name', 'name' => 'project_name', 'searchable' => false],
            __('modules.timeLogs.startTime') => ['data' => 'start_time', 'name' => 'start_time'],
            __('modules.timeLogs.endTime') => ['data' => 'end_time', 'name' => 'end_time'],
            __('modules.timeLogs.totalHours') => ['data' => 'total_hours', 'name' => 'total_hours'],
            __('modules.timeLogs.memo') => ['data' => 'memo', 'name' => 'memo'],
            __('modules.timeLogs.whoLogged') => ['data' => 'name', 'name' => 'name', 'searchable' => false],
            __('app.action') => ['data' => 'action', 'name' => 'action', 'exportable' => false, 'printable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Time Log report_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
