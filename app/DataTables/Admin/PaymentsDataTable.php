<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PaymentsDataTable extends BaseDataTable
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
                                <a href="' . route("admin.payments.edit", $row->id) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>
                                <a href="javascript:;" data-payment-id="' . $row->id . '" class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . trans('app.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </div>';                

                return $action;
            })
            ->editColumn('remarks', function ($row) {
                return ucfirst($row->remarks);
            })

            ->editColumn('project_id', function ($row) {
                if (!is_null($row->project)) {
                    return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project->project_name) . '</a>';
                } else {
                    return '--';
                }
            })
            ->editColumn('invoice_number', function ($row) {
                if ($row->invoice_id != null) {
                    return $row->invoice->invoice_number;
                } else {
                    return '--';
                }
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'pending') {
                    return '<label class="badge badge-warning w-100">' . ucfirst($row->status) . '</label>';
                } else {
                    return '<label class="badge badge-success w-100">' . ucfirst($row->status) . '</label>';
                }
            })
            ->editColumn('remarks', function ($row) {
                return str_limit($row->remarks, 20);
            })
            ->editColumn('amount', function ($row) {
                $symbol = (!is_null($row->currency)) ? $row->currency->currency_symbol : '';
                $code = (!is_null($row->currency)) ? $row->currency->currency_code : '';

                return $symbol . currencyFormat($row->amount) . ' (' . $code . ')';
            })
            ->editColumn(
                'paid_on',
                function ($row) {
                    if (!is_null($row->paid_on)) {
                        return $row->paid_on->format('m/d/Y ' . $this->global->time_format);
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['invoice', 'action', 'status', 'project_id'])
            ->removeColumn('invoice_id')
            ->removeColumn('currency_symbol')
            ->removeColumn('currency_code')
            ->removeColumn('project_name');

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payment $model)
    {
        $request = $this->request();

        $model = $model->with(['project:id,project_name', 'currency:id,currency_symbol,currency_code', 'invoice'])
            ->leftJoin('projects', 'projects.id', '=', 'payments.project_id')
            ->select('payments.id', 'payments.project_id', 'payments.currency_id', 'payments.invoice_id', 'payments.amount', 'payments.status', 'payments.paid_on', 'payments.remarks', 'payments.gateway');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(payments.`paid_on`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(payments.`paid_on`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('payments.status', '=', $request->status);
        }

        if ($request->project != 'all' && !is_null($request->project)) {
            $model = $model->where('payments.project_id', '=', $request->project);
        }

        if ($request->client != 'all' && !is_null($request->client)) {
            $model = $model->where('projects.client_id', '=', $request->client);
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
            ->setTableId('payments-table')
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
                   window.LaravelDataTables["payments-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    
                    $(\'#status, #payments-table_wrapper select\').select2({minimumResultsForSearch: -1});
                }',
            ])
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]));
            //->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']));
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
            __('app.job')  => ['data' => 'project_id', 'name' => 'project_id'],
            __('app.invoice'). '#' => ['data' => 'invoice_number', 'name' => 'invoice.invoice_number'],
            __('modules.invoices.amount') => ['data' => 'amount', 'name' => 'amount'],
            __('modules.payments.paidOn') => ['data' => 'paid_on', 'name' => 'paid_on'],
            __('modules.payments.paymentMode') => ['data' => 'gateway', 'name' => 'gateway'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
            __('app.remark') => ['data' => 'remarks', 'name' => 'remarks'],
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
        return 'Payments_' . date('YmdHis');
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
