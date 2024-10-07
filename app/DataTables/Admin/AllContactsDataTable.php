<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Lead;
use App\ProjectMember;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AllContactsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($collections)
    {
        return datatables()
            ->collection($collections)
            ->addColumn('action', function ($row) {
                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">';
                if ($row['user_type'] == 'admin' || $row['user_type'] == 'employee') {
                    $action .= '  <a href="' . route('admin.employees.show', [$row['id']]) . '"><img src="'.asset('img/icons/search.svg').'" /> ' . __('app.view') . '</a>
                    <a href="' . route('admin.employees.edit', [$row['id']]) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>';
                }elseif ($row['user_type'] == 'lead'){
                    $action .= '  <a href="' . route('admin.leads.show', [$row['id']]) . '"><img src="'.asset('img/icons/search.svg').'" /> ' . __('app.view') . '</a>
                    <a href="' . route('admin.leads.edit', $row['id']) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . __('modules.lead.edit') . '</a>';

                }
                elseif ($row['user_type'] == 'client'){
                    $action .= '  <a href="' . route('admin.clients.projects', [$row['id']]) . '"><img src="'.asset('img/icons/search.svg').'" /> ' . __('app.view') . '</a>';

                    if($row['client_id']) {
                    $action .= '<a href="' . route('admin.clients.edit', [$row['client_id']]) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>';
                    }

                }

                $action .= '          </div>
                        </div>
                    </div>
                </div>';

                return $action;


            })

            ->editColumn(
                'first_name',
                function ($row) {
                    $fname = $row['first_name'];
                    if($row['user_type'] == 'client'){
                        $fname = $row['cl_fname'];
                    }
                    return $fname;
                }
            )
            
            ->editColumn(
                'last_name',
                function ($row) {
                    $lastName = $row['last_name'];
                    if($row['user_type'] == 'client'){
                        $lastName = $row['cl_lname'];
                    }
                    return $lastName;
                }
            )

            ->editColumn('company_name', function ($row) {
                if ($row['user_type'] == 'admin' || $row['user_type'] == 'employee') {
                    $company = '<a href="' . route('admin.employees.show', [$row['id']]) . '">' . ucfirst($row['company_name']) . '</a>';
                } elseif ($row['user_type'] == 'lead'){
                    $company = '<a href="' . route('admin.leads.show', [$row['id']]) . '">' . ucfirst($row['company_name']) . '</a>';
                }elseif ($row['user_type'] == 'client'){
                    $company = '<a href="' . route('admin.clients.projects', [$row['id']]) . '">' . ucfirst($row['client_company']) . '</a>';
                } else {
                    $company = '<a href="' . route('admin.employees.show', [$row['id']]) . '">' . ucfirst($row['company_name']) . '</a>';
                }
            
                return $company;
            })
            /*->editColumn('company_name', function ($row) {
                if ($row['user_type'] == 'client') {
                    $company = '<a href="' . route('admin.employees.show', [$row['id']]) . '">' . ucfirst($row['client_company']) . '</a>';
                } else {
                    $company = '<a href="' . route('admin.employees.show', [$row['id']]) . '">' . ucfirst($row['company_name']) . '</a>';
                }
                

                return $company;
            })*/
            
            ->editColumn('created', function ($row) {
                if(isset($row['created_at'])){
                    return date('m/d/Y',strtotime($row['created_at']));//->format('m/d/Y');

                }else{
                    return '--';
                }
            })

            ->editColumn('type', function ($row) {

                if ($row['user_type'] == 'client') {
                    $type = '<label class="badge badge-success w-100">' . __('app.client') . '</label>';
                } else if ($row['user_type'] == 'employee') {
                    $type = '<label class="badge badge-orange w-100">' . __('app.employee') . '</label>';
                } else if ($row['user_type'] == 'admin') {
                    $type = '<label class="badge badge-danger w-100">' . __('app.admin') . '</label>';
                }  else if ($row['user_type'] == 'lead') {
                    $type = '<label class="badge badge-blue w-100">' . __('app.lead') . '</label>';
                }
                return $type;
            })

            ->addIndexColumn()
            ->rawColumns(['first_name', 'last_name', 'company_name', 'action', 'type'])
            ->removeColumn('roleId');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $request = $this->request();
        $roles_in = ['client','admin', 'employee'];
        $user_types = 'all';
        $users = [];
        $leads = [];
        if (!is_null($request->usertype))
        {
            if(in_array($request->usertype,['client','admin', 'employee'])){
                $roles_in = [$request->usertype];
                $user_types = 'users';
            }elseif ($request->usertype == 'lead'){
                $user_types = 'leads';
            }
        }

        $user_query = User::select('users.id', 'users.first_name', 'users.last_name',
            'users.email', 'users.mobile', 'users.created_at', 'users.status',
            'client_details.id as client_id',
            'client_details.first_name as cl_fname',
            'client_details.last_name as cl_lname',
            'client_details.email as cl_email',
            'client_details.mobile as cl_phone',
            'client_details.company_name as client_company',
            'roles.name as user_type', 'companies.company_name')
            ->leftJoin('client_details', 'client_details.user_id', '=', 'users.id')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('companies', 'companies.id', '=', 'users.company_id')
            ->whereIn('roles.name', $roles_in)
            //->groupBy('users.id')
            ->orderBy('users.id', 'DESC');

        $lead_query = Lead::select('leads.id', 'leads.client_name AS lead_name', 'leads.client_first_name AS first_name',
            'leads.client_last_name AS last_name', 'leads.client_email AS email',
            'leads.company_name', 'leads.mobile', 'leads.created_at')
            ->orderBy('leads.id', 'DESC');



        if (($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') && $request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $startDate = date('Y-m-d',strtotime($request->startDate));
            $endDate = date('Y-m-d',strtotime($request->endDate));
            $user_query->whereBetween(DB::raw('DATE(users.`created_at`)'), [$startDate,$endDate]);
            $lead_query->whereBetween(DB::raw('DATE(leads.`created_at`)'), [$startDate,$endDate]);
        }


        if($user_types == 'all' || $user_types == 'users'){
            $users = $user_query->get()->makeHidden(['unreadNotifications','modules','role'])->toArray();
        }
        if($user_types == 'all' || $user_types == 'leads') {
            $leads = $lead_query->get()->makeVisible(['created_at'])->toArray();
        }

        $collection = collect($leads)->merge($users)->sort(function($a, $b){
            return strtotime($b['created_at']) <= strtotime($a['created_at']);
        })->values()->map(function($n,$k){
            if(isset($n['lead_name'])){
                $n ['user_type']= 'lead';
            }
            $n ['arr_id'] = $k;
            return $n;
        });

        return collect($collection);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-10'Bl><'col-md-2'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->destroy(true)
            ->orderBy(0)
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
                   window.LaravelDataTables["employees-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(\'select\').select2({minimumResultsForSearch: -1});
                }',
            ])
            ->buttons(Button::make(['text'=> 'Filters<span class="ml-2"><i class="fa fa-angle-down"></i></span>','className'=>'filterBtn'])->action("$('.toggle-data').trigger('click');"))
            ->buttons(Button::make([
                'text'=> '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export', 
                'extend'=> 'export',
                'buttons' => ['excel', 'csv','pdf' ]
                ]))
//            ->buttons(Button::make(['text'=> '<span class="ml-2"><i class="fa fa-upload"></i></span> Import','className'=>'importBtn']))
            ;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.arr_id') => ['data' => 'arr_id', 'exportable' => false, 'printable' => false, 'name' => 'arr_id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('app.firstName') => ['data' => 'first_name', 'name' => 'first_name'],
            __('app.lasttName') => ['data' => 'last_name', 'name' => 'last_name'],
            __('app.company_name') => ['data' => 'company_name', 'name' => 'company_name'],
            __('app.email') => ['data' => 'email', 'name' => 'email'],
            __('app.phoneNumber') => ['data' => 'mobile', 'name' => 'mobile'],
            __('app.type') => ['data' => 'type', 'name' => 'type'],
            __('app.created') => ['data' => 'created', 'name' => 'created'],
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
        return 'employees_' . date('YmdHis');
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
