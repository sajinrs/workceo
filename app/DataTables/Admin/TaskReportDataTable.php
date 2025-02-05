<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;

class TaskReportDataTable extends BaseDataTable
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
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('users', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members.= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= ($member->image) ? '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . asset_url('avatar/' . $member->image) . '"
                    alt="user" class="img-circle" width="25" height="25"> ' : '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . asset('img/default-profile-2.png') . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members.= '</a>';
                }
                return $members;
            })
            ->editColumn('heading', function ($row) {
                return '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';
            })
            ->editColumn('status', function ($row) {
                return '<label class="badge" style="color:#FFF;background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })
            ->editColumn('project_name', function ($row) {
                if (is_null($row->project_id)) {
                    return "";
                }
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->rawColumns(['status', 'project_name', 'due_date', 'users', 'heading'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->addIndexColumn();

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Task $model)
    {
        $request = $this->request();
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $employeeId = $request->employeeId;
        $projectId = $request->projectID;

        
           

        $model = $model->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                        ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                        ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
                        ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.due_date', 'tasks.project_id', 'taskboard_columns.column_name', 'taskboard_columns.label_color');

        if (!is_null($startDate)) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
            $model->where(DB::raw('DATE(tasks.`due_date`)'), '>=', $startDate);
        }

        if (!is_null($endDate)) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();  
            $model->where(DB::raw('DATE(tasks.`due_date`)'), '<=', $endDate);
        }

        if ($projectId != 0) {
            $model->where('tasks.project_id', '=', $projectId);
        }

        if ($request->clientID != '' && $request->clientID !=  null && $request->clientID !=  'all') {
            $model->where('projects.client_id', '=', $request->clientID);
        }

        if ($request->task_status != '' && $request->task_status !=  null && $request->task_status !=  'all') {
            $model->where('tasks.board_column_id', '=', $request->task_status);
        }


        if ($employeeId != 0) {
            $model->where('task_users.user_id', '=', $request->employeeId);
        }
           
        $model->with('users')->get();
        
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('tasks-table')
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
            //->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv', 'pdf']]))
            //->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']))
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["tasks-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });

                $(\'#tasks-table_wrapper select\').select2({minimumResultsForSearch: -1});    
                   
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
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'exportable' => false, 'printable' => false, 'visible' => false],
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('app.project')  => ['data' => 'project_name', 'name' => 'projects.project_name'],
            __('app.title') => ['data' => 'heading', 'name' => 'heading'],
            __('modules.tasks.assignTo') => ['data' => 'users', 'name' => 'member.name'],
            __('app.dueDate') => ['data' => 'due_date', 'name' => 'due_date'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Task report_' . date('YmdHis');
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
