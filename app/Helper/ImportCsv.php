<?php


namespace App\Helper;


use App\ClientDetails;
use App\Country;
use App\Designation;
use App\EmployeeDetails;
use App\EmployeeSkill;
use App\Lead;
use App\LeadAgent;
use App\LeadSource;
use App\Notifications\NewUser;
use App\Role;
use App\Scopes\CompanyScope;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImportCsv
{
    public function csvToArray($filename = '',$section='')
    {
        $delimiter = ',';
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;

        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header){
                    if($section){
                        $header = $this->getHeaderKeys($section);
                    }else{
                        $header = $row;
                    }
                }else{
                    $count = min(count($header), count($row));
                    $data[] = array_combine(array_slice($header, 0, $count), array_slice($row, 0, $count));
                }

            }
            fclose($handle);
        }

        return $data;
    }

    public function importTo($section,$csv_file,$options){
        $data = $this->csvToArray($csv_file,$section);
        if(empty($data)){
            return false;
        }

        switch ($section){
            case ('clients'):{
                return $this->importClients($data,$options);
            }
            case ('leads'):{
                return $this->importLeads($data,$options);
            }
            case ('employees'):{
                return $this->importEmployees($data,$options);
            }
            default: return false;
        }
    }

    private function importClients($data,$options){
        $resp_array = [];
        foreach ($data as $key=>$user){
             if($this->saveClient($user,$options)){
                 //$resp_array[$key] = 'Added '.$client['email'];
             }else{
                 $resp_array[$key] = $user['email'];
             }
        }
        return $resp_array;
    }

    private function importLeads($data,$options){
        $resp_array = [];
        foreach ($data as $key=>$user){
            if($this->saveLead($user,$options)){
               // $resp_array[$key] = 'Added '.$user['email'];
            }else{
                $resp_array[$key] = $user['client_email'];
            }
        }
        return $resp_array;
    }

    private function importEmployees($data,$options){
        $resp_array = [];
        foreach ($data as $key=>$user){
            if($this->saveEmployees($user,$options)){
                // $resp_array[$key] = 'Added '.$user['email'];
            }else{
                $resp_array[$key] = $user['email'];
            }
        }
        return $resp_array;
    }

    private function saveClient($client_data,$options){
        $company = company();
        $validator = Validator::make($client_data, [
            "first_name" => "required",
            'last_name'  => 'required',
            "email" => "required|email",
            'website' => 'nullable',
            'country_id'=> 'required',
            'company_name' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);
        if ($validator->fails()) {
            return false;
        }
        $existing_user = User::withoutGlobalScope(CompanyScope::class)->withoutGlobalScopes(['active'])->select('id', 'company_id', 'email')->where('email', $client_data['email'])->first();
        // if no user found create new user with random password
        if (!$existing_user) {
            $password = str_random(8);
            // create new user
            $user = new User();
            $user->name = $client_data['first_name'].' '.$client_data['last_name'];
            $user->first_name = $client_data['first_name'];
            $user->last_name = $client_data['last_name'];
            $user->email = $client_data['email'];
            $user->password = Hash::make($password);
            $user->mobile = $client_data['mobile'];

            $user->save();

            // attach role
            $role = Role::where('name', 'client')->first();
            $user->attachRole($role->id);
        }elseif($existing_user->company_id != $company->id){
           return false;
        }

        $existing_client_count = ClientDetails::select('id', 'email', 'company_id')
            ->where(
                [
                    'email' => $client_data['email']
                ]
            )->count();

        if ($existing_client_count === 0) {
            $client = new ClientDetails();
            $client->user_id = $existing_user ? $existing_user->id : $user->id;
            $client->first_name = $client_data['first_name'];
            $client->last_name = $client_data['last_name'];
            $name = $client_data['first_name'].' '.$client_data['last_name'];
            $client->name = $name;
            $client->email = $client_data['email'];
            $client->mobile = $client_data['mobile'];
            $client->company_name = $client_data['company_name'];
            $client->street = $client_data['street'];
            $client->apt_floor  = $client_data['apt_floor'];
            $client->city  = $client_data['city'];
            $client->state  = $client_data['state'];
            $client->zip  = $client_data['zip'];
            $country_code = $client_data['country_id'];
            $country = Country::where('iso_alpha3',$country_code)->first();
            $country_name = '';
            if($country){
                $client->country_id  = $country->id;
                $country_name = $country->name;
            }
            $address = [$client_data['street'],$client_data['apt_floor'],$client_data['city'],$client_data['state'],$client_data['zip'],$country_name];
            $client->address = implode(', ',$address);
            $client->shipping_address = $client_data['shipping_address'];

            $client->website = $client_data['website'];
            $client->note = $client_data['note'];
            $client->save();

            DB::table('client_details')
                ->where('id', $client->id)
                ->update(['name' => $name,
                    'email' => $client_data['email']]);

            // attach role
            if ($existing_user) {
                   $role = Role::where('name', 'client')->where('company_id', $client->company_id)->first();
                   $existing_user->attachRole($role->id);
            }

            // log search
//            if (!is_null($client->company_name)) {
//                $user_id = $existing_user ? $existing_user->id : $user->id;
//                $this->logSearchEntry($user_id, $client->company_name, 'admin.clients.edit', 'client');
//            }
//            //log search
//            $this->logSearchEntry($client->id, $client_data['name'], 'admin.clients.edit', 'client');
//            $this->logSearchEntry($client->id, $client_data['email'], 'admin.clients.edit', 'client');
        } else {
            return false;
            //return Reply::error('Provided email is already registered. Try with different email.');
        }

        if (!$existing_user && $options['emailSetting'][0]->send_email == 'yes') {
            //send welcome email notification
            $user->notify(new NewUser($password));
        }

        return true;
    }

    private function saveLead($lead_data,$options){
        $validator = Validator::make($lead_data, [
            'company_name' => 'required',
            'client_first_name' => 'required',
            'client_last_name' => 'required',
            'client_email' => 'required|email|unique:leads',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return false;
        }
        $lead = new Lead();
        $lead->company_name = $lead_data['company_name'];
        $lead->website = $lead_data['website'];
        $lead->address = $lead_data['address'];
        $lead->client_first_name = $lead_data['client_first_name'];
        $lead->client_last_name = $lead_data['client_last_name'];
        $lead->client_name = $lead_data['client_first_name'].' '.$lead_data['client_last_name'];
        $lead->client_email = $lead_data['client_email'];
        $lead->mobile = $lead_data['mobile'];
        $lead->note = $lead_data['note'];
        $lead->next_follow_up = (strtolower($lead_data['next_follow_up']) == 'yes')?'yes':'no';
        if($lead_data['agent_email']){
            $agentData = LeadAgent::select('lead_agents.id', 'lead_agents.user_id', 'users.name')
                ->join('users','users.id', 'lead_agents.user_id')
                ->where('users.email',$lead_data['agent_email'])
                ->first();
            if($agentData){
                $lead->agent_id = $agentData->id;
            }else{
                // check employee exist
                $employeeData = User::doesntHave('lead_agent')
                    ->join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                    ->where('roles.name', 'employee')
                    ->where('users.email',$lead_data['agent_email'])
                    ->first();
                if($employeeData){
                    // add new agent
                    $agent = new LeadAgent();
                    $agent->user_id = $employeeData->id;
                    $agent->save();

                    $lead->agent_id = $agent->id;
                }
            }

        }
        if($lead_data['source_text']){
            //TODO find or add source and get id
            $leadSources = LeadSource::where('type',strtolower($lead_data['source_text']))->first();
            if($leadSources){
                $lead->source_id = $leadSources->id;
            }else{
                $source = new LeadSource();
                $source->type = strtolower($lead_data['source_text']);
                $source->save();
                $lead->source_id = $source->id;
            }

        }
        $lead->save();
        if($lead->id) {
           return true;
        }

        return false;
    }

    private function saveEmployees($employee_data,$options){
        $validator = Validator::make($employee_data, [
            'employee_id' => [
                'required',
                Rule::unique('employee_details')->where(function($query) use($employee_data) {
                    $query->where(['employee_id' => $employee_data['employee_id'], 'company_id' => company()->id]);
                })
            ],
            'first_name'  => 'required',
            "last_name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            'slack_username' => 'nullable|unique:employee_details,slack_username',
            'hourly_rate' => 'nullable|numeric',
            'joining_date' => 'required',
            'department' => 'required',
            'designation' => 'required',
        ]);
        if ($validator->fails()) {
            return false;
        }
        $company = company();

        if (!is_null($company->employees) && $company->employees->count() >= $company->package->max_employees) {
           // return Reply::error(__('messages.upgradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees]));
            return false;
        }

        if (!is_null($company->employees) && $company->package->max_employees < $company->employees->count()) {
          //  return Reply::error(__('messages.downGradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees]));
            return false;
        }
        DB::beginTransaction();
        try {
            $user = new User();
            $fullName = $employee_data['first_name'].' '.$employee_data['last_name'];
            $user->first_name = $employee_data['first_name'];
            $user->last_name = $employee_data['last_name'];
            $user->name = $fullName;
            $user->email = $employee_data['email'];
            $user->password = Hash::make($employee_data['password']);
            $user->mobile = $employee_data['mobile'];
            $user->gender = ($employee_data['gender']=='male' || $employee_data['gender']=='female')?$employee_data['gender']:'others';
            $user->login = 'disable';

            $user->save();

            /*$tags = $employee_data['tags'];
            //print_r($tags);
            if (!empty($tags)) {
                EmployeeSkill::where('user_id', $user->id)->delete();
                foreach ($tags as $tag) {
                    // Store user skills
                    $skill = new EmployeeSkill();
                    $skill->user_id = $user->id;
                    $skill->skill_id = $tag;
                    $skill->save();
                }
            }*/

            if ($user->id) {
                $employee = new EmployeeDetails();
                $employee->user_id = $user->id;
                $employee->employee_id = $employee_data['employee_id'];
                $employee->address = $employee_data['address'];
                $employee->hourly_rate = $employee_data['hourly_rate'];
                if($employee_data['slack_username']!=''){
                  $employee->slack_username = $employee_data['slack_username'];
                }

                $employee->joining_date = date('Y-m-d', strtotime($employee_data['joining_date']));

                if ($employee_data['last_date'] != '') {
                    $employee->last_date = date('Y-m-d', strtotime($employee_data['last_date']));
                        //Carbon::createFromFormat($options['global']->date_format, $employee_data['last_date'])->format('Y-m-d');
                }
                if($employee_data['department']){
                    $team = Team::where('team_name',$employee_data['department'])->first();
                    if(!$team){
                        $team = new Team();
                        $team->team_name = $employee_data['department'];
                        $team->save();
                    }
                    if($team){
                        $employee->department_id = $team->id;
                    }
                }
                if($employee_data['designation']){
                    $designation = Designation::where('name',$employee_data['designation'])->first();
                    if(!$designation){
                        $designation = new Designation();
                        $designation->name = $employee_data['designation'];
                        $designation->save();
                    }
                    if($designation){
                        $employee->designation_id = $designation->id;
                    }
                }

               $employee->save();

            }


            $role = Role::where('name', 'employee')->first();
            $resp = $user->attachRole($role->id);

            DB::commit();

            return true;

        } catch (\Swift_TransportException $e) {
            // Rollback Transaction
            DB::rollback();
            //return Reply::error('Please configure SMTP details to add employee. Visit Settings -> Email setting to set SMTP','smtp_error');
            return false;
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollback();
            //return Reply::error('Some error occured when inserting the data. Please try again or contact support');
            return false;
        }

        return false;
    }

    public function getExcelFields($section){
        $fields['employees'] = [
            'employee_id' => 'Employee ID',
            'first_name' => 'Employee First Name',
            'last_name' => 'Employee Last Name',
            'email' => 'Employee Email',
            'password' => 'Password',
            'slack_username' => 'Slack Username',
            'joining_date' => 'Joining Date',
            'last_date' => 'Last Date',
            'gender' => 'Gender',
            'designation' => 'Designation',
            'department' => 'Department',
            'mobile' => 'Mobile',
            'hourly_rate' => 'Hourly Rate',
            'address' => 'Address'
        ];
        $fields['leads'] = [
            'company_name' => 'Company Name',
            'client_email' => 'Client Email',
            'client_first_name' => 'First Name',
            'client_last_name' => 'Last Name',
            'website' => 'Website',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'note' => 'Note',
            'next_follow_up' => 'Next Follow Up',
            'agent_email' => "Lead Sponsor's(Agent) Email",
            'source_text' => 'Lead Source'
        ];
        $fields['clients'] = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'company_name' => 'Company Name',
            'street' => 'Street Address',
            'apt_floor' => 'Apt / Suite / Floor',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'country_id' => 'Country Code',
            'shipping_address' => 'Shipping Address',
            'website' => 'Website',
            'note' => 'Note'
        ];

        if(isset($fields[$section])){
            return $fields[$section];
        }
        return false;
    }

    public function getHeaderKeys($section){
        $fields = $this->getExcelFields($section);
        if(!empty($fields)){
            return array_flip($fields);
        }
        return false;
    }

}