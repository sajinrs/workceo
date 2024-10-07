<?php

namespace App\DataTables\Client;

use App\DataTables\BaseDataTable;
use App\Payment;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;


class PaymentDataTable extends BaseDataTable
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
            ->editColumn('remarks', function($row) {
                return ucfirst($row->remarks);
            })

            ->editColumn('project_id', function($row) {
                if ($row->project_id != null) {
                    return '<a href="' . route('client.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
                } else {
                    return '--';
                }

            })
            ->editColumn('amount', function ($row) {
                return $row->currency_symbol . currencyFormat($row->amount). ' ('.$row->currency_code.')';
            })
            ->editColumn(
                'paid_on',
                function ($row) {
                    if(!is_null($row->paid_on)){
                        return $row->paid_on->format($this->global->date_format .' '. $this->global->time_format);
                    }
                }
            )
            ->rawColumns(['status', 'project_id'])
            ->removeColumn('invoice_id')
            ->removeColumn('currency_symbol')
            ->removeColumn('currency_code')
            ->removeColumn('project_name')
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payment $payment)
    {
        $request = $this->request();

        $payments = Payment::leftJoin('projects', 'projects.id', '=', 'payments.project_id')
            ->join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->select('payments.id', 'payments.project_id', 'projects.project_name', 'payments.amount', 'currencies.currency_symbol', 'currencies.currency_code', 'payments.status', 'payments.paid_on', 'payments.remarks', 'payments.transaction_id');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $payments = $payments->where(DB::raw('DATE(payments.`paid_on`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $payments = $payments->where(DB::raw('DATE(payments.`paid_on`)'), '<=', $endDate);
        }

        $payments = $payments->where('payments.status', '=', 'complete');

        if ($request->project != 'all' && !is_null($request->project)) {
            $payments = $payments->where('payments.project_id', '=', $request->project);
        }

        $payments = $payments->where('projects.client_id', '=', $this->user->id);

        $payments = $payments->orderBy('payments.id', 'desc');

        return $payments;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('payment-table')
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
                   window.LaravelDataTables["payment-table"].buttons().container()
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
            __('app.project') => ['data' => 'project_id', 'name' => 'project_id'],
            __('modules.invoices.amount') => ['data' => 'amount', 'name' => 'amount'],
            __('modules.payments.transactionId') => ['data' => 'transaction_id', 'name' => 'transaction_id'],
            __('modules.payments.paidOn') => ['data' => 'paid_on', 'name' => 'paid_on'],
            __('app.remark') => ['data' => 'remarks', 'name' => 'remarks'],           
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
