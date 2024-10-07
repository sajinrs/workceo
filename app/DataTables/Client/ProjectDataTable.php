<?php

namespace App\DataTables\Client;

use App\DataTables\BaseDataTable;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;


class ProjectDataTable extends BaseDataTable
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
                return '<a href="' . route('client.projects.show', [$row->id]) . '" class="btn btn-sm btn-secondary btn-circle"
                      data-toggle="tooltip" data-original-title="View Project Details"><i class="fa fa-search" aria-hidden="true"></i></a>';
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
                return '<a href="' . route('client.projects.show', $row->id) . '">' . ucfirst($row->project_name) . '</a>';
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
            ->editColumn('completion_percent', function ($row) {
                $status = '';
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
            ->removeColumn('client_id')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $project)
    {
        $request = $this->request();

        $projects = Project::select('projects.id', 'projects.project_name', 'projects.project_summary', 'projects.start_date', 'projects.deadline', 'projects.notes', 'projects.category_id', 'projects.client_id', 'projects.feedback', 'projects.completion_percent', 'projects.created_at', 'projects.updated_at', 'projects.status')
            ->where('projects.client_id', '=', $this->user->id);

        return $projects;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('project-table')
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
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv', 'pdf']]))
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["project-table"].buttons().container()
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'exportable' => false, 'printable' => false, 'visible' => false],
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('modules.projects.projectName') => ['data' => 'project_name', 'name' => 'project_name'],
            __('modules.projects.members') => ['data' => 'members', 'name' => 'members'],
            __('modules.projects.deadline') => ['data' => 'deadline', 'name' => 'deadline'],
            __('app.completion') => ['data' => 'completion_percent', 'name' => 'completion_percent'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],   
            __('app.action') => ['data' => 'action', 'name' => 'action'],          
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Jobs_' . date('YmdHis');
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
