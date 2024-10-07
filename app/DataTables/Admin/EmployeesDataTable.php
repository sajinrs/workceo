<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\ProjectMember;
use App\Role;
use App\User;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class EmployeesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $roles = Role::where('name', '<>', 'client')->get();
        return datatables()
            ->eloquent($query)
            ->addColumn('role', function ($row) use ($roles) {
                $roleRow = '';
                if ($row->id != 1) {

                    $flag = 0;
                    foreach ($roles as $role) {

                        if($role->name != 'vehicle_operator')
                        {
                            $roleRow .= '<div class="radio radio-info">
                              <input type="radio" name="role_' . $row->id . '" class="assign_role" data-user-id="' . $row->id . '"';

                            foreach ($row->role as $urole) {

                                if ($role->id == $urole->role_id && $flag == 0) {
                                    $roleRow .= ' checked ';

                                    if ($role->name == 'admin') {
                                        $flag = 1; //do not check any other role for user if is admin
                                    }
                                }
                            }

                            if ($role->id <= 3) {
                                $roleRow .= 'id="none_role_' . $row->id . $role->id . '" data-role-id="' . $role->id . '" value="' . $role->id . '"> <label for="none_role_' . $row->id . $role->id . '" data-role-id="' . $role->id . '" data-user-id="' . $row->id . '">' . __('app.' . $role->name) . '</label></div>';
                            } else {
                                $roleRow .= 'id="none_role_' . $row->id . $role->id . '" data-role-id="' . $role->id . '" value="' . $role->id . '"> <label for="none_role_' . $row->id . $role->id . '" data-role-id="' . $role->id . '" data-user-id="' . $row->id . '">' . ucwords($role->name) . '</label></div>';
                            }

                            //$roleRow .= '<br>';
                            }
                        
                    }
                    if($this->user->id == $row->id){
                        return 'Admin';
                    } else {
                        return $roleRow;
                    }
                } else {
                    return __('messages.roleCannotChange');
                }
            })
            ->addColumn('action', function ($row) {

                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <a href="' . route('admin.employees.edit', [$row->id]) . '"><img src="'.asset('img/icons/edit.svg').'" /> ' . trans('app.edit') . '</a>
                                <a href="' . route('admin.employees.show', [$row->id]) . '"><img src="'.asset('img/icons/search.svg').'" /> ' . __('app.view') . '</a>';
                                if($this->user->id !== $row->id){
                                    $action .= '<a href="javascript:;"  data-user-id="' . $row->id . '"  class="sa-params"><img src="'.asset('img/icons/delete.svg').'" /> ' . trans('app.delete') . '</a>';
                                }
                            '</div>
                        </div>
                    </div>
                </div>';

                return $action;


            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    if ($row->status == 'active') {
                        return '<label class="badge badge-success">' . __('app.active') . '</label>';
                    } else {
                        return '<label class="badge badge-danger">' . __('app.inactive') . '</label>';
                    }
                }
            )
            ->editColumn('name', function ($row) use ($roles) {

                $image = '<div class="emp-image"><img src="' . $row->image_url . '"alt="user" class="img-circle rounded-circle" width="35" height="35"></div> ';

                $designation = ($row->designation_name) ? ucwords($row->designation_name) : ' ';

                return  $image.'<div class="emp-info"><a href="' . route('admin.employees.show', $row->id) . '">' . ucwords($row->name) . '</a><br><span class="text-muted font-12">' . $designation . '</span></div>';
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'action', 'role', 'status'])
            ->removeColumn('roleId')
            ->removeColumn('roleName')
            ->removeColumn('current_role');
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
        if ($request->role != 'all' && $request->role != '') {
            $userRoles = Role::findOrFail($request->role);
        }

        $users = User::with('role')
            ->withoutGlobalScope('active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'roles.name as roleName', 'roles.id as roleId', 'users.image', 'users.status', \DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1) as `current_role`"), 'designations.name as designation_name')
            ->where('roles.name', '<>', 'client')
            ->where('roles.name', '<>', 'vehicle_operator');

        if ($request->status != 'all' && $request->status != '') {
            $users = $users->where('users.status', $request->status);
        }

        if ($request->employee != 'all' && $request->employee != '') {
            $users = $users->where('users.id', $request->employee);
        }

        if ($request->designation != 'all' && $request->designation != '') {
            $users = $users->where('employee_details.designation_id', $request->designation);
        }

        if ($request->department != 'all' && $request->department != '') {
            $users = $users->where('employee_details.department_id', $request->department);
        }
        
        if ($request->role != 'all' && $request->role != '' && $userRoles) {
            if ($userRoles->name == 'admin') {
                $users = $users->where('roles.id', $request->role);
            } elseif ($userRoles->name == 'employee') {
                $users =  $users->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $request->role)
                    ->having('roleName', '<>', 'admin');
            } else {
                $users = $users->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $request->role);
            }
        }
        
        if ((is_array($request->skill) && $request->skill[0] != 'all') && $request->skill != '' && $request->skill != null && $request->skill != 'null') {
            $users =  $users->join('employee_skills', 'employee_skills.user_id', '=', 'users.id')
                ->whereIn('employee_skills.skill_id', $request->skill);
        }

        if (!is_null($request->status_by) && $request->status_by != 'totalEmployees') {
            switch ($request->status_by){
                case('freeEmployees'):{

                    $users =  $users->whereNotIn('users.id', function ($query) {
                        $query->select('user_id as id')->from('project_members')
                            ->join('projects', 'projects.id', '=', 'project_members.project_id')
                            ->join('users', 'users.id', '=', 'project_members.user_id')
                            ->groupBy('project_members.user_id')
                            ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100");
                    });

                    break;
                }               
            }
        }

        if (!is_null($request->status_by) && $request->status_by == 'adminUser') {
            $users = $users->where('roles.name', 'admin');
        }

        if (!is_null($request->status_by) && $request->status_by == 'vehicleAssignedEmp') {
            $users = $users->join('vehicles', 'vehicles.operator_id', '=', 'users.id');
        }

        return $users->groupBy('users.id');
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
                    $(\'#status, #role, #employees-table_wrapper select\').select2({minimumResultsForSearch: -1});
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
                    'className'=>'importBtn importEmployeesList'
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('app.name') => ['data' => 'name', 'name' => 'name'],
            __('app.email') => ['data' => 'email', 'name' => 'email'],
            __('app.role') => ['data' => 'role', 'name' => 'role', 'width' => '20%'],
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
