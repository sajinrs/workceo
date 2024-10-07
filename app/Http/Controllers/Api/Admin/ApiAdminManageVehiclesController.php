<?php

namespace App\Http\Controllers\Api\Admin;

use App\DataTables\Admin\VehiclesDataTable;
use App\DataTables\Admin\OperatorsDataTable;
use App\Helper\Files;
use App\Helper\ImportCsv;
use App\Helper\Reply;
use App\Http\Requests\Admin\Vehicle\StoreRequest;
use App\Http\Requests\Admin\Vehicle\UpdateRequest;
use App\Http\Requests\Admin\Vehicle\OperatorStoreRequest;
use App\Http\Resources\ApiResource;
use App\Role;
use App\RoleUser;
use App\Vehicle;
use App\VehicleDocument;
use App\UniversalSearch;
use App\User;
use App\Project;
use App\VehicleActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ApiAdminManageVehiclesController extends ApiAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.vehicles';
        $this->pageIcon = 'fa fa-car';
        $this->middleware(function ($request, $next) {
            if (!in_array('employees', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        return ApiResource::collection(Vehicle::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->operators = User::allOperators();
        return view('admin.vehicles.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_name'  => 'required',
            "license_plate" => "required|unique:vehicles,license_plate",
            "year" => "required",
            'make' => 'required',
            'model' => 'required',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $company = company();

        $vehicle = new Vehicle();
        $vehicle->operator_id = $request->operator_id;
        $vehicle->vehicle_name = $request->vehicle_name;
        $vehicle->license_plate = $request->license_plate;
        $vehicle->year = $request->year;
        $vehicle->make = $request->make;
        $vehicle->model = $request->model;
        $vehicle->status = $request->status;

        if ($request->hasFile('image')) {
            /* $crop = [
                'width' => $request->input('width'),
                'height'=> $request->input('height'),
                'x'     => $request->input('x'),
                'y'     => $request->input('y')
            ];
            $vehicle->photo = Files::upload($request->image, 'vehicle',300, false, $crop); */
            $vehicle->photo = Files::upload($request->image, 'vehicle', 300);
        }

        $vehicle->save();

        //Vehicle Log
        if($request->operator_id)
        {
            $user = User::findOrFail($request->operator_id);
            $activity = 'Vehicle assigned to '.$user->name;
            $this->logVehicleActivity($vehicle->id, $activity);
        }

        $result = Vehicle::with('operator')->findOrFail($vehicle->id);
        $response = [
            'success' => 1,
            "message" => __('messages.vehicleAdded'),
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.vehicleAdded')]);
        //return Reply::dataOnly(['vehicleID' => $vehicle->id]);        
    }

    public function storeDocuments(Request $request)
    {
        if ($request->hasFile('file')) {
            foreach ($request->file as $fileData){
                $file = new VehicleDocument();
                $file->user_id = $this->user->id;
                $file->vehicle_id = $request->vehicle_id;
                $file->type = $request->type;

                $filename = Files::uploadLocalOrS3($fileData,'vehicle-documents/'.$request->vehicle_id);

                $file->file_name = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size = $fileData->getSize();
                $file->save();
            }

            if($request->type == 'document')
                $this->logVehicleActivity($request->vehicle_id, 'Documents Updated');
            else
                $this->logVehicleActivity($request->vehicle_id, 'Photos Updated');

        }

        $result = VehicleDocument::where('vehicle_id', $request->vehicle_id)->get();

        $response = [
            'success' => 1,
            "message" => 'Documents updated',
            'data'    => $result
        ];

        return new ApiResource($response);
        //return response()->json([ 'success' => 1, "message" => 'Documents updated']);
        //return Reply::dataOnly(['status' => 'true', 'type' => $request->type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        $this->vehicle = Vehicle::findOrFail($id);
        if($this->vehicle->operator_id)
        {
            $this->user = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.name', 'roles.name as roleName')
                        ->findOrFail($this->vehicle->operator_id);
        }
        $this->documents = VehicleDocument::where(['vehicle_id' => $id, 'type' => 'document'])->get();
        $this->photos = VehicleDocument::where(['vehicle_id' => $id, 'type' => 'photo'])->get();
        $this->activities = VehicleActivity::where('vehicle_id', $id)->get();
        $this->projects   = Project::join('project_vehicles', 'project_vehicles.project_id', '=', 'projects.id')
                                    ->select('projects.project_name', 'projects.id')
                                    ->where('vehicle_id', $id)->get();

        return view('admin.vehicles.show', $this->data);
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Vehicle::with('operator')->findOrFail($id);
//        $data['operators'] = User::allOperators();
//        $data['employees'] = User::allEmployees();
//
//        if($data['vehicle']->operator_id)
//        {
//            $data['user'] = User::join('role_user', 'role_user.user_id', '=', 'users.id')
//                            ->join('roles', 'roles.id', '=', 'role_user.role_id')
//                            ->select('roles.name as roleName')
//                            ->findOrFail($data['vehicle']->operator_id);
//        } else {
//            $data['user']['roleName'] = 'vehicle_operator';
//        }
        
        return new ApiResource($data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_name'  => 'required',
            "license_plate" => "required",
            "year" => "required",
            'make' => 'required',
            'model' => 'required',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $vehicle = Vehicle::findOrFail($id);

        //Vehicle Log
        if($request->operator_id != '' && $vehicle->operator_id != $request->operator_id)
        {
            $user = User::findOrFail($request->operator_id);
            $activity = 'Vehicle assigned to '.$user->name;
            $this->logVehicleActivity($id, $activity);
        }

        $vehicle->operator_id = $request->operator_id;
        $vehicle->vehicle_name = $request->vehicle_name;
        $vehicle->license_plate = $request->license_plate;
        $vehicle->year = $request->year;
        $vehicle->make = $request->make;
        $vehicle->model = $request->model;
        $vehicle->status = $request->status;

        if ($request->hasFile('image')) {
            /* $crop = [
                'width' => $request->input('width'),
                'height'=> $request->input('height'),
                'x'     => $request->input('x'),
                'y'     => $request->input('y')
            ]; */

            $vehicle->photo = Files::upload($request->image, 'vehicle', 300);
            //$vehicle->photo = Files::upload($request->image, 'vehicle',300, false, $crop);
        }

        $vehicle->save();

        $result = Vehicle::with('operator')->findOrFail($id);
        $response = [
            'success' => 1,
            "message" => 'Vehicle updated',
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => 'Vehicle updated']);
        //return Reply::dataOnly(['vehicleID' => $vehicle->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {       
        $documents = VehicleDocument::where('vehicle_id', $id)->get();

        if($documents)
        {
            foreach ($documents as $file) {
                Files::deleteFile($file->hashname, 'vehicle-documents/' . $file->vehicle_id);
                $file->delete();
            }
        }        

        Vehicle::destroy($id);        
        $response = [
            'success' => 1,
            "message" => __('messages.vehicleDeletedSuccessfully'),
        ];

        return new ApiResource($response);
        //return response()->json([ 'success' => 1, "message" => __('messages.vehicleDeletedSuccessfully')]);
    }

    

    public function createOperator(OperatorsDataTable $dataTable)
    {
        $this->operators = User::allOperators();
        return $dataTable->render('admin.vehicles.create-operator', $this->data);
        //return view('admin.vehicles.create-operator', $this->data);
        
    }

    public function storeOperator(OperatorStoreRequest $request)
    {

        $user = new User();
        $fullName = $request->input('first_name').' '.$request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->name = $fullName;
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->mobile = $request->input('mobile');
        $user->gender = $request->input('gender');
        $user->login = 'enable';

        if ($request->hasFile('image')) {
            $crop = [
                'width' => $request->input('width'),
                'height'=> $request->input('height'),
                'x'     => $request->input('x'),
                'y'     => $request->input('y')
            ];
            $user->image = Files::upload($request->image, 'avatar',300, false, $crop);
        }

        $user->save();

        $role = Role::where('name', 'vehicle_operator')->first();
        if($role) {
            $roleID = $role->id;
        } else {
            $operator = new Role();
            $operator->company_id = $this->company->id;
            $operator->name = 'vehicle_operator';
            $operator->display_name = 'Vehicle Operator'; // optional
            $operator->description = 'Vehicle Operator description'; // optional
            $operator->save();

            $roleID = $operator->id;
        }

        $user->attachRole($roleID);
        DB::commit();
        
        $operatorData = User::allOperators();
        return Reply::successWithData(__('messages.operatorDeleted'),['data' => $operatorData]);
    }

    public function destroyOperator(Request $request, $id)
    {
        User::destroy($id);
        $operatorData = User::allOperators();
        return Reply::successWithData(__('messages.operatorDeleted'),['data' => $operatorData]);
    }

    public function getOperators(Request $request)
    {
        if($request->user_type == 'employee')
            $operators = User::allEmployees();
        else
            $operators = User::allOperators();

        return json_encode($operators);
    }

    public function editOperator(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return json_encode($user);
    }

    public function updateOperator(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $fullName = $request->input('first_name').' '.$request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->name = $fullName;
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');
        $user->gender = $request->input('gender');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        $operatorData = User::allOperators();
        return Reply::successWithData(__('messages.operatorUpdated'),['data' => $operatorData]);
    }

    public function downloadDocument($id) {
        $file = vehicleDocument::findOrFail($id);

        return response()->download('user-uploads/vehicle-documents/'.$file->vehicle_id.'/'.$file->hashname, $file->file_name);
    }

    public function destroyDocument($id)
    {
        $file = vehicleDocument::findOrFail($id);

        Files::deleteFile($file->hashname,'vehicle-documents/'.$file->vehicle_id);

        vehicleDocument::destroy($id);        
        return Reply::success(__('messages.fileDeleted'));        

        //return Reply::successWithData(__('messages.fileDeleted'));
    }

    

    public function export($status, $employee, $role)
    {
        if ($role != 'all' && $role != '') {
            $userRoles = Role::findOrFail($role);
        }
        $rows = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->withoutGlobalScope('active')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '<>', 'client')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')

            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'designations.name as designation_name',
                'employee_details.address',
                'employee_details.hourly_rate',
                'users.created_at',
                'roles.name as roleName'
            );
        if ($status != 'all' && $status != '') {
            $rows = $rows->where('users.status', $status);
        }

        if ($employee != 'all' && $employee != '') {
            $rows = $rows->where('users.id', $employee);
        }

        if ($role != 'all' && $role != '' && $userRoles) {
            if ($userRoles->name == 'admin') {
                $rows = $rows->where('roles.id', $role);
            } elseif ($userRoles->name == 'employee') {
                $rows =  $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role)
                    ->having('roleName', '<>', 'admin');
            } else {
                $rows = $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role);
            }
        }
        $attributes =  ['roleName'];
        $rows = $rows->groupBy('users.id')->get()->makeHidden($attributes);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Email', 'Mobile', 'Designation', 'Address', 'Hourly Rate', 'Created at', 'Role'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = [
                "id" => $row->id,
                "name" => $row->name,
                "email" => $row->email,
                "mobile" => $row->mobile,
                "Designation" => $row->designation_name,
                "address" => $row->address,
                "hourly_rate" => $row->hourly_rate,
                "created_at" => $row->created_at->format('Y-m-d h:i:s a'),
                "roleName" => $row->roleName
            ];
        }

        // Generate and return the spreadsheet
        Excel::create('Employees', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Employees');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Employees file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });
            });
        })->download('xlsx');
    }
    
    public function import(Request $request){
        $validator = Validator::make($request->all(), [
            "file" => "required|mimes:csv,txt"
        ]);
        if ($validator->fails()) {
            return Reply::error('Invalid File. Upload file in csv format');
        }
        $file_path = $request->file('file')->getRealPath();
        $importCsv = new ImportCsv();
        $options['emailSetting'] = $this->emailSetting;
        $options['global'] = $this->global;
        $response = $importCsv->importTo('employees',$file_path,$options);
        $msg = '';
        if(!empty($response)){
            $msg = implode(', ',$response);
            return Reply::error('Failed to import following users '.$msg);
        }
        return Reply::success('List imported successfuly!');
    }
    public function downloadCSVTemplate(){
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=employees_list.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $importCsv = new ImportCsv();
        $columns = $importCsv->getExcelFields('employees');

        $callback = function() use ($columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // sample data
            // foreach($reviews as $review) {
            // fputcsv($file, array($review->reviewID, $review->provider, $review->title, $review->review, $review->location, $review->review_created, $review->anon, $review->escalate, $review->rating, $review->name));
            // }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

}
