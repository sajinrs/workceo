<?php

namespace App\DataTables\Admin;

use App\Vehicle;
use App\Users;
use App\DataTables\BaseDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class VehiclesDataTable extends BaseDataTable
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
                              <a href="' . route('admin.vehicles.edit', [$row->id]) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>
                              <a href="' . route('admin.vehicles.show', [$row->id]) . '"><img src="'.asset('img/icons/search.svg').'" /> ' . __('app.view') . '</a>
                              <a href="javascript:;"  data-vehicle-id="' . $row->id . '"  class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . trans('app.delete') . '</a>

                        </div>
                    </div>
                </div>';

                return $action;

            })
            ->editColumn(
                'vehicle_name',
                function ($row) {
                    $photo = $row->image_url;
                    if($photo) {
                        return '<a href="' . route('admin.vehicles.show', $row->id) . '"><img class="vehicle-image m-r-10" width="50" src="'.$photo.'" />' . ucfirst($row->vehicle_name) . '</a>';
                    } else {
                        return '<a href="' . route('admin.vehicles.show', $row->id) . '">' . ucfirst($row->vehicle_name) . '</a>';
                    }
                    
                }
            )
            ->editColumn(
                'name',
                function ($row) {
                    $name = $row->name;
                    if($name) {
                        return  ucfirst($name);
                    } else {
                        return 'Unassigned';
                    }
                    
                }
            )
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format('m/d/Y');
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    if($row->status == 'active'){
                        return '<label class="badge badge-success w-100">'.__('app.active').'</label>';
                    } else if($row->status == 'in_shop'){
                        return '<label class="badge badge-warning w-100">In Shop</label>';
                    } else if($row->status == 'out_of_service'){
                        return '<label class="badge badge-danger w-100">Out of Service</label>';
                    } else if($row->status == 'inactive'){
                        return '<label class="badge badge-primary w-100">Inactive</label>';
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['vehicle_name', 'name', 'action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Vehicle $model)
    {
        $request = $this->request();
        $model = $model->leftJoin('users', 'vehicles.operator_id', '=', 'users.id')
                       ->select('vehicles.*', 'users.name');
        
        /* if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(users.`created_at`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(users.`created_at`)'), '<=', $endDate);
        } */
        if ($request->status_by == 'notAssigned') {
            $model = $model->where('operator_id', NULL);
        }

        /* if ($request->client != 'all' && $request->client != '') {
            $model = $model->where('users.id', $request->client);
        } */

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
            ->setTableId('vehicles-table')
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
                   window.LaravelDataTables["vehicles-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    
                    $(\'#status, #vehicles-table_wrapper select\').select2({minimumResultsForSearch: -1});
                }',
            ])
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]))
                ->buttons(Button::make([
                    'text'=> '<span class="ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></span> Import',
                    'className'=>'importBtn importVehicleList'
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
            __('app.id') => ['data' => 'id', 'vehicle_name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('app.name') => ['data' => 'vehicle_name', 'name' => 'vehicle_name'],
            __('modules.vehicles.licensePlate') => ['data' => 'license_plate', 'name' => 'license_plate'],
            __('modules.vehicles.operator') => ['data' => 'name', 'name' => 'users.name'],
            __('modules.vehicles.year') => ['data' => 'year', 'name' => 'year'],
            __('modules.vehicles.make') => ['data' => 'make', 'name' => 'make'],
            __('modules.vehicles.model') => ['data' => 'model', 'name' => 'model'],
            __('app.status') => ['data' => 'status', 'name' => 'users.status'],
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
        return 'vehicles_' . date('YmdHis');
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
