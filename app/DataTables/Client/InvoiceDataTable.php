<?php

namespace App\DataTables\Client;

use App\DataTables\BaseDataTable;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;


class InvoiceDataTable extends BaseDataTable
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
                return '<a href="' . route('client.invoices.download', $row->id) . '" data-toggle="tooltip" data-original-title="Download" class="btn  btn-sm btn-outline btn-info"><i class="fa fa-download"></i> '.__('app.download').'</a>';
            })
            ->editColumn('project_name', function ($row) {
                return $row->project_name != '' ? $row->project_name : '--';
            })
            ->editColumn('invoice_number', function ($row) {
                return '<a style="text-decoration: underline" href="' . route('client.invoices.show', $row->id) . '">' . $row->invoice_number . '</a>';
            })
            ->editColumn('currency_symbol', function ($row) {
                return $row->currency_symbol . ' (' . $row->currency_code . ')';
            })
            ->editColumn('total', function ($row) {
                return currencyFormat($row->total);
            })
            ->editColumn('issue_date', function ($row) {
                return $row->issue_date->format($this->global->date_format);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'unpaid') {
                    return '<label class="badge badge-danger">' . strtoupper($row->status) . '</label>';
                } else if($row->status == 'review') {
                    return '<label class="badge badge-warning">' . strtoupper($row->status) . '</label>';
                } else if(empty($row->status) || $row->status == 'partial'){
                    return '<label class="badge badge-info">' . strtoupper(__('Waiting for Approval')) . '</label>';
                } else {
                    return '<label class="badge badge-success">' . strtoupper($row->status) . '</label>';
                }
            })
            ->rawColumns(['action', 'status', 'invoice_number'])
            ->removeColumn(['currency_code'])
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $invoice)
    {
        $request = $this->request();

        $invoices = Invoice::leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
                            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
                            ->select('invoices.id', 'projects.project_name', 'invoices.invoice_number', 'currencies.currency_symbol', 'currencies.currency_code', 'invoices.total', 'invoices.issue_date', 'invoices.status')
                            ->where(function ($query) {
                                $query->where('projects.client_id', $this->user->id)
                                    ->orWhere('invoices.client_id', $this->user->id);
                            })->where('invoices.status', '!=', 'canceled');
        return $invoices;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('invoices-table')
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
                   window.LaravelDataTables["invoices-table"].buttons().container()
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
            __('modules.projects.projectName') => ['data' => 'project_name', 'name' => 'projects.project_name'],
            __('app.invoice #') => ['data' => 'invoice_number', 'name' => 'invoice_number'],
            __('modules.invoices.currency') => ['data' => 'currency_symbol', 'name' => 'currencies.currency_symbol'],
            __('modules.invoices.amount') => ['data' => 'total', 'name' => 'total'],
            __('modules.invoices.invoiceDate') => ['data' => 'issue_date', 'name' => 'issue_date'],
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
        return 'Invoices_' . date('YmdHis');
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
