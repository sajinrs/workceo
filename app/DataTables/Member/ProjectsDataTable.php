<?php

namespace App\DataTables\Member;

use App\DataTables\BaseDataTable;
use App\Payment;
use App\Project;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Setting;
use Carbon\Carbon;

class ProjectsDataTable extends BaseDataTable
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
                $action = '<div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">';

                if ($row->project_admin == $this->userDetail->id || $this->user->can('edit_projects')) {
                    $action .= '<a href="' . route('member.projects.edit', [$row->id]) . '"><i class="fa fa-pen" aria-hidden="true"></i> '.trans('app.edit').'</a>';
                }
                $action .= '<a href="' . route('member.projects.show', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> View Project Details</a>';
                $action .= '<a href="' . route('member.projects.gantt', [$row->id]) . '"><i class="fas fa-chart-bar" aria-hidden="true"></i> '.trans('modules.projects.viewGanttChart').'</a>';
                $action .= '<a href="' . route('front.gantt', [md5($row->id)]) . '" target="_blank"><i class="fas fa-chart-line" aria-hidden="true"></i> '.trans('modules.projects.viewPublicGanttChart').'</a>';

                if ($this->user->can('delete_projects')) {
                    $action .= '<a href="javascript:;" data-user-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> '.trans('app.delete').'</a>';
                }

                $action .= '</div>  
                            </div>
                            </div>
                            </div>';

                return $action;
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

                if ($this->user->can('add_projects')) {
                    $members .= '<br><br><a class="font-12" href="' . route('member.project-members.show', $row->id) . '"><i class="fa fa-plus"></i> ' . __('modules.projects.addMemberTitle') . '</a>';
                }
                return $members;
            })

            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('member.projects.show', $row->id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('deadline', function ($row) {
                if ($row->deadline) {
                    return $row->deadline->format($this->global->date_format);
                }

                return '-';
            })
            ->editColumn('client_id', function ($row) {
                if (!is_null($row->client_id)) {
                    return ucwords($row->client->name);
                } else {
                    return '--';
                }
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
            ->editColumn('status', function ($row) {

                $status = '';
                if ($row->status == 'in progress') {
                    $status = '<label class="badge badge-info">' . __('app.inProgress') . '</label>';
                } else if ($row->status == 'on hold') {
                    $status = '<label class="badge badge-warning">' . __('app.onHold') . '</label>';
                } else if ($row->status == 'not started') {
                    $status = '<label class="badge badge-warning">' . __('app.notStarted') . '</label>';
                } else if ($row->status == 'canceled') {
                    $status = '<label class="badge badge-danger">' . __('app.canceled') . '</label>';
                } else if ($row->status == 'finished') {
                    $status = '<label class="badge badge-success">' . __('app.finished') . '</label>';
                }
                return $status;
            })
            ->rawColumns(['project_name', 'action', 'members', 'completion_percent', 'status'])
            ->removeColumn('project_summary')
            ->removeColumn('notes')
            ->removeColumn('category_id')
            ->removeColumn('feedback')
            ->removeColumn('start_date')
            ->addIndexColumn();

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
    {
        $request = $this->request();
        $this->userDetail = auth()->user();

        $model = $model->select('projects.id', 'projects.project_name', 'projects.project_admin', 'projects.project_summary', 'projects.start_date', 'projects.deadline', 'projects.notes', 'projects.category_id', 'projects.client_id', 'projects.feedback', 'projects.completion_percent', 'projects.created_at', 'projects.updated_at', 'projects.status');

        if (!is_null($request->status) && $request->status != 'all') {
            $model->where('status', $request->status);
        }

        if (!$this->user->can('view_projects')) {
            $model = $model->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $model = $model->where('project_members.user_id', '=', $this->userDetail->id);
        }

        if (!is_null($request->status) && $request->status != 'all') {
            if ($request->status == 'incomplete') {
                $projects->where('completion_percent', '<', '100');
            } elseif ($request->status == 'complete') {
                $projects->where('completion_percent', '=', '100');
            }
        }


        if (!is_null($request->client_id) && $request->client_id != 'all') {
            $model->where('client_id', $request->client_id);
        }

        

        if (!is_null($request->status_by) && $request->status_by != 'total') {
            switch ($request->status_by){
                case('inProcess'):{
                    $model->where('status', 'in progress');
                    break;
                }
                case('finished'):{
                    $model->where('completion_percent', '100');
                    break;
                }
                case('canceled'):{
                    $model->where('status', 'canceled');
                    break;
                }
                case('notStarted'):{
                    $model->where('status', 'not started');
                    break;
                }
                case('overdue'):{
                    $setting = Setting::first();
                    $model->where('completion_percent', '<>', '100')
                          ->where('deadline', '<', Carbon::today()->timezone($setting->timezone));
                    break;
                }
                
            }
        }

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
            ->setTableId('projects-table')
            //->lengthMenu([2, 5, 10, 25])
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
                    window.LaravelDataTables["projects-table"].buttons().container()
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
        return [
            //'#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'exportable' => false, 'printable' => false, 'visible' => false],
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('modules.projects.projectName') => ['data' => 'project_name', 'name' => 'project_name'],
            __('app.client') => ['data' => 'client_id', 'name' => 'client_id'],
            __('modules.projects.members')  => ['data' => 'members', 'name' => 'members'],
            __('app.deadline') => ['data' => 'deadline', 'name' => 'deadline'],
            __('app.completion') => ['data' => 'completion_percent', 'name' => 'completion_percent'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
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
        return 'Projects_' . date('YmdHis');
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
