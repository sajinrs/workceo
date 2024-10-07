<?php

namespace App\DataTables\Client;

use App\DataTables\BaseDataTable;
use App\Http\Requests\TimeLogs\StoreTimeLog;
use App\Notice;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;

class NoticeDataTable extends BaseDataTable
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
            ->addColumn('action', function($row){

                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                              <a href="javascript:;"  data-notice-id="' . $row->id . '"  class="noticeShow sa-params"><i class="fa fa-search" aria-hidden="true"></i> ' . trans('app.view') . '</a>

                        </div>
                    </div>
                </div>';
                
                /* $action = '';

                $action.= '<a href="javascript:;" class="btn btn-secondary btn-circle noticeShow"
                  data-toggle="tooltip" data-notice-id="' . $row->id . '" data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a> ';

                if($this->user->can('edit_notice')){
                    $action.= '<a href="'.route('client.notices.edit', [$row->id]).'" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if($this->user->can('delete_notice')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                } */
                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->addIndexColumn();

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Notice $notice)
    {
        $request = $this->request();        
        $startDate = $request->startDate;
        $endDate = $request->endDate;      
        
        
        $notice = Notice::select('id','heading', 'created_at')->where('to', 'client');
        if($request->startDate !== null && $request->startDate != 'null' && $request->startDate != ''){
            $startDate = $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->format('Y-m-d');
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '>=', $startDate);
        }

        if($request->endDate !== null && $request->endDate != 'null' && $request->endDate != ''){
            $endDate = $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->format('Y-m-d');
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '<=', $endDate);
        }

        
        return $notice;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('notice-table')
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
                   window.LaravelDataTables["notice-table"].buttons().container()
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
            __('modules.notices.notice')  => ['data' => 'heading', 'name' => 'heading'],
            __('app.date') => ['data' => 'created_at', 'name' => 'created_at'],
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
        return 'Notice report_' . date('YmdHis');
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
