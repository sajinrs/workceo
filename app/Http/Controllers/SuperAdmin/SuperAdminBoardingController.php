<?php

namespace App\Http\Controllers\SuperAdmin;

use App\OnBoarding;
use App\Helper\Reply;
use App\Helper\Files;

use App\Http\Requests\SuperAdmin\OnBoarding\StoreRequest;
use App\Http\Requests\SuperAdmin\OnBoarding\UpdateRequest;
use Yajra\DataTables\Facades\DataTables;


class SuperAdminBoardingController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.onBoarding';
        $this->pageIcon = 'fa fa-graduation-cap';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('super-admin.onboarding.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super-admin.onboarding.add-boarding');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $boarding = new OnBoarding();
        $boarding->title             = $request->title;
        $boarding->description       = $request->description;
        $boarding->icon_code         = $request->icon_code;
        $boarding->popup_title       = $request->popup_title;
        $boarding->popup_description = $request->popup_description;
        $boarding->type              = $request->type;
        $boarding->popup_link        = $request->popup_link;        

        if ($request->hasFile('image')) {
            $boarding->popup_image = Files::upload($request->image, 'boarding' ,null,null);
        } 
        $boarding->save();

        return Reply::success( 'messages.createSuccess');
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
        $this->boarding = OnBoarding::find($id);
        return view('super-admin.onboarding.edit-boarding', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $boarding = OnBoarding::find($id);
        $boarding->title             = $request->title;
        $boarding->description       = $request->description;
        $boarding->icon_code         = $request->icon_code;
        $boarding->popup_title       = $request->popup_title;
        $boarding->popup_description = $request->popup_description;
        $boarding->type              = $request->type;
        $boarding->popup_link        = $request->popup_link;

        if ($request->hasFile('image')) {
            $boarding->popup_image = Files::upload($request->image, 'boarding' ,null,null);
        }      

        $boarding->save();
        return Reply::success('messages.updateSuccess');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OnBoarding::destroy($id);
        return Reply::success('messages.deleteSuccess');
    }

    /**
     * @return mixed
     */
    public function data()
    {
        $boarding = OnBoarding::all();        


        return Datatables::of($boarding)
           
            ->addColumn('action', function($row){
                $action = '';              

                $action .= ' <a href="javascript:;" onclick="editBoarding('.$row->id.')" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="'.trans('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                      
                      <a href="javascript:;" onclick="deleteBoarding('.$row->id.')" class="btn btn-danger btn-circle"
                      data-toggle="tooltip" data-original-title="'.trans('app.edit').'"><i class="fa fa-times" aria-hidden="true"></i></a>';                

                return $action;

            })

            ->addColumn('adspace', function($row){
                return $row->title;
            })

            ->addIndexColumn()
            ->rawColumns(['action','adspace'])
            ->make(true);
    }
}
