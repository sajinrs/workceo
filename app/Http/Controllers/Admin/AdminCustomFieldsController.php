<?php

namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Requests\CustomField\StoreCustomField;
use App\Http\Requests\CustomField\UpdateCustomField;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminCustomFieldsController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.customFields';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.custom-fields.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->customFieldGroups = DB::table('custom_field_groups')->where('company_id', company()->id)->get()->pluck('name', 'id');
        return view('admin.custom-fields.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomField $request)
    {
        if($request->module == 1){
            $model = new ClientDetails();
        }
        elseif($request->module == 2){
            $model = new EmployeeDetails();
        }
        else{
            $model = new Project();
        }

        $group = [
            'fields' => [
                [
                    'name'     => $request->get('name'),
                    'groupID'  => $request->module,
                    'label'    => $request->get('label'),
                    'type'     => $request->get('type'),
                    'required' => $request->get('required'),
                    'values'   => $request->get('value'),
                ]
            ],

        ];
        $model->addCustomField($group);
        return Reply::success('messages.customFieldCreateSuccess');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->custom_field = DB::table('custom_fields')->find($id);
        return view('admin.custom-fields.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomField $request, $id)
    {
        DB::table('custom_fields')
                ->where('id', $id)
                ->update([
                    'label'    => $request->get('label'),
                    'type'     => $request->get('type'),
                    'required' => $request->get('required')
                ]);
        return Reply::success(__('messages.customFieldUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('custom_fields')->delete($id);
        return Reply::success('messages.deleteSuccess');
    }

    public function getFields()
    {
        $permissions = DB::table('custom_fields')
            ->join('custom_field_groups', 'custom_field_groups.id', '=', 'custom_fields.custom_field_group_id')
            ->select('custom_fields.id', 'custom_field_groups.name as module', 'custom_fields.label', 'custom_fields.name', 'custom_fields.type', 'custom_fields.values', 'custom_fields.required')->where('custom_field_groups.company_id', company()->id);
        $data = DataTables::of($permissions)
            ->editColumn(
                'values',
                function ($row) {
                    $ul = '<ul>';

                    if (isset($row->values)) {
                        foreach (json_decode($row->values) as $key => $value) {
                            $ul .= '<li>' . $value . '</li>';
                        }

                    }

                    $ul .= '</ul>';

                    return $ul;
                }
            )
            ->editColumn(
                'required',
                function ($row) {
                    // Edit Button
                    $string = ' - ';
                    $class  = 'badge bg-red badge-danger disabled color-palette';

                    if ($row->required === 'yes') {
                        $string = '<span class="' . $class . '">' . $row->required . '</span>';
                    }

                    return $string;
                }
            )
            ->addColumn(
                'action',
                function ($row) {
                    return ' <a href="javascript:;" class="btn btn-outline-danger  btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><span class="icon-trash"></span></a>
                      <a id="edit-field" class="btn btn-outline-info  btn-circle" data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Edit"><span class="icon-pencil"></span></a>';
                }
            )
            ->rawColumns(['values', 'action', 'required'])
            ->make(true);
        return $data;
    }

}
