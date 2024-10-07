<?php

namespace App\DataTables\Client;

use App\DataTables\BaseDataTable;
use App\Contract;
use App\ContractDiscussion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;


class ContractDataTable extends BaseDataTable
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
            ->addColumn('action', function($row) {
                return '<a href="'.route('client.contracts.show', md5($row->id)).'" target="_blank" class="btn btn-info btn-circle view-contact"
                      data-toggle="tooltip" data-contract-id="'.$row->id.'"  data-original-title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>';
            })
            ->editColumn('start_date', function($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('subject', function($row) {
                return '<a href="'.route('client.contracts.show', md5($row->id)).'">'.ucfirst($row->subject).'</a>';
            })
            ->editColumn('end_date', function($row) {
                return $row->end_date->format($this->global->date_format);
            })
            ->editColumn('amount', function($row) {
                return $this->global->currency->currency_symbol.''.$row->amount;
            })
            ->editColumn('signature', function($row) {
                if($row->signature) {
                    return 'signed';
                }
                return 'Not Signed';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'subject'])
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contract $contract)
    {
        $request = $this->request();

        $contract = Contract::with('contract_type', 'signature')->where('client_id', $this->user->id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $contract = $contract->where(DB::raw('DATE(contracts.`start_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $contract = $contract->where(DB::raw('DATE(contracts.`end_date`)'), '<=', $startDate);
        }

        if ($request->contractType != 'all' && !is_null($request->contractType)) {
            $contract = $contract->where('contracts.contract_type_id', '=', $request->contractType);
        }

        return $contract;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    
    public function html()
    {
        return $this->builder()
            ->setTableId('contract-table')
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
                   window.LaravelDataTables["contract-table"].buttons().container()
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
            __('app.subject') => ['data' => 'subject', 'name' => 'subject'],
            __('modules.contracts.contractType') => ['data' => 'contract_type.name', 'name' => 'contract_type.name'],
            __('app.amount') => ['data' => 'amount', 'name' => 'amount'],
            __('app.startDate') => ['data' => 'start_date', 'name' => 'start_date'],
            __('app.endDate') => ['data' => 'end_date', 'name' => 'end_date'],  
            __('modules.estimates.signature') => ['data' => 'signature', 'name' => 'signature', 'orderable' =>false, 'searchable' => false],
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
