<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Invoice;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class InvoicesDataTable extends BaseDataTable
{
    protected $firstInvoice;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $firstInvoice = $this->firstInvoice;
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($firstInvoice) {
                $action = '<div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                        <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                  <a href="' . route("admin.all-invoices.download", $row->id) . '"><img src="'.asset('img/icons/download.svg').'" /> ' . __('app.download') . '</a>';

                /*if ($row->status == 'paid') {
                    $action .= ' <a href="javascript:" data-invoice-id="' . $row->id . '" class="invoice-upload" data-toggle="modal" data-target="#invoiceUploadModal"><i class="fa fa-upload"></i> ' . __('app.upload') . ' </a>';
                }*/

                if ($row->status == 'unpaid') {
                    $action .= '<a href="' . route("admin.all-invoices.edit", $row->id) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . __('app.edit') . '</a>';
                    if (in_array('payments', $this->user->modules) && $row->credit_note == 0) {
                        $action .= '<a href="' . route("admin.payments.payInvoice", [$row->id]) . '" data-toggle="tooltip" ><img src="'.asset('img/icons/dollar-sign.svg').'" /> ' . __('modules.payments.addPayment') . '</a>';
                    }
                }
                if ($row->status == 'cancelled') {
                    $action .= '<a href="javascript:;" data-toggle="tooltip" title="' . __('app.unCancel') . '"  data-invoice-id="' . $row->id . '" class="sa-uncancel"><img src="'.asset('img/icons/rotate-ccw.svg').'" /> ' . __('app.unCancel') . '</a>';
                    //$action .= '<a href="' . route("admin.all-invoices.edit", $row->id) . '"><i class="fa fa-undo"></i> ' . __('app.unCancel') . '</a>';
                }
               /* if (isset($row->clientdetails)) {
                    if (!is_null($row->clientdetails->shipping_address)) {
                        if ($row->show_shipping_address === 'yes') {
                            $action .= '<a href="javascript:toggleShippingAddress(' . $row->id . ');"><i class="fa fa-eye-slash"></i> ' . __('app.hideShippingAddress') . '</a>';
                        }
                        else {
                            $action .= '<a href="javascript:toggleShippingAddress(' . $row->id . ');"><i class="fa fa-eye"></i> ' . __('app.showShippingAddress') . '</a>';
                        }
                    }
                    else {
                        $action .= '<a href="javascript:addShippingAddress(' . $row->id . ');"><i class="fa fa-plus"></i> ' . __('app.addShippingAddress') . '</a>';
                    }
                } else {
                    if (isset($row->project->clientdetails)) {
                        if (!is_null($row->project->clientdetails->shipping_address)) {
                            if ($row->show_shipping_address === 'yes') {
                                $action .= '<a href="javascript:toggleShippingAddress(' . $row->id . ');"><i class="fa fa-eye-slash"></i> ' . __('app.hideShippingAddress') . '</a>';
                            }
                            else {
                                $action .= '<a href="javascript:toggleShippingAddress(' . $row->id . ');"><i class="fa fa-eye"></i> ' . __('app.showShippingAddress') . '</a>';
                            }
                        }
                        else {
                            $action .= '<a href="javascript:addShippingAddress(' . $row->id . ');"><i class="fa fa-plus"></i> ' . __('app.addShippingAddress') . '</a>';
                        }
                    }
                } */              

                /*if ($firstInvoice->id != $row->id && $row->status == 'unpaid') {
                    $action .= '<a href="javascript:;" data-toggle="tooltip" title="' . __('app.cancel') . '"  data-invoice-id="' . $row->id . '" class="sa-cancel"><i class="fa fa-times"></i> ' . __('app.markCancel') . '</a>';
                }*/

                if ($row->status == 'unpaid' &&  $row->credit_note == 0) {
                    $action .= '<a href="' . route("front.invoice", [md5($row->id)]) . '" target="_blank" data-toggle="tooltip" ><img src="'.asset('img/icons/link.svg').'" /> ' . __('modules.payments.paymentLink') . '</a>';
                }
                if ($row->credit_note == 0) {
                    if ($row->status == 'unpaid') {
                        $action .= '<a href="' . route('admin.all-credit-notes.convert-invoice', $row->id) . '" data-toggle="tooltip"  data-invoice-id="' . $row->id . '" class="addCreditNote"><img src="'.asset('img/icons/plus-square.svg').'" /> ' . __('modules.credit-notes.CreditNote') . '</a>';
                    } //else {
                       // $action .= '<a href="javascript:;" data-toggle="tooltip"  data-invoice-id="' . $row->id . '" class="unpaidAndPartialPaidCreditNote"><i class="fa fa-plus"></i> ' . __('modules.credit-notes.addCreditNote') . '</a>';
                   // }
                }
                if ($row->status == 'unpaid') {
                    $action .= '<a href="javascript:;" data-toggle="tooltip"  data-invoice-id="' . $row->id . '" class="reminderButton"><img src="'.asset('img/icons/rss.svg').'" /> ' . __('app.payReminder') . '</a>';
                    $action .= '<a href="javascript:;" data-toggle="tooltip"  data-invoice-id="' . $row->id . '" class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . __('app.delete') . '</a>';
                }
               
                /*if ($firstInvoice->id == $row->id) {
                    $action .= '<a href="javascript:;" data-toggle="tooltip"  data-invoice-id="' . $row->id . '" class="sa-params"><i class="fa fa-times"></i> ' . __('app.delete') . '</a>';
                }*/

                $action .= '</div>  </div>
                    </div>
                </div>';

                return $action;
            })
            ->editColumn('project_name', function ($row) {
                if ($row->project_id != null) {
                    return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project->project_name) . '</a>';
                }

                return '--';
            })
            ->editColumn('name', function ($row) {
                if ($row->project && $row->project->client) {
                    return ucfirst($row->project->client->name);
                } else if ($row->client_id != '') {
                    $client = User::find($row->client_id);
                    return ucfirst($client->name);
                } else if ($row->estimate && $row->estimate->client) {
                    return ucfirst($row->estimate->client->name);
                } else {
                    return '--';
                }
            })
            ->editColumn('invoice_number', function ($row) {
                return '<a href="' . route('admin.all-invoices.show', $row->id) . '">' . ucfirst($row->invoice_number) . '</a>';
            })
            ->editColumn('status', function ($row) {
                if ($row->credit_note) {
                    return '<label class="badge badge-warning">' . strtoupper(__('app.credit-note')) . '</label>';
                } else {
                    if ($row->status == 'unpaid') {
                        return '<label class="badge badge-danger">' . strtoupper($row->status) . '</label>';
                    } elseif ($row->status == 'paid') {
                        return '<label class="badge badge-success">' . strtoupper($row->status) . '</label>';
                    } elseif ($row->status == 'cancelled') {
                        return '<label class="badge badge-danger">' . strtoupper(__('app.cancelled')) . '</label>';
                    } else {
                        return '<label class="badge badge-info">' . strtoupper(__('Waiting for Approval')) . '</label>';
                    }
                }
            })
            ->editColumn('total', function ($row) {
                $currencyCode = ' (' . $row->currency->currency_code . ') ';
                $currencySymbol = $row->currency->currency_symbol;

                return '<div class="text-right">'.__('app.total').': ' . $currencySymbol . currencyFormat($row->total) . '<br><span class="text-success">'.__('app.paid').':</span> ' . $currencySymbol . currencyFormat($row->amountPaid())  . '<br><span class="text-danger">'.__('app.unpaid').':</span> ' . $currencySymbol . currencyFormat($row->amountDue()) . '</div>';
            })
            ->editColumn(
                'issue_date',
                function ($row) {
                    return $row->issue_date->timezone($this->global->timezone)->format('m/d/Y');
                }
            )
            ->rawColumns(['project_name', 'action', 'status', 'invoice_number', 'total'])
            ->removeColumn('currency_symbol')
            ->removeColumn('currency_code')
            ->removeColumn('project_id');

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
    {
        $request = $this->request();

        $this->firstInvoice = Invoice::orderBy('id', 'desc')->first();
        $model = $model->with(['project:id,project_name,client_id', 'currency:id,currency_symbol,currency_code', 'project.client'])
            ->select('invoices.id', 'invoices.project_id', 'invoices.client_id', 'invoices.invoice_number', 'invoices.currency_id', 'invoices.total', 'invoices.status', 'invoices.issue_date', 'invoices.credit_note', 'invoices.show_shipping_address');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(invoices.`issue_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(invoices.`issue_date`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('invoices.status', '=', $request->status);
        }

        if ($request->projectID != 'all' && !is_null($request->projectID)) {
            $model = $model->where('invoices.project_id', '=', $request->projectID);
        }

        if ($request->clientID != 'all' && !is_null($request->clientID)) {
            $model = $model->where('client_id', '=', $request->clientID);
        }

        if (!is_null($request->status_by)) {
            switch ($request->status_by){
                case('paidInvoicesThisMonth'):{
                   // $model = $model->join('payments', 'payments.id', '=', 'payments.invoice_id')->whereBetween(DB::raw('DATE(payments.`created_at`)'),[ Carbon::now()->firstOfMonth()->format('Y-m-d'),Carbon::now()->lastOfMonth()->format('Y-m-d')]);
                    $model = $model->whereBetween(DB::raw('DATE(invoices.`issue_date`)'),[ Carbon::now()->firstOfMonth()->format('Y-m-d'),Carbon::now()->lastOfMonth()->format('Y-m-d')]);
                    break;
                }
                case('paidInvoicesThisYear'):{
                    $model = $model->whereBetween(DB::raw('DATE(invoices.`issue_date`)'),[ Carbon::now()->firstOfYear()->format('Y-m-d'),Carbon::now()->lastOfYear()->format('Y-m-d')]);
                    break;
                }

            }
        }

        $model = $model->whereHas('project', function ($q) {
            $q->whereNull('deleted_at');
        });

        /* $model = $model->whereHas('project', function ($q) {
            $q->whereNull('deleted_at');
        }, '>=', 0); */
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
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv', 'pdf']]))
            //->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']))
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
                    $(\'#status, #invoices-table_wrapper select\').select2({minimumResultsForSearch: -1});
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
            __('app.invoice'). '#' => ['data' => 'invoice_number', 'name' => 'invoice_number'],
            __('app.project')  => ['data' => 'project_name', 'name' => 'project.project_name'],
            __('app.client') => ['data' => 'name', 'name' => 'project.client.name'],
            __('modules.invoices.total') => ['data' => 'total', 'name' => 'total'],
            __('app.date') => ['data' => 'issue_date', 'name' => 'issue_date'],
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
