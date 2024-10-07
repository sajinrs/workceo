<?php

namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\Country;
use App\DataTables\Admin\AllContactsDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientRequest;
use App\Http\Requests\Gdpr\SaveConsentUserDataRequest;
use App\Invoice;
use App\Lead;
use App\Payment;
use App\Estimate;
use App\Notifications\NewUser;
use App\PurposeConsent;
use App\PurposeConsentUser;
use App\Role;
use App\Scopes\CompanyScope;
use App\UniversalSearch;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ContactsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.contacts';
        $this->pageIcon = 'icofont icofont-people';
        $this->middleware(function ($request, $next) {
            if (!in_array('clients', $this->user->modules)) {
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
    public function index(AllContactsDataTable $dataTable)
    {

       $this->totalLeads = Lead::count();
       $this->totalAdmins = User::join('role_user', 'role_user.user_id', '=', 'users.id')
           ->join('roles', 'roles.id', '=', 'role_user.role_id')
           ->whereIn('roles.name', ['admin'])
           ->count();
        $this->totalClients = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->whereIn('roles.name', ['client'])
            ->count();
        $this->totalEmployees = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->whereIn('roles.name', ['employee'])
            ->count();
       
        return $dataTable->render('admin.contacts.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadID = null)
    {
        if ($leadID) {
            $this->leadDetail = Lead::findOrFail($leadID);
        }
        $this->countries = Country::all(['id', 'name']);

        $client = new ClientDetails();
        $this->fields = $client->getCustomFieldGroupsWithFields()->fields;
        return view('admin.clients.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $existing_user = User::withoutGlobalScope(CompanyScope::class)->select('id', 'email')->where('email', $request->input('email'))->first();

        // if no user found create new user with random password
        if (!$existing_user) {
            $password = str_random(8);
            // create new user
            $user = new User();
            $user->name = $request->input('name').' '.$request->input('last_name');
            $user->email = $request->input('email');
            $user->password = Hash::make($password);
            $user->mobile = $request->input('mobile');

            $user->save();

            // attach role
            $role = Role::where('name', 'client')->first();
            $user->attachRole($role->id);

            if ($request->has('lead')) {
                $lead = Lead::findOrFail($request->lead);
                $lead->client_id = $user->id;
                $lead->save();

                //return Reply::redirect(route('admin.leads.index'), __('messages.leadClientChangeSuccess'));
            }
        }

        $existing_client_count = ClientDetails::select('id', 'email', 'company_id')
            ->where(
                [
                    'email' => $request->input('email')
                ]
            )->count();

        if ($existing_client_count === 0) {
            $client = new ClientDetails();
            $client->user_id = $existing_user ? $existing_user->id : $user->id;
            $client->first_name = $request->input('name');
            $client->last_name = $request->input('last_name');
            $name = $request->input('name').' '.$request->input('last_name');
            $client->name = $name;
            $client->email = $request->input('email');
            $client->mobile = $request->input('mobile');
            $client->company_name = $request->company_name;
            $client->street = $request->street;
            $client->apt_floor  = $request->apt_floor;
            $client->city  = $request->city;
            $client->state  = $request->state;
            $client->zip  = $request->zip;
            $client->country_id  = $request->country_id;
            $country = Country::find($request->country_id);
            $address = [$request->street,$request->apt_floor,$request->city,$request->state,$request->zip,$country->name];
            $client->address = implode(', ',$address);
            $client->shipping_address = $request->shipping_address;

            $client->website = $request->website;
            $client->note = $request->note;
            $client->skype = $request->skype;
            $client->facebook = $request->facebook;
            $client->twitter = $request->twitter;
            $client->linkedin = $request->linkedin;
            $client->gst_number = $request->gst_number;
            $client->save();

            DB::table('client_details')
                ->where('id', $client->id)
                ->update(['name' => $name,
                    'email' => $request->input('email')]);

            // attach role
            if ($existing_user) {
                $role = Role::where('name', 'client')->where('company_id', $client->company_id)->first();
                $existing_user->attachRole($role->id);
            }

            // To add custom fields data
            if ($request->get('custom_fields_data')) {
                $client->updateCustomFieldData($request->get('custom_fields_data'));
            }

            // log search
            if (!is_null($client->company_name)) {
                $user_id = $existing_user ? $existing_user->id : $user->id;
                $this->logSearchEntry($user_id, $client->company_name, 'admin.clients.edit', 'client');
            }
            //log search
            $this->logSearchEntry($client->id, $request->name, 'admin.clients.edit', 'client');
            $this->logSearchEntry($client->id, $request->email, 'admin.clients.edit', 'client');
        } else {
            return Reply::error('Provided email is already registered. Try with different email.');
        }

        if (!$existing_user && $this->emailSetting[0]->send_email == 'yes' && $request->sendMail == 'yes') {
            //send welcome email notification
            $user->notify(new NewUser($password));
        }

        return Reply::redirect(route('admin.clients.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        return view('admin.clients.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->countries = Country::all(['id', 'name']);
        $this->userDetail = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->where('client_details.id', $id)
            ->select('client_details.id', 'client_details.name', 'client_details.email', 'client_details.user_id', 'client_details.mobile', 'users.status', 'users.login')
            ->first();

        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->user_id)->first();

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        return view('admin.clients.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {


      $client = ClientDetails::find($id);
  /*
        // if(empty($client)){
        //     $client = new ClientDetails();
        //     $client->user_id = $user->id;
        // }

        $client->company_name = $request->company_name;
        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->mobile = $request->input('mobile');
        $client->address = $request->address;
        $client->website = $request->website;
        $client->note = $request->note;
        $client->skype = $request->skype;
        $client->facebook = $request->facebook;
        $client->twitter = $request->twitter;
        $client->linkedin = $request->linkedin;
        $client->gst_number = $request->gst_number;



        $client->save();*/

        $country = Country::find($request->country_id);

        $address = [$request->street,$request->apt_floor,$request->city,$request->state,$request->zip,$country->name];


        DB::table('client_details')
            ->where('id', $id)
            ->update([
                'first_name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'name' => $request->input('name').' '.$request->input('last_name'),
                'company_name' => $request->company_name,
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'street' => $request->street,
                'apt_floor'  => $request->apt_floor,
                'city'  => $request->city,
                'state'  => $request->state,
                'zip'  => $request->zip,
                'country_id'  => $request->country_id,
                'address' => implode(', ',$address),
                'shipping_address' => $request->shipping_address,
                'website' => $request->website,
                'note' => $request->note,
                'skype' => $request->skype,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'gst_number' => $request->gst_number]);


        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $client->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $user = User::withoutGlobalScopes(['active', 'company'])->findOrFail($client->user_id);


        // if($request->password != ''){
        //     $user->password = Hash::make($request->input('password'));
        // }
        $user->status = $request->input('status');
        $user->email = $request->input('email');
        $user->save();

        return Reply::redirect(route('admin.clients.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $clients_count = ClientDetails::withoutGlobalScope(CompanyScope::class)->where('user_id', $id)->count();

        if ($clients_count > 1) {
            $client_builder = ClientDetails::where('user_id', $id);
            $client = $client_builder->first();

            $user_builder = User::where('id', $id);
            $user = $user_builder->first();
            if ($user) {
                $other_client = $client_builder->withoutGlobalScope(CompanyScope::class)
                    ->where('company_id', '!=', $client->company_id)
                    ->first();

                request()->request->add(['company_id' => $other_client->company_id]);

                $user->save();
            }
            $role = Role::where('name', 'client')->first();
            $user_role = $user_builder->withoutGlobalScope(CompanyScope::class)->first();
            $user_role->detachRoles([$role->id]);
            $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
            if ($universalSearches) {
                foreach ($universalSearches as $universalSearch) {
                    UniversalSearch::destroy($universalSearch->id);
                }
            }
            $client->delete();
        } else {
            // $client = ClientDetails::where('user_id', $id)->first();
            // $client->delete();
            $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
            if ($universalSearches) {
                foreach ($universalSearches as $universalSearch) {
                    UniversalSearch::destroy($universalSearch->id);
                }
            }
            User::destroy($id);
        }
        DB::commit();
        return Reply::success(__('messages.clientDeleted'));
    }

    public function showProjects($id)
    {

        $this->client = User::fromQuery('SELECT users.id, client_details.company_id FROM users JOIN client_details ON client_details.user_id = users.id WHERE client_details.user_id = ' . $id)->first();

        if (!$this->client) {
            abort(404);
        }

        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        return view('admin.clients.projects', $this->data);
    }

    public function showInvoices($id)
    {

        $this->client = User::with('client_detail')->fromQuery('SELECT users.id, client_details.company_id FROM users JOIN client_details ON client_details.user_id = users.id WHERE client_details.user_id = ' . $id)->first();

        $this->clientDetail = $this->client ? $this->client->client_details : abort(404);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->invoices = Invoice::select('invoices.invoice_number', 'invoices.total', 'currencies.currency_symbol', 'invoices.issue_date', 'invoices.id')
            ->leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id)
                    ->orWhere('invoices.client_id', $id);
            })
            ->get();

        return view('admin.clients.invoices', $this->data);
    }

    public function showPayments($id)
    {
        $this->client = User::with('client_detail')->fromQuery('SELECT users.id, client_details.company_id FROM users JOIN client_details ON client_details.user_id = users.id WHERE client_details.user_id = ' . $id)->first();

        $this->clientDetail = $this->client ? $this->client->client_details : abort(404);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->payments = Payment::select('payments.invoice_id', 'payments.amount', 'payments.paid_on', 'payments.status', 'currencies.currency_symbol', 'payments.id', 'projects.project_name')
            ->leftJoin('projects', 'projects.id', '=', 'payments.project_id')
            ->join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id);
            })
            ->get();

        return view('admin.clients.payments', $this->data);
    }

    public function showEstimates($id)
    {
        $this->client = User::with('client_detail')->fromQuery('SELECT users.id, client_details.company_id FROM users JOIN client_details ON client_details.user_id = users.id WHERE client_details.user_id = ' . $id)->first();

        $this->clientDetail = $this->client ? $this->client->client_details : abort(404);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->estimates = Estimate::select('estimates.estimate_number', 'estimates.valid_till', 'estimates.total', 'estimates.status', 'currencies.currency_symbol', 'estimates.id')
            ->join('currencies', 'currencies.id', '=', 'estimates.currency_id')
            ->orderBy('estimates.estimate_number', 'desc')
            ->where(function ($query) use ($id) {
                $query->where('estimates.client_id', $id);
            })
            ->get();

        return view('admin.clients.estimates', $this->data);
    }

    public function export($status, $client)
    {
        $rows = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->withoutGlobalScope('active')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'client')
            ->where('roles.company_id', company()->id)
            ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
            ->select(
                'users.id',
                'client_details.name',
                'client_details.email',
                'client_details.mobile',
                'client_details.company_name',
                'client_details.address',
                'client_details.website',
                'client_details.created_at'
            )
            ->where('client_details.company_id', company()->id);

        if ($status != 'all' && $status != '') {
            $rows = $rows->where('users.status', $status);
        }

        if ($client != 'all' && $client != '') {
            $rows = $rows->where('users.id', $client);
        }

        $rows = $rows->get()->makeHidden(['image']);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Email', 'Mobile', 'Company Name', 'Address', 'Website', 'Created at'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('clients', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Clients');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('clients file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));
                });
            });
        })->download('xlsx');
    }

    public function gdpr($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->allConsents = PurposeConsent::with(['user' => function ($query) use ($id) {
            $query->where('client_id', $id)
                ->orderBy('created_at', 'desc');
        }])->get();

        return view('admin.clients.gdpr', $this->data);
    }

    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentUser::select('purpose_consent.name', 'purpose_consent_users.created_at', 'purpose_consent_users.status', 'purpose_consent_users.ip', 'users.name as username', 'purpose_consent_users.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_users.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_users.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_users.client_id', $id);

        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {
                if ($row->status == 'agree') {
                    $status = __('modules.gdpr.optIn');
                } else if ($row->status == 'disagree') {
                    $status = __('modules.gdpr.optOut');
                } else {
                    $status = '';
                }

                return $status;
            })
            ->make(true);
    }

    public function saveConsentLeadData(SaveConsentUserDataRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = $request->consent_description;
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentUser();
        $newConsentLead->client_id = $user->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        $url = route('admin.clients.gdpr', $user->id);

        return Reply::redirect($url);
    }
}
