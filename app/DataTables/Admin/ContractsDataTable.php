<?php

namespace App\DataTables\Admin;

use App\Contract;
use App\DataTables\BaseDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ContractsDataTable extends BaseDataTable
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
                                <a href="' . route('admin.contracts.show', md5($row->id)) . '" class="view-contact" data-contract-id="' . $row->id . '"><img src="'.asset('img/icons/search.svg').'" /> ' . trans('app.view') . '</a>
                                <a href="' . route('admin.contracts.edit', [$row->id]) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>
                                <a href="' . route('admin.contracts.copy', [$row->id]) . '" data-contract-id="' . $row->id . '"><img src="'.asset('img/icons/copy.svg').'" /> ' . __('app.copy') . '</a>
                                <a href="javascript:;"   data-contract-id="' . $row->id . '"  class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . trans('app.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </div>';

                

                return $action;
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format('m/d/Y');

            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date->format('m/d/Y');
            })
            ->editColumn('amount', function ($row) {
                return $this->global->currency->currency_symbol . '' . currencyFormat($row->amount);
            })
            ->editColumn('client.name', function ($row) {
                return '<a href="' . route('admin.clients.projects', $row->client_id) . '">' . ucfirst($row->clientdetails->company_name) . '</a>';
            })
            ->editColumn('signature', function ($row) {
                if ($row->signature) {
                    return 'signed';
                }
                return 'Not Signed';
            })
            ->addIndexColumn()
            ->rawColumns(['action','signature','status', 'client.name']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contract $model)
    {
        $request = $this->request();

        $model = $model->with('contract_type', 'client', 'signature', 'clientdetails');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(contracts.`start_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(contracts.`end_date`)'), '<=', $endDate);
        }

        if ($request->clientID != 'all' && !is_null($request->clientID)) {
            $model = $model->where('contracts.client_id', '=', $request->clientID);
        }

        if ($request->contractType != 'all' && !is_null($request->contractType)) {
            $model = $model->where('contracts.contract_type_id', '=', $request->contractType);
        }

        if (!is_null($request->status_by) && $request->status_by != 'contractCounts') {
            switch ($request->status_by){
                case('expiredCounts'):{
                    $model = $model->where(DB::raw('DATE(contracts.`end_date`)'), '<', Carbon::now()->format('Y-m-d'));
                    break;
                }
                case('aboutToExpire'):{
                    $model = $model->where(DB::raw('DATE(contracts.`end_date`)'), '>', Carbon::now()->format('Y-m-d'));
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
            ->setTableId('contracts-table')
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
                   window.LaravelDataTables["contracts-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'#status, #contracts-table_wrapper select\').select2({minimumResultsForSearch: -1});
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
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            __('app.contactTitle') => ['data' => 'subject', 'name' => 'subject'],
            __('app.client')  => ['data' => 'client.name', 'name' => 'client.name'],
            __('app.amount') => ['data' => 'amount', 'name' => 'amount'],
            __('app.startDate') => ['data' => 'start_date', 'name' => 'start_date'],
            __('app.endDate') => ['data' => 'end_date', 'name' => 'end_date'],
            __('app.signature') => ['data' => 'signature', 'name' => 'signature'],
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
        return 'Contracts_' . date('YmdHis');
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
