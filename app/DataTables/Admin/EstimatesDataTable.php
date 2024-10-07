<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Estimate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class EstimatesDataTable extends BaseDataTable
{
    protected $firstEstimate;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $firstEstimate = $this->firstEstimate;
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($firstEstimate) {
                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <a href="' . route("admin.estimates.download", $row->id) . '" ><img src="'.asset('img/icons/download.svg').'" /> Download</a>
                                <a href="' . route("front.estimate.show", md5($row->id)) . '" target="_blank"><img src="'.asset('img/icons/search.svg').'" /> View</a>';
                                $action .= '<a href="' . route("admin.estimates.edit", $row->id) . '" ><img src="'.asset('img/icons/edit.svg').'" /> Edit</a>';
                                $action .= '<a class="sa-params" href="javascript:;" data-estimate-id="' . $row->id . '"><img src="'.asset('img/icons/delete.svg').'" /> Delete</a>';
                                //if ($row->status == 'waiting' || $row->status == 'declined') {
                                   // $action .= '<a href="' . route("admin.estimates.edit", $row->id) . '" ><i class="fa fa-edit"></i> Edit</a>';
                                //}                                
                                //if ($row->status == 'waiting') {
                                   // $action .= '<a href="' . route("admin.all-invoices.convert-estimate", $row->id) . '" ><i class="fa fa-file"></i> Create Invoice</a>';
                                //}
                                //if ($firstEstimate->id == $row->id) {
                                //$action .= '<a class="sa-params" href="javascript:;" data-estimate-id="' . $row->id . '"><i class="fa fa-times"></i> Delete</a>';
                                //}
                            '</div>
                        </div>
                    </div>
                </div>';
                
                return $action;
            })
            ->addColumn('original_estimate_number', function ($row) {
                return $row->original_estimate_number;
            })
            ->editColumn('name', function ($row) {
                return '<a href="' . route('admin.clients.projects', $row->client_id) . '">' . ucwords($row->name) . '</a>';
            })
            ->editColumn('status', function ($row) {
               /* if ($row->status == 'waiting') {
                   // return '<label class="badge badge-warning">' . ucfirst($row->status) . '</label>';
                    return '<label class="text-warning">' . ucfirst($row->status) . '</label>';
                }
                if ($row->status == 'declined') {
                    //return '<label class="badge badge-danger">' . ucfirst($row->status) . '</label>';
                    return '<label class="text-danger">' . ucfirst($row->status) . '</label>';
                } else {
                    //return '<label class="badge badge-success">' . ucfirst($row->status) . '</label>';
                    return '<label class="text-success">' . ucfirst($row->status) . '</label>';
                }*/
                return $row->status;
            })
            ->editColumn('total', function ($row) {
                return $row->currency_symbol . currencyFormat($row->total);
            })
            ->editColumn(
                'valid_till',
                function ($row) {
                    return Carbon::parse($row->valid_till)->format('m/d/Y');
                }
            )
            ->addColumn(
                'created',
                function ($row) {
                    return Carbon::parse($row->created_at)->format('m/d/Y');
                }
            )
            
            ->rawColumns(['name', 'action','created', 'status'])
            ->removeColumn('currency_symbol')
            ->removeColumn('client_id');

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Estimate $model)
    {
        $request = $this->request();

        $this->firstEstimate = Estimate::latest()->first();
        $model = $model->join('client_details', 'estimates.client_id', '=', 'client_details.user_id')
            ->join('currencies', 'currencies.id', '=', 'estimates.currency_id')
            ->join('users', 'users.id', '=', 'estimates.client_id')
            ->select('estimates.id', 'estimates.client_id', 'users.name', 'estimates.total', 'currencies.currency_symbol', 'estimates.status','estimates.created_at', 'estimates.valid_till', 'estimates.estimate_number');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(estimates.`valid_till`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(estimates.`valid_till`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('estimates.status', '=', $request->status);
        }

        return $model->orderBy('estimates.id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('estimates-table')
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
                   window.LaravelDataTables["estimates-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'select\').select2({minimumResultsForSearch: -1});
                }',
            ])
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]));
//            ->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']));
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
            __('app.estimate') => ['data' => 'original_estimate_number', 'name' => 'original_estimate_number'],
            __('app.client')  => ['data' => 'name', 'name' => 'users.name'],
            __('modules.invoices.total') => ['data' => 'total', 'name' => 'total'],
            __('modules.estimates.created') => ['data' => 'created', 'name' => 'created_at'],
            __('modules.estimates.validTill') => ['data' => 'valid_till', 'name' => 'valid_till'],
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
        return 'estimates_' . date('YmdHis');
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
