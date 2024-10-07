<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contract;
use App\ContractDiscussion;
use App\ContractSign;
use App\ContractType;
use App\DataTables\Admin\ContractsDataTable;
use App\Helper\Reply;
use App\Helper\Files;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ContractCollection;
use App\Http\Resources\UserResource;
use App\Http\Requests\Admin\Contract\SignRequest;
use App\Http\Requests\Admin\Contract\StoreDiscussionRequest;
use App\Http\Requests\Admin\Contract\StoreRequest;
use App\Http\Requests\Admin\Contract\UpdateDiscussionRequest;
use App\Http\Requests\Admin\Contract\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApiAdminContractController extends ApiAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'fa fa-file';
        $this->pageTitle = __('app.menu.contracts');
        $this->middleware(function ($request, $next) {
            if(!in_array('contracts',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index()
    {
        return new ContractCollection(Contract::paginate(10));
    }

    public function index2(ContractsDataTable $dataTable) {
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        $this->contractCounts = Contract::count();
        $this->expiredCounts = Contract::where(DB::raw('DATE(`end_date`)'), '<', Carbon::now()->format('Y-m-d'))->count();
        $this->aboutToExpireCounts = Contract::where(DB::raw('DATE(`end_date`)'), '>', Carbon::now()->format('Y-m-d'))
            ->where(DB::raw('DATE(`end_date`)'), '<', Carbon::now()->addDays(30)->format('Y-m-d'))
            ->count();
        return $dataTable->render('admin.contracts.index', $this->data);
    }

    public function create() {
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        return view('admin.contracts.create', $this->data);
    }

    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'client' => 'required',
            'subject' => 'required',
            'amount' => 'required',
            'contract_type' => 'required|exists:contract_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $contract = new Contract();

        if ($request->hasFile('contract_file')) {
            $this->validate($request, [
                'contract_file' => 'mimes:pdf,doc,docx'
            ]);

            $file = $request->contract_file;
            $filename = time() . '.' . $request->contract_file->extension();
            $filePath = public_path() . '/user-uploads/contract/';
            $file->move($filePath, $filename);
            $contract['contract_file'] = $filename;
        }   
        $contract = $this->storeUpdate($request, $contract);

        $result = Contract::with('signature', 'renew_history', 'renew_history.renewedBy')->find($contract->id);
        $response = [
            'success' => 1,
            "message" => __('messages.contractAdded'),
            'data'    => $result
        ];

        return new ApiResource($response);
        //return response()->json([ 'success' => 1, "message" => __('messages.contractAdded')]);
    }

    public function show($id) {
        $data = Contract::with('client', 'contract_type', 'signature', 'discussion', 'discussion.user')->find($id);
        $data->client->makeHidden(['unreadNotifications','modules','role','user_other_role','name']);
        return new ApiResource($data);
    }

    public function update(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'client' => 'required',
            'subject' => 'required',
            'amount' => 'required',
            'contract_type' => 'required|exists:contract_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $contract = Contract::findOrFail($id);
        
        if ($request->hasFile('contract_file')) {
            $this->validate($request, [
                'contract_file' => 'mimes:pdf,doc,docx'
           ]);
            $file = $request->contract_file;
            $filename = time() . '.' . $request->contract_file->extension();
            $filePath = public_path() . '/user-uploads/contract/';
            $file->move($filePath, $filename);
            $contract['contract_file'] = $filename;
        }       
        $this->storeUpdate($request, $contract);

        $result = Contract::with('signature', 'renew_history', 'renew_history.renewedBy')->find($id);
        $response = [
            'success' => 1,
            "message" => __('messages.contractUpdated'),
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.contractUpdated')]);
    }

    /*public function show($id) {
        $data['contract'] = Contract::where('id', $id)
            ->with('client', 'contract_type', 'signature', 'discussion', 'discussion.user')
            ->firstOrFail();

        $data['company'] = [
                            'name' => $this->global->company_name,
                            'address' => $this->global->address,
                            'currency' => $this->global->currency->currency_symbol
                        ];

        return new UserResource($data);
    }*/

    private function storeUpdate($request, $contract)
    {
        $contract->client_id = $request->client;
        $contract->subject = $request->subject;
        $contract->amount = $request->amount;
        $contract->original_amount = $request->amount;
        $contract->contract_type_id = $request->contract_type;
        $contract->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $contract->original_start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $contract->end_date = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d');
        $contract->original_end_date = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d');
        $contract->description = $request->description;

        if($request->contract_detail) {
            $contract->contract_detail = $request->contract_detail;
        }
        $contract->save();

        return $contract;
    }

    public function destroy($id) {
        Contract::destroy($id);

        $response = [
            'success' => 1,
            "message" => __('messages.contactDeleted')
        ];

        return new ApiResource($response);
    }

    public function download($id)
    {
        $this->contract = Contract::findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.contracts.contract-pdf', $this->data);

        $filename = 'contract-' . $this->contract->id;

        return $pdf->download($filename . '.pdf');
    }

    public function addDiscussion(StoreDiscussionRequest $request, $id)
    {
        $contractDiscussion = new ContractDiscussion();
        $contractDiscussion->from = $this->user->id;
        $contractDiscussion->message = $request->message;
        $contractDiscussion->contract_id = $id;
        $contractDiscussion->save();

        return Reply::redirect(route('admin.contracts.show', md5($id).'#discussion'), __('messages.addDiscussion'));
    }

    public function contractSignModal($id)
    {
        $this->contract = Contract::find($id);
        return view('admin.contracts.accept', $this->data);
    }

    public function contractSign(SignRequest $request, $id)
    {
        $this->contract =Contract::whereRaw('md5(id) = ?', $id)->firstOrFail();

        if(!$this->contract) {
            return Reply::error('you are not authorized to access this.');
        }

        $sign = new ContractSign();
        $sign->full_name = $request->first_name. ' '. $request->last_name;
        $sign->contract_id = $this->contract->id;
        $sign->email = $request->email;

        $image = $request->signature;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(32).'.'.'jpg';

        if (!\File::exists(public_path('user-uploads/' . 'contract/sign'))) {
            $result = \File::makeDirectory(public_path('user-uploads/contract/sign'), 0775, true);
        }

        \File::put(public_path(). '/user-uploads/contract/sign/' . $imageName, base64_decode($image));

        $sign->signature = $imageName;
        $sign->save();

        return Reply::redirect(route('admin.contracts.show', md5($this->contract->id)));

    }

    public function editDiscussion($id)
    {
        $this->discussion = ContractDiscussion::find($id);
        return view('admin.contracts.edit-discussion', $this->data);
    }

    public function updateDiscussion(UpdateDiscussionRequest $request, $id)
    {
        $this->discussion = ContractDiscussion::find($id);
        $this->discussion->message = $request->messages;
        $this->discussion->save();

        return Reply::success(__('modules.contracts.discussionUpdated'));
    }

    public function removeDiscussion($id)
    {
        ContractDiscussion::destroy($id);

        return Reply::success(__('modules.contracts.discussionDeleted'));
    }

    public function copy($id) {
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        $this->contract = Contract::with('signature', 'renew_history', 'renew_history.renewedBy')->find($id);
        return view('admin.contracts.copy', $this->data);
    }

    public function copySubmit(StoreRequest $request)
    {
        $contract  = new Contract();
        $contract->client_id = $request->client;
        $contract->subject = $request->subject;
        $contract->amount = $request->amount;
        $contract->original_amount = $request->amount;
        $contract->contract_type_id = $request->contract_type;
        $contract->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $contract->original_start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $contract->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $contract->original_end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $contract->description = $request->description;

        if($request->contract_detail) {
            $contract->contract_detail = $request->contract_detail;
        }

        $contract->save();

        return Reply::redirect(route('admin.contracts.edit', $contract->id), __('messages.contractAdded'));

    }
}
