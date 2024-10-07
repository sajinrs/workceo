<?php

namespace App\Http\Controllers\Api\Admin;

use App\DataTables\Admin\LeadsDataTable;
use App\Helper\ImportCsv;
use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\Gdpr\SaveConsentLeadDataRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Http\Resources\LeadCollection;
use App\Http\Resources\UserResource;
use App\Lead;
use App\User;
use App\LeadAgent;
use App\LeadFollowUp;
use App\LeadSource;
use App\LeadStatus;
use App\PurposeConsent;
use App\PurposeConsentLead;
use App\Http\Resources\ApiResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ApiAdminLeadController extends ApiAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icofont icofont-people');
        $this->pageTitle = trans('app.menu.lead');
        $this->middleware(function ($request, $next) {
            if (!in_array('leads', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        
        return new LeadCollection(Lead::paginate(10));
    }

    
    /*public function show($id)
    {
        $data['lead'] = Lead::findOrFail($id);
        $data['lead']['source'] = $data['lead']->lead_source->type;
        return new UserResource($data);
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        return view('admin.lead.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'client_name' => 'required',
            'client_last_name' => 'required',
            'client_email' => 'required|email|unique:leads',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $lead = new Lead();
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_first_name = $request->client_name;
        $lead->client_last_name = $request->client_last_name;
        $lead->client_name = $request->client_name.' '.$request->client_last_name;
        $lead->client_email = $request->client_email;
        $lead->mobile = $request->mobile;
        $lead->note = $request->note;
        //$lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;
        $lead->source_id = $request->source_id;

        

        try {
            $lead->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            $response = [
                'error' => 1,
                "message" => 'Something went wrong',
            ];
    
            return new ApiResource($response);
        }


        //log search
        $this->logSearchEntry($lead->id, $lead->client_name, 'admin.leads.show', 'lead');
        $this->logSearchEntry($lead->id, $lead->client_email, 'admin.leads.show', 'lead');
        if (!is_null($lead->company_name)) {
            $this->logSearchEntry($lead->id, $lead->company_name, 'admin.leads.show', 'lead');
        }

        //$result['leadAgents'] = LeadAgent::with('user')->get();
        $result = Lead::findOrFail($lead->id);       

        $response = [
            'success' => 1,
            "message" => 'Lead created successfully!',
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => 'Lead created successfully!'  ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$data['leadAgents'] = LeadAgent::with('user')->get();
        $data = Lead::with('lead_source','lead_agent','lead_status','follow','files')->findOrFail($id);
        //$data['sources'] = LeadSource::all();
        //$data['status'] = LeadStatus::all();

        return new ApiResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'client_name' => 'required',
            'client_last_name' => 'required',
            'client_email' => 'required|email|unique:leads,client_email,'.$id,
            'address' => 'required',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $lead = Lead::findOrFail($id);
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_first_name = $request->client_name;
        $lead->client_last_name = $request->client_last_name;
        $lead->client_name = $request->client_name.' '.$request->client_last_name;
        $lead->client_email = $request->client_email;
        $lead->mobile = $request->mobile;
        $lead->note = $request->note;
        $lead->status_id = $request->status;
        $lead->source_id = $request->source;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;

        $lead->save();

        $result = Lead::findOrFail($lead->id);       

        $response = [
            'success' => 1,
            "message" => __('messages.LeadUpdated'),
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.LeadUpdated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lead::destroy($id);
        return response()->json([ 'success' => 1, "message" => __('messages.LeadDeleted')]);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function changeStatus(CommonRequest $request)
    {
        $lead = Lead::findOrFail($request->leadID);
        $lead->status_id = $request->statusID;
        $lead->save();

        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }

    public function gdpr($leadID)
    {
        $this->lead = Lead::findOrFail($leadID);
        $this->allConsents = PurposeConsent::with(['lead' => function ($query) use ($leadID) {
            $query->where('lead_id', $leadID)
                ->orderBy('created_at', 'desc');
        }])->get();

        return view('admin.lead.gdpr.show', $this->data);
    }

    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentLead::select('purpose_consent.name', 'purpose_consent_leads.created_at', 'purpose_consent_leads.status', 'purpose_consent_leads.ip', 'users.name as username', 'purpose_consent_leads.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_leads.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_leads.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_leads.lead_id', $id);

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

    public function saveConsentLeadData(SaveConsentLeadDataRequest $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = $request->consent_description;
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentLead();
        $newConsentLead->lead_id = $lead->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        $url = route('admin.leads.gdpr', $lead->id);

        return Reply::redirect($url);
    }


    /**
     * @param $leadID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followUpCreate($leadID)
    {
        $this->leadID = $leadID;
        return view('admin.lead.follow_up', $this->data);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function followUpStore(\App\Http\Requests\FollowUp\StoreRequest $request)
    {

        $followUp = new LeadFollowUp();
        $followUp->lead_id = $request->lead_id;
        $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
        $followUp->remark = $request->remark;
        $followUp->save();
        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpAddedSuccess'), ['html' => $view]);
    }

    public function followUpShow($leadID)
    {
        $this->leadID = $leadID;
        $this->lead = Lead::findOrFail($leadID);
        return view('admin.lead.followup.show', $this->data);
    }

    public function editFollow($id)
    {
        $this->follow = LeadFollowUp::findOrFail($id);
        $view = view('admin.lead.followup.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    public function UpdateFollow(\App\Http\Requests\FollowUp\StoreRequest $request)
    {
        $followUp = LeadFollowUp::findOrFail($request->id);
        $followUp->lead_id = $request->lead_id;
        $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');;
        $followUp->remark = $request->remark;
        $followUp->save();

        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpUpdatedSuccess'), ['html' => $view]);
    }

    public function followUpSort(CommonRequest $request)
    {
        $leadId = $request->leadId;
        $this->sortBy = $request->sortBy;

        $this->lead = Lead::findOrFail($leadId);
        if ($request->sortBy == 'next_follow_up_date') {
            $order = "asc";
        } else {
            $order = "desc";
        }

        $follow = LeadFollowUp::where('lead_id', $leadId)->orderBy($request->sortBy, $order);


        $this->lead->follow = $follow->get();

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.followUpFilter'), ['html' => $view]);
    }


    public function export($followUp, $client)
    {
        $currentDate = Carbon::today()->format('Y-m-d');
        $lead = Lead::select('leads.id', 'client_name', 'website', 'client_email', 'company_name', 'lead_status.type as statusName', 'leads.created_at', 'lead_sources.type as source', \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' and DATE(next_follow_up_date) >= {$currentDate} ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
            ->leftJoin('lead_status', 'lead_status.id', 'leads.status_id')
            ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id');
        if ($followUp != 'all' && $followUp != '') {
            $lead = $lead->leftJoin('lead_follow_up', 'lead_follow_up.lead_id', 'leads.id')
                ->where('leads.next_follow_up', 'yes')
                ->where('lead_follow_up.next_follow_up_date', '<', $currentDate);
        }
        if ($client != 'all' && $client != '') {
            if ($client == 'lead') {
                $lead = $lead->whereNull('client_id');
            } else {
                $lead = $lead->whereNotNull('client_id');
            }
        }

        $lead = $lead->GroupBy('leads.id')->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Client Name', 'Website', 'Email', 'Company Name', 'Status', 'Created On', 'Source', 'Next Follow Up Date'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($lead as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('leads', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Leads');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('leads file');

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
        $response = $importCsv->importTo('leads',$file_path,$options);
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
            "Content-Disposition" => "attachment; filename=leads_list.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $importCsv = new ImportCsv();
        $columns = $importCsv->getExcelFields('leads');

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


    public function storeAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $agent = new LeadAgent();
        $agent->user_id = $request->agent_name;
        $agent->save();
        $data['agentData'] = LeadAgent::select('lead_agents.id', 'lead_agents.user_id', 'users.name')
            ->join('users','users.id', 'lead_agents.user_id')
            ->get();

        return response()->json([ 'success' => 1, "message" => 'Agent created successfully!', 'agents' => $data  ]);
    }

    public function removeAgent($id)
    {
        $data = [];
        $leadAgent = LeadAgent::where('user_id', $id)->firstOrFail();
        $leadAgent->delete();
        $agentData = LeadAgent::select('lead_agents.id', 'lead_agents.user_id', 'users.name')
            ->join('users','users.id', 'lead_agents.user_id')
            ->get();
        $employeeData = User::doesntHave('lead_agent')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();

        $empDatas = [];
        foreach($employeeData as $empData){
            $empDatas[] = ['name' => $empData->name,'email' => $empData->email,'id' => $empData->id,'created_at' => $empData->created_at, ];
        }

        $data['agents'] = $agentData;
        $data['employees'] = $empDatas;
        
        return response()->json([ 'success' => 1, "message" => 'Agent removed successfully!', 'data' => $data  ]);
    }


}
