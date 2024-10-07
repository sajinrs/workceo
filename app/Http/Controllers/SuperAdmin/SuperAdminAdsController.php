<?php

namespace App\Http\Controllers\SuperAdmin;

use App\AdsSpace;
use App\AdsCategory;
use App\Helper\Reply;
use App\Helper\Files;

//use App\Http\Requests\SuperAdmin\Ads\StoreRequest;
use App\Http\Requests\SuperAdmin\Ads\UpdateRequest;
use Yajra\DataTables\Facades\DataTables;


class SuperAdminAdsController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.adspace';
        $this->pageIcon = 'fa fa-bullhorn';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('super-admin.ads-space.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->faqCategory = new FaqCategory();

        return view('super-admin.ads-space.add-edit', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $faqCategory = new FaqCategory();
        $faqCategory->name = $request->name;
        $faqCategory->fontawesome_code = $request->fontawesome_code;
        $faqCategory->save();

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
        $this->ads = AdsSpace::find($id);

        //Footer data
        if($id == 6) {
            $this->footerData = json_decode($this->ads->content);
        }

        return view('super-admin.ads-space.add-edit-ads', $this->data);
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
        
        $ads = AdsSpace::find($id);        
        $ads->button_text = $request->button_text;
        $ads->status = $request->status;
        $ads->duration = $request->duration;

        if($request->has('footer')) {
            $ads->content   = json_encode($request->footer);
        } else {
            $ads->content   = $request->content;
        }
       
        
        if ($request->hasFile('image')) {
            $ads->photo = Files::upload($request->image, 'ads');
        }

        if($id == 2){
            $this->validate($request, [
                'image' => 'dimensions:width=320,height=40'
           ]);
        } else if($id == 3){
            $this->validate($request, [
                'image' => 'dimensions:width=110,height=200'
           ]);
        } else if($id == 4){
            $this->validate($request, [
                'image' => 'dimensions:width=450,height=450'
           ]);
        } else if($id == 5){
            $this->validate($request, [
                'image' => 'dimensions:width=950,height=200'
           ]);
        } 

       
      
        $ads->save();

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
        FaqCategory::destroy($id);

        return Reply::success('messages.deleteSuccess');
    }

    /**
     * @return mixed
     */
    public function data()
    {
        $adsSpace = AdsSpace::all();        


        return Datatables::of($adsSpace)
           
            ->addColumn('action', function($row){
                $action = '';              

                $action .= ' <a href="javascript:;" onclick="editAdspace('.$row->id.')" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="'.trans('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';                

                return $action;

            })

            ->addColumn('adspace', function($row){
                return $row->adsCategory->category;
            })

            ->rawColumns(['action','adspace'])
            ->make(true);
    }
}
