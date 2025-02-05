<?php

namespace App\Http\Controllers\Api\Admin;

use App\DataTables\Admin\NoticeBoardDataTable;
use App\Helper\Reply;
use App\Http\Requests\Notice\StoreNotice;
use App\Notice;
use App\Notifications\NewNotice;
use App\Team;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\UserResource;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiAdminManageNoticesController extends ApiAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.noticeBoard';
        $this->pageIcon = 'fa fa-window-maximize';
        $this->middleware(function ($request, $next) {
            if (!in_array('notices',$this->user->modules)) {
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
        return ApiResource::collection(Notice::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->teams = Team::with('member')->get();
        return view('admin.notices.create', $this->data);
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
            'heading' => 'required'
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $notice = new Notice();
        $notice->heading = $request->heading;
        $notice->description = $request->description;
        $notice->to = $request->to;
        $notice->team_id = $request->team_id;
        $notice->icon = $request->icon;
        $notice->save();

        $this->logSearchEntry($notice->id, 'Notice: '.$notice->heading, 'admin.notices.edit', 'notice');

        $result = Notice::findOrFail($notice->id);

        $response = [
            'success' => 1,
            "message" => __('messages.noticeAdded'),
            'data'    => $result
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.noticeAdded')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    /*public function show($id)
    {
        $this->notice = Notice::findOrFail($id);
        return view('admin.notices.show', $this->data);
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$data['teams'] = Team::with('member')->get();
        $data = Notice::findOrFail($id);
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
            'heading' => 'required'
        ]);        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $notice = Notice::findOrFail($id);
        $notice->heading = $request->heading;
        $notice->description = $request->description;
        $notice->to = $request->to;
        $notice->team_id = $request->team_id;
        $notice->icon = $request->icon;
        $notice->save();

        if($request->to == 'employee') {
            if($request->team_id != '') {
                $users = User::join('employee_details','employee_details.user_id', 'users.id')
                    ->where('employee_details.department_id', $request->team_id)->get();
            }
            else {
                $users = User::allEmployees();
            }

            //Notification::send($users, new NewNotice($notice));

        }

        if($request->to == 'client') {
            $clients = User::join('client_details', 'client_details.user_id', '=', 'users.id')
                ->select('users.id', 'client_details.name', 'client_details.email', 'client_details.created_at')
                ->get();
            //Notification::send($clients, new NewNotice($notice));
        }

        $response = [
            'success' => 1,
            "message" => __('messages.noticeUpdated'),
            'data'    => $notice
        ];

        return new ApiResource($response);

        //return response()->json([ 'success' => 1, "message" => __('messages.noticeUpdated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notice::destroy($id);

        $response = [
            'success' => 1,
            "message" => __('messages.noticeDeleted'),
        ];

        return new ApiResource($response);
        //return response()->json([ 'success' => 1, "message" => __('messages.noticeDeleted')]);
    }

    public function export($startDate, $endDate) {

        $notice = Notice::select('id','heading', 'created_at');
        if($startDate !== null && $startDate != 'null' && $startDate != ''){
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '>=', $startDate);
        }

        if($endDate !== null && $endDate != 'null' && $endDate != ''){
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '<=', $endDate);
        }

        $attributes =  ['created_at'];

        $notice = $notice->get()->makeHidden($attributes);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Notice','Date'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($notice as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('notice', function($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Notice');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('notice file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));

                });

            });

        })->download('xlsx');
    }

}
