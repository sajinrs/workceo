<?php

namespace App\Http\Controllers\Admin;

use App\Project;
use App\ClientDetails;
use App\Lead;
use App\EmployeeDetails;
use App\User;
use App\Designation;
use App\UserExtra;
use App\Vehicle;
use Carbon\Carbon;
use App\Traits\CurrencyExchange;
use App\Helper\Reply;
use Illuminate\Http\Request;

class MapController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.map';
        $this->pageIcon = 'fas fa-map-marker-alt';
    }

    public function index()
    {
       return view('admin.map.index', $this->data);
    }

    public function filter(Request $request)
    {
        $tab = $request->input('tab'); 
        $this->clients = $this->projects = $this->leads = $this->employees = $this->vehicles = '' ;
        $jobsDataArray = [];
        $clientsDataArray = [];
        $leadsDataArray = [];
        $employeesDataArray = [];
        $vehiclesDataArray = [];
        $per_page = 10;
        $last_page = 1;

        if($tab == 'jobs'){
            $this->projects = Project::orderBy('start_date','DESC')->paginate($per_page);
            foreach ($this->projects as $job){
                if ($job->status == 'in progress') {
                    $status = '<label class="badge badge-info">' . __('app.inProgress') . '</label>';
                } else if ($job->status == 'on hold') {
                    $status = '<label class="badge badge-warning">' . __('app.onHold') . '</label>';
                } else if ($job->status == 'not started') {
                    $status = '<label class="badge badge-warning">' . __('app.notStarted') . '</label>';
                } else if ($job->status == 'canceled') {
                    $status = '<label class="badge badge-danger">' . __('app.canceled') . '</label>';
                } else if ($job->status == 'awaiting invoice') {
                    $status = '<label class="badge badge-yellow">' . __('app.awaitingInvoice') . '</label>';
                } else if ($job->status == 'awaiting pay') {
                    $status = '<label class="badge badge-cyan">' . __('app.awaitingPay') . '</label>';
                } else if ($job->status == 'paid') {
                    $status = '<label class="badge badge-ltgreen">' . __('app.paid') . '</label>';
                } else if ($job->status == 'finished') {
                    $status = '<label class="badge badge-dark">' . __('app.closed') . '</label>';
                }
                $jobsDataArray[] = ['id'=>$job->id,
                    'title'=>$job->project_name,
                    'client_name'=>$job->client->name,
                    'deadline'=>date('m/d/Y',strtotime($job->deadline)),
                    'status'=>$status,
                    'address'=>$job->client->address,
                    'url'=>route('admin.projects.show', [$job->id])
                ];
            }
            $last_page = $this->projects->lastPage();

        } elseif($tab == 'clients'){
            $this->clients = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
                ->where('users.status', '=', 'active')
                ->select('users.id', 'users.email', 'client_details.company_name', 'client_details.name', 'client_details.address')
                ->groupBy('client_details.id')->orderBy('id','DESC')->paginate($per_page);
            foreach ($this->clients as $client){
                $clientsDataArray[] = ['id'=>$client->id,'title'=>$client->company_name,
                    'name'=>$client->name,'address'=>$client->address, 'email'=>$client->email,
                    'url'=>route('admin.contacts.show', [$client->id])];
            }
            $last_page = $this->clients->lastPage();
        } elseif($tab == 'leads'){
            $this->leads = Lead::orderBy('id','DESC')->paginate($per_page);
            foreach ($this->leads as $lead){
                if ($lead->client_id != null && $lead->client_id != '') {
                    $label = '<label class="badge badge-success">' . __('app.client') . '</label>';
                } else {
                    $label = '<label class="badge badge-info">' . __('app.lead') . '</label>';
                }
                $leadsDataArray[] = ['id'=>$lead->id, 'title'=>$lead->company_name,
                    'name'=>$lead->client_first_name .' '.$lead->client_last_name,
                    'label'=>$label,
                    'address'=>$lead->address,'url'=>route('admin.leads.show', [$lead->id])];
            }
            $last_page = $this->leads->lastPage();
        }  elseif($tab == 'employees'){
            $this->employees = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.image')
                ->whereNotIn('roles.name', ['client','vehicle_operator'])->groupBy('users.id')->orderBy('id','DESC')->paginate($per_page);//EmployeeDetails::paginate($per_page);
            foreach ($this->employees as $employee){
                $empDetails = $employee->employeeDetail;
                $employeesDataArray[] = ['id'=>$employee->id,'name'=>$employee->name,
                    'address'=>$empDetails->address??'',
                    'email'=>$employee->email,
                    'designation'=>($employee->employeeDetail->designation->name)??'',
                    'department'=>($employee->employeeDetail->department->team_name)??'',
                    'url'=>route('admin.employees.show', [$employee->id])];
            }
            $last_page = $this->employees->lastPage();

        }elseif($tab == 'vehicles') {
            $this->vehicles = Vehicle::whereNotNull('operator_id')->orderBy('id', 'DESC')->get(); // ->paginate($per_page);
            foreach ($this->vehicles as $vehicle) {
                $location_array = null;
                $current_location = UserExtra::where('user_id',$vehicle->operator_id)->where('key_name', 'CURRENT_GPS_LOCATION')->first();
                if($current_location && $current_location->key_value != ''){
                    $location_array = json_decode($current_location->key_value);
                }

                $user = User::findOrFail($vehicle->operator_id);
                $vehiclesDataArray[] = [
                    'id' => $vehicle->id,
                    'name' => $vehicle->vehicle_name,
                    'model' => $vehicle->model,
                    'license_plate' => $vehicle->license_plate,
                    'operator' => $vehicle->operator->name,
                    'designation' => '', //($employee->employeeDetail->designation->name) ?? '',
                    'department' => '', //($employee->employeeDetail->department->team_name) ?? '',
                    'url' => route('admin.vehicles.show', [$vehicle->id]),
                    'current_location' => $location_array,
                    'operator'  => $user
                ];
            }

            $last_page = null; // $this->vehicles->lastPage();
        }
        
        $view = view('admin.map.ajaxresult', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view,
            'jobsData' => $jobsDataArray,
            'clientsData' => $clientsDataArray,
            'leadsData' => $leadsDataArray,
            'employeesData' => $employeesDataArray,
            'vehiclesData' => $vehiclesDataArray,
            'last_page' => $last_page
        ]);
    }


    public function showVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $location_array = null;
        $current_location = UserExtra::where('user_id',$vehicle->operator_id)->where('key_name', 'CURRENT_GPS_LOCATION')->first();
        if($current_location && $current_location->key_value != ''){
            $location_array = json_decode($current_location->key_value);
        }
        $vehicle['current_location'] = $location_array;
        $vehicle['image_url'] = $vehicle->image_url;
        $vehicle['operator'] = User::findOrFail($vehicle->operator_id);
        $vehicle['model'] =  $vehicle->model;
        $vehicle['id'] = $vehicle->id;
        $vehicle['url'] = route('admin.vehicles.show', [$vehicle->id]);
        return json_encode($vehicle);
    }
    
}
