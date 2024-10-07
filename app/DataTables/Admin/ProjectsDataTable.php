<?php

namespace App\DataTables\Admin;

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

                $action = ' <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                        <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">';
                            if($row->job_status == 'closed') {
                                $action .= '<a onclick="editClosedJob('.$row->id.')" href="javascript:;"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>';
                            } else {
                                $action .= '<a href="' . route('admin.projects.edit', [$row->id]) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>';
                            }
                           
                  $action .= '<a href="' . route('admin.projects.show', [$row->id]) . '"><img src="'.asset('img/icons/search.svg').'" /> ' . trans('app.view') .'</a>';
                 
                 /* <a href="' . route('admin.projects.gantt', [$row->id]) . '"><i class="fas fa-chart-bar" aria-hidden="true"></i> ' . trans('modules.projects.viewGanttChart') . '</a>
                   */ 
                 /* <a href="' . route('front.gantt', [md5($row->id)]) . '" target="_blank"><i class="fas fa-chart-line" aria-hidden="true"></i> ' . trans('modules.projects.viewPublicGanttChart') . '</a>
                   */
                  $action .= '<a href="' . route('admin.projects.copy', [$row->id]) . '"><img src="'.asset('img/icons/copy.svg').'" /> ' . trans('modules.jobs.copy') . '</a> 
                  <a href="javascript:;" data-user-id="' . $row->id . '" class="archive"><img src="'.asset('img/icons/archive1.svg').'" /> ' . trans('app.archive') . '</a>
                  <a href="javascript:;" data-user-id="' . $row->id . '" class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . trans('app.delete') . '</a>
                      </div>  </div>
                    </div>
                </div>';

                return $action;
            })
            ->addColumn('members', function ($row) {
                $members = '';

                if (count($row->members) > 0) {
                    foreach ($row->members as $member) {
                        $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->user->name) . '" src="' . $member->user->image_url . '"
                        alt="user" class="img-circle rounded-circle m-b-5" width="40" height="40"> ';
                    }
                } else {
                    $members .= __('messages.noMemberAddedToProject');
                }
                $members .= '<a class="btn btn-xs add_member_btn" data-toggle="tooltip" data-original-title="' . __('modules.projects.addMemberTitle') . '"  href="' . route('admin.project-members.show', $row->id) . '"><i class="fa fa-plus" ></i></a>';
                return $members;
            })
            ->editColumn('project_name', function ($row) {
                $name = '<a href="' . route('admin.projects.show', $row->id) . '">' . ucfirst($row->project_name) . '</a>';

                return $name;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('deadline', function ($row) {
                if ($row->deadline) {
                    return $row->deadline->format('m/d/Y');
                }

                return '-';
            })
            ->editColumn('client_id', function ($row) {
                if (is_null($row->client_id)) {
                    return "";
                }
                return (!is_null($row->clientdetails) && $row->clientdetails->company_name != '') ? ucwords($row->client->name) . "<br>[" . $row->clientdetails->company_name . "]" : ucwords($row->client->name);
            })
            ->editColumn('status', function ($row) {

                //$status = 'no';
                if ($row->job_status == 'schedule') {
                    $status = '<label class="badge badge-danger">Scheduled</label>';
                } else if ($row->job_status == 'omw') {
                    $status = '<label class="badge badge-warning">En Route</label>';
                } else if ($row->job_status == 'start') {
                    $status = '<label class="badge badge-yellow">Started</label>';
                } else if ($row->job_status == 'finish') {
                    $status = '<label class="badge badge-success">Finished</label>';
                } else if ($row->job_status == 'invoice') {
                    $status = '<label class="badge badge-secondary">Invoiced</label>';
                } else if ($row->job_status == 'paid') {
                    $status = '<label class="badge badge-primary">Paid</label>';
                } else if ($row->job_status == 'closed') {
                    $status = '<label class="badge badge-dark">Closed</label>';
                } 
                
                /* if ($row->status == 'in progress') {
                    $status = '<label class="badge badge-info">' . __('app.inProgress') . '</label>';
                } else if ($row->status == 'on hold') {
                    $status = '<label class="badge badge-warning">' . __('app.onHold') . '</label>';
                } else if ($row->status == 'not started') {
                    $status = '<label class="badge badge-warning">' . __('app.notStarted') . '</label>';
                } else if ($row->status == 'canceled') {
                    $status = '<label class="badge badge-danger">' . __('app.canceled') . '</label>';
                } else if ($row->status == 'awaiting invoice') {
                    $status = '<label class="badge badge-yellow">' . __('app.awaitingInvoice') . '</label>';
                } else if ($row->status == 'awaiting pay') {
                    $status = '<label class="badge badge-cyan">' . __('app.awaitingPay') . '</label>';
                } else if ($row->status == 'paid') {
                    $status = '<label class="badge badge-ltgreen">' . __('app.paid') . '</label>';
                } else if ($row->status == 'finished') {
                    $status = '<label class="badge badge-dark">' . __('app.closed') . '</label>';
                } */
                return $status;
            })
            ->editColumn('completion_percent', function ($row) {
                return $row->completion_percent.'%';

               /* $status = __('app.progress');

                if ($row->job_status == 'schedule') {
                    $statusColor = 'danger';
                } else if ($row->job_status == 'omw') {
                    $statusColor = 'warning';
                } else if ($row->job_status == 'start') {
                    $statusColor = 'yellow';
                } else if ($row->job_status == 'finish') {
                    $statusColor = 'success';
                } else if ($row->job_status == 'invoice') {
                    $statusColor = 'secondary';
                } else if ($row->job_status == 'paid') {
                    $statusColor = 'primary';
                } else if ($row->job_status == 'closed') {
                    $statusColor = 'dark';                    
                } 

                /* if ($row->completion_percent < 50) {
                    $statusColor = 'danger';
                    $status = __('app.progress');
                } elseif ($row->completion_percent >= 50 && $row->completion_percent < 75) {
                    $statusColor = 'warning';
                    $status = __('app.progress');
                } else {
                    $statusColor = 'success';
                    $status = __('app.progress');

                    if ($row->completion_percent >= 100) {
                        $status = __('app.progress');
                    }
                } */

                /*$pendingPayment = 0;
                $projectEarningTotal = Payment::join('projects', 'projects.id', '=', 'payments.project_id')
                    ->where('payments.status', 'complete')
                    ->whereNotNull('projects.project_budget')
                    ->where('payments.project_id', $row->id)
                    ->sum('payments.amount');
                $pendingPayment = ($row->project_budget - $projectEarningTotal);

                $pendingAmount = '';
                if ($pendingPayment > 0 && isset($row->currency->currency_symbol)) {
                    $pendingAmount = $row->currency->currency_symbol . $pendingPayment;
                }

                $progress = '<h5>' . $status . '<span class="pull-right">' . $row->completion_percent . '%</span></h5><div class="progress">
                  <div class="progress-bar bg-' . $statusColor . '" aria-valuenow="' . $row->completion_percent . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $row->completion_percent . '%" role="progressbar"> <span class="sr-only">' . $row->completion_percent . '% Complete</span> </div>
                </div>';

                if ($pendingAmount != '') {
                    $progress .= '<small class="text-danger">' . __('app.unpaid') . ': ' . $pendingAmount . '</small>';
                }

                return $progress;*/
            })
            ->addIndexColumn()
            //->rawColumns(['project_name', 'action', 'completion_percent', 'members', 'status', 'client_id'])
            ->rawColumns(['project_name', 'action', 'completion_percent','members','status', 'client_id'])
            ->removeColumn('project_summary')
            ->removeColumn('notes')
            ->removeColumn('category_id')
            ->removeColumn('feedback')
            ->removeColumn('start_date');

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

        $model = $model->with('members', 'members.user', 'client', 'clientdetails', 'currency')->select('id', 'project_name', 'start_date', 'deadline', 'client_id', 'completion_percent', 'status', 'job_status', 'project_budget', 'currency_id');

        if (!is_null($request->status) && $request->status != 'all') {
            $model->where('status', $request->status);
        }

        if (!is_null($request->client_id) && $request->client_id != 'all') {
            $model->where('client_id', $request->client_id);
        }

        if (!is_null($request->team_id) && $request->team_id != 'all') {
            $model->where('team_id', $request->team_id);
        }

        if (!is_null($request->category_id) && $request->category_id != 'all') {
            $model->where('category_id', $request->category_id);
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
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]))
          //  ->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']))
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
                     $(\'#status, #projects-table_wrapper select\').select2({minimumResultsForSearch: -1});
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
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('modules.projects.projectName') => ['data' => 'project_name', 'name' => 'project_name'],
            __('app.client') => ['data' => 'client_id', 'name' => 'client_id'],
            __('modules.projects.members')  => ['data' => 'members', 'name' => 'members', 'width' => 230],
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