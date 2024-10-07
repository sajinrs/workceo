<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\GroupTaskCategory;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class GroupTaskDataTable extends BaseDataTable
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

                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <a href="' . route("admin.task-groups.edit", $row->id) . '"><i class="fa fa-edit" aria-hidden="true"></i> ' . trans('app.edit') . '</a>
                                <a href="javascript:;"  data-cat-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </div>';                

                return $action;
            })
            
            ->editColumn('name', function ($row) {
                return ucfirst($row->title);
            })  

            ->editColumn('total_task', function ($row) {
                return ucfirst($row->total_task);
            }) 

            ->addIndexColumn()
            ->rawColumns(['action']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(GroupTaskCategory $model)
    {
        $model = $model->leftJoin('group_tasks', 'group_task_categories.id', '=', 'group_tasks.task_category_id')
                       ->select('group_task_categories.id', 'group_task_categories.title', DB::raw('COUNT(task_category_id) as total_task'))
                       ->groupBy('task_category_id');

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
            ->setTableId('group-task-table')
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
            ->parameters([
                'lengthMenu' => [
                    [ 10, 25, 50, 100 ],
                    [ 'Show 10', 'Show 25', 'Show 50', 'Show 100' ]
                ],

                "oLanguage"=> ['sSearchPlaceholder'=>"Search...", 'sSearch'=>'<i class="fa fa-search"></i> _INPUT_',"sLengthMenu"=> "_MENU_"],
                
                'initComplete' => 'function () {
                   window.LaravelDataTables["group-task-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                $(\'#group-task-table_wrapper select\').select2({minimumResultsForSearch: -1});
                    
                }',
            ])
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]));
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
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('app.category') => ['data' => 'name', 'name' => 'name'],
            ('Total Task') => ['data' => 'total_task', 'name' => 'total_task'],
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
        return 'Task_' . date('YmdHis');
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
