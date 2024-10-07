<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ExpensesDataTable extends BaseDataTable
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
                                <a href="' . route("admin.expenses.edit", $row->id) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>
                                <a href="javascript:;"  data-expense-id="' . $row->id . '" class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . trans('app.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </div>';                

                return $action;
            })
            ->editColumn('price', function ($row) {
                if (!is_null($row->purchase_date)) {
                    return $row->total_amount;
                }
                return '-';
            })
            ->editColumn('user_id', function ($row) {
                return '<a href="' . route('admin.employees.show', $row->user_id) . '">' . ucwords($row->name) . '</a>';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'pending') {
                    return '<label class="badge badge-warning w-100">' . ucfirst($row->status) . '</label>';
                } else if ($row->status == 'approved') {
                    return '<label class="badge badge-success w-100">' . ucfirst($row->status) . '</label>';
                } else {
                    return '<label class="badge badge-danger w-100">' . ucfirst($row->status) . '</label>';
                }
            })
            ->editColumn(
                'purchase_date',
                function ($row) {
                    if (!is_null($row->purchase_date)) {
                        return $row->purchase_date->timezone($this->global->timezone)->format('m/d/Y');
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'user_id'])
            ->removeColumn('currency_id')
            ->removeColumn('name')
            ->removeColumn('currency_symbol')
            ->removeColumn('updated_at')
            ->removeColumn('created_at');

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Expense $model)
    {
        $request = $this->request();

        $model = $model->select('expenses.id', 'expenses.item_name', 'expenses.user_id', 'expenses.price', 'users.name', 'expenses.purchase_date', 'expenses.currency_id', 'currencies.currency_symbol', 'expenses.status', 'expenses.purchase_from')
            ->join('users', 'users.id', 'expenses.user_id')
            ->join('currencies', 'currencies.id', 'expenses.currency_id');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(expenses.`purchase_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(expenses.`purchase_date`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('expenses.status', '=', $request->status);
        }
        if ($request->employee != 'all' && !is_null($request->employee)) {
            $model = $model->where('expenses.user_id', '=', $request->employee);
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
            ->setTableId('expenses-table')
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
                   window.LaravelDataTables["expenses-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'#status, #expenses-table_wrapper select\').select2({minimumResultsForSearch: -1});
                }',
            ])
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv', 'pdf']]));
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
            __('modules.expenses.itemName')  => ['data' => 'item_name', 'name' => 'item_name'],
            __('app.price') => ['data' => 'price', 'name' => 'price'],
            __('modules.expenses.purchaseFrom') => ['data' => 'purchase_from', 'name' => 'purchase_from'],
            __('app.menu.employees') => ['data' => 'user_id', 'name' => 'user_id'],
            __('modules.expenses.purchaseDate') => ['data' => 'purchase_date', 'name' => 'purchase_date'],
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
        return 'Expenses_' . date('YmdHis');
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
