<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Task;
use App\TaskboardColumn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AllTasksArhiveDataTable extends BaseDataTable
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
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <a href="javascript:;" onclick="restore('.$row->id.')"><i class="fa fa-undo"></i> Restore</a>';                               

                            $recurringTaskCount = Task::where('recurring_task_id', $row->id)->count();
                            $recurringTask = $recurringTaskCount > 0 ? 'yes' : 'no';
            
                            $action .= '<a href="javascript:;" class="sa-params"
                                  data-task-id="' . $row->id . '" data-recurring="' . $recurringTask . '"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>';
                            '</div>
                        </div>
                    </div>
                </div>';

                return $action;
                
            })
            

            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    //return '<span class="text-danger">' . $row->due_date->format('m/d/Y') . '</span>';
                    return $row->due_date->format('m/d/Y');
                }
                //return '<span class="text-success">' . $row->due_date->format('m/d/Y') . '</span>';
                return $row->due_date->format('m/d/Y');
            })
            ->editColumn('users', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members.= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members.= '</a>';
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
                $name = ucfirst($row->heading);

                if ($row->is_private) {
                    $name.= ' <i data-toggle="tooltip" data-original-title="' . __('app.private') . '" class="fa fa-lock" style="color: #ea4c89"></i>';
                }
                return $name;
            })
            ->editColumn('column_name', function ($row) {
                return '<label class="badge" style="color:#fff;background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })
            ->editColumn('project_name', function ($row) {
                if (is_null($row->project_id)) {
                    return "";
                }
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })

            ->editColumn('priority', function ($row) {
                if($row->priority == 'high') {
                    $priority = '<label class="text-danger">'.ucfirst($row->priority).'</label>';
                } else if($row->priority == 'medium') {
                    $priority = '<label class="text-warning">'.ucfirst($row->priority).'</label>';
                }  else if($row->priority == 'low') {
                    $priority = '<label class="text-success">'.ucfirst($row->priority).'</label>';
                }

                return $priority;
            })

            ->rawColumns(['column_name', 'action', 'checkbox', 'project_name', 'clientName', 'due_date', 'users', 'created_by', 'heading', 'priority'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->removeColumn('created_image')
            ->removeColumn('label_color');

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
        $startDate = null;
        $endDate = null;
        
        $projectId =  $request->projectId;
        $hideCompleted = $request->hideCompleted;
        $taskBoardColumn = TaskboardColumn::where('slug', 'completed')->first();

        $model = $model->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.priority', 'client.name as clientName', 'creator_user.name as created_by', 'creator_user.image as created_image', 'tasks.due_date', 'taskboard_columns.column_name', 'taskboard_columns.label_color', 'tasks.project_id', 'tasks.is_private')
            ->whereNull('projects.deleted_at')
            ->with('users')
            ->groupBy('tasks.id');
            
        if (($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') && $request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);
                $q->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
            });
        }
        if ($projectId != 0 && $projectId !=  null && $projectId !=  'all') {
            $model->where('tasks.project_id', '=', $projectId);
        }
        if ($request->clientID != '' && $request->clientID !=  null && $request->clientID !=  'all') {
            $model->where('projects.client_id', '=', $request->clientID);
        }
        if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
            $model->where('task_users.user_id', '=', $request->assignedTo);
        }

        if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
            $model->where('creator_user.id', '=', $request->assignedBY);
        }
        if ($request->status != '' && $request->status !=  null && $request->status !=  'all') {
            $model->where('tasks.board_column_id', '=', $request->status);
        }
        if ($hideCompleted == '1') {
            $model->where('tasks.board_column_id', $taskBoardColumn->id);
        }
        return $model->onlyTrashed();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('allTasks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-10'Bl><'col-md-2'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            //->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
           /*  ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ])) */
            //->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']))
            
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["allTasks-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'#status, #allTasks-table_wrapper select\').select2({minimumResultsForSearch: -1});
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('app.task') => ['data' => 'heading', 'name' => 'heading'],
            __('app.project')  => ['data' => 'project_name', 'name' => 'projects.project_name'],
            //__('modules.tasks.assignTo') => ['data' => 'users', 'name' => 'member.name'],
            __('app.dueDate') => ['data' => 'due_date', 'name' => 'due_date'],
            __('Priority') => ['data' => 'priority', 'name' => 'priority'],
            __('app.status') => ['data' => 'column_name', 'name' => 'taskboard_columns.column_name'],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'All_Task_' . date('YmdHis');
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
