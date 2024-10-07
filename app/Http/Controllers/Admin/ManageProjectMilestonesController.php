<?php

namespace App\Http\Controllers\Admin;

use App\Project;
use App\Http\Requests\Milestone\StoreMilestone;
use App\ProjectMilestone;
use App\Helper\Reply;
use Yajra\DataTables\Facades\DataTables;
use App\Currency;

class ManageProjectMilestonesController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.jobs');
        $this->pageIcon = 'fas fa-list-alt';
        $this->middleware(function ($request, $next) {
            if (!in_array('projects', $this->user->modules)) {
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMilestone $request)
    {
        $milestone = new ProjectMilestone();
        $milestone->project_id = $request->project_id;
        $milestone->milestone_title = $request->milestone_title;
        $milestone->summary = $request->summary;
        $milestone->cost = ($request->cost == '') ? '0' : $request->cost;
        $milestone->currency_id = $request->currency_id;
        $milestone->status = $request->status;
        $milestone->save();

        return Reply::success(__('messages.milestoneSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = Project::findorFail($id);
        $this->currencies = Currency::all();
        return view('admin.projects.milestones.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->milestone = ProjectMilestone::findOrFail($id);
        $this->currencies = Currency::all();
        return view('admin.projects.milestones.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMilestone $request, $id)
    {
        $milestone = ProjectMilestone::findOrFail($id);
        $milestone->project_id = $request->project_id;
        $milestone->milestone_title = $request->milestone_title;
        $milestone->summary = $request->summary;
        $milestone->cost = ($request->cost == '') ? '0' : $request->cost;
        $milestone->currency_id = $request->currency_id;
        $milestone->status = $request->status;
        $milestone->save();

        return Reply::success(__('messages.milestoneSuccess')); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProjectMilestone::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    public function data($id)
    {
        $milestones = ProjectMilestone::with('currency')->where('project_id', $id)->get();

        return DataTables::of($milestones)
            ->addColumn('action', function($row){
                return '<a href="javascript:;" class="btn btn-outline-info btn-small edit-milestone"
                        data-toggle="tooltip" data-milestone-id="'.$row->id.'"  data-original-title="'.__('app.edit').'"><i class="fa fa-pen" aria-hidden="true"></i></a>

                        <a href="javascript:;" class="btn btn-outline-danger btn-small sa-params"
                        data-toggle="tooltip" data-milestone-id="'.$row->id.'" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('status', function($row){
                if($row->status == 'complete'){
                    return '<label class="badge badge-success">'.$row->status.'</label>';
                }
                else{
                    return '<label class="badge badge-danger">'.$row->status.'</label>';
                }
            })
            ->editColumn('cost', function($row) {
                if(!is_null($row->currency_id)) {
                    return $row->currency->currency_symbol.currencyFormat($row->cost);
                }
                return $row->cost;
            })
            ->editColumn('milestone_title', function($row) {
                return '<a href="javascript:;" class="milestone-detail" data-milestone-id="'.$row->id.'">'.ucfirst($row->milestone_title).'</a>';
            })
            ->rawColumns(['status', 'action', 'milestone_title'])
            ->removeColumn('project_id')
            ->make(true);
    }

    public function detail($id)
    {
        $this->milestone = ProjectMilestone::findOrFail($id);
        return view('admin.projects.milestones.detail', $this->data);
    }

/////
}
