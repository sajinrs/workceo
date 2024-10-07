<?php

namespace App\DataTables\Admin;

use App\ClientDetails;
use App\ClientPropertie;
use App\DataTables\BaseDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PropertyReportDataTable extends BaseDataTable
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
            /* ->addColumn('action', function ($row) {

                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                              <a href="' . route('admin.clients.edit', [$row->id]) . '"><i class="fa fa-edit" aria-hidden="true"></i> ' . trans('app.edit') . '</a>
                              <a href="' . route('admin.clients.projects', [$row->user_id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . __('app.view') . '</a>
                              <a href="javascript:;"  data-user-id="' . $row->user_id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>

                        </div>
                    </div>
                </div>';

                return $action;

            }) */
            ->editColumn(
                'name',
                function ($row) {
                    return '<a href="' . route('admin.contacts.show', $row->user_id) . '">' . ucfirst($row->company_name) . '</a>';
                }
            )
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format('m/d/Y');
                }
            )
           
            ->addIndexColumn()
            ->rawColumns(['name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ClientPropertie $model)
    {
        $request = $this->request();
        
        $model = $model->join('client_details', 'client_details.user_id', '=', 'client_properties.user_id')
                        ->join('countries', 'countries.id', '=', 'client_properties.country_id')
                        ->select('client_properties.*', 'client_details.name', 'client_details.company_name', 'countries.name as country');
        
        if ($request->client != 'all' && $request->client != '') {
            $model = $model->where('client_properties.user_id', $request->client);
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
            ->setTableId('clients-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-9'Bl><'col-md-3'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
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
                   window.LaravelDataTables["clients-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                $(\'#clients-table_wrapper select\').select2({minimumResultsForSearch: -1});    
                    
                }',
            ])
            //->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make(['extend'=> 'export','buttons' => ['excel', 'csv','pdf' ]]));
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
            __('app.client_name') => ['data' => 'name', 'name' => 'name'],
            __('modules.client.streetAddress') => ['data' => 'street', 'name' => 'street'],
            __('modules.client.aptSuiteFloor') => ['data' => 'apt_floor', 'name' => 'apt_floor'],
            __('modules.client.city') => ['data' => 'city', 'name' => 'city'],
            __('modules.client.state') => ['data' => 'state', 'name' => 'state'],
            __('modules.client.zip') => ['data' => 'zip', 'name' => 'zip'], 
            __('modules.client.country') => ['data' => 'country', 'name' => 'country'],                
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'clients_' . date('YmdHis');
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
