<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ProductsDataTable extends BaseDataTable
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
                                <a href="' . route("admin.products.edit", $row->id) . '"><i class="fa fa-edit" aria-hidden="true"></i> ' . trans('app.edit') . '</a>
                                <a href="javascript:;"  data-user-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>
                            </div>
                        </div>
                    </div>
                </div>';                

                return $action;
            })

            
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->editColumn('allow_purchase', function ($row) {
                if ($row->allow_purchase == 1) {
                    return '<label class="badge btn-block badge-success">' . __('app.allowed') . '</label>';
                } else {
                    return '<label class="badge btn-block badge-danger">' . __('app.notAllowed') . '</label>';
                }
            })
            ->editColumn('price', function ($row) {
                if (!is_null($row->taxes)) {
                    $totalTax = 0;
                    foreach (json_decode($row->taxes) as $tax) {
                        $this->tax = Product::taxbyid($tax)->first();
                        $totalTax = $totalTax + ($row->price * ($this->tax->rate_percent / 100));
                    }
                    return $this->global->currency->currency_symbol . ($row->price + $totalTax);
                } else {
                    return $this->global->currency->currency_symbol . $row->price;
                }
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'price', 'allow_purchase']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->select('id', 'name', 'price', 'taxes', 'allow_purchase');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    /* public function html()
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend'=> 'export','buttons' => ['excel', 'csv']])
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["products-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
    } */

    public function html()
    {
        return $this->builder()
            ->setTableId('products-table')
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
                   window.LaravelDataTables["products-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                $(\'#products-table_wrapper select\').select2({minimumResultsForSearch: -1});
                    
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
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.name') => ['data' => 'name', 'name' => 'name'],
            __('app.price') . '(' . __('app.inclusiveAllTaxes') . ')' => ['data' => 'price', 'name' => 'price'],
            __('app.purchaseAllow') => ['data' => 'allow_purchase', 'name' => 'allow_purchase'],
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
        return 'Products_' . date('YmdHis');
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
