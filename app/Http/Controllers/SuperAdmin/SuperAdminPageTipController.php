<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\PageTipArticle;
use App\PageTipModule;
use App\Helper\Reply;

use App\Http\Requests\SuperAdmin\PageTipModule\StoreRequest;
use App\Http\Requests\SuperAdmin\PageTipModule\UpdateRequest;
use App\Http\Requests\SuperAdmin\PageTipModule\ArticleStoreRequest;
use App\Http\Requests\SuperAdmin\PageTipModule\ArticleUpdateRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class SuperAdminPageTipController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.pageTips';
        $this->pageIcon = 'fa fa-info-circle';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('super-admin.page-tips.module.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->module = new PageTipModule();

        return view('super-admin.page-tips.module.add-edit', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $module = new PageTipModule();
        $module->name = $request->name;
        $module->save();

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
        $this->module = PageTipModule::find($id);
        return view('super-admin.page-tips.module.add-edit', $this->data);
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
        $module = PageTipModule::find($id);
        $module->name = $request->name;
        $module->save();

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
        $modules = PageTipModule::all();

        return Datatables::of($modules)
            ->addColumn('article', function($row) {

                $articles = $row->articles;

                if($articles->count() > 0)
                {
                    $string = '<ul id="module_'.$row->id.'" data-moduleid="'.$row->id.'" class="sortable">';

                    foreach ($articles as $article)
                    {
                        $string .= '<li id="'.$article->id.'"><a href="javascript:;" onclick="showArticleEdit('.$row->id. ','. $article->id .')">'.$article->title.'</a></li>';
                    }

                    $string .= '</ul>';
                } else {
                    $string = '-';
                }


                return $string;
            })
            ->addColumn('action', function($row){
                $action = '';

                $action .= '<a href="javascript:;" onclick="showArticleAdd('.$row->id.')" class="btn btn-success btn-circle"
                      data-toggle="tooltip" data-original-title="'.trans('app.addNew').' ' . trans('app.article').'"><i class="fa fa-plus" aria-hidden="true"></i></a>';

                $action .= ' <a href="javascript:;" onclick="showModuleEdit('.$row->id.')" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="'.trans('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

                /* $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                  data-toggle="tooltip" data-user-id="'.$row->id.'" data-original-title="'.trans('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>'; */

                return $action;

            })

            ->rawColumns(['action', 'article'])
            ->make(true);
    }

    public function articleCreate($moduleId)
    {
        $this->moduleId = $moduleId;
        $this->article = new PageTipArticle();
        return view('super-admin.page-tips.add-edit-article', $this->data);
    }

    public function articleEdit($moduleId, $id)
    {
        $this->moduleId = $moduleId;
        $this->article = PageTipArticle::find($id);
        return view('super-admin.page-tips.add-edit-article', $this->data);
    }

    public function articleStore(ArticleStoreRequest $request, $categoryId)
    {
        $article               = new PageTipArticle();
        $article->title        = $request->title;
        $article->description  = $request->description;
        $article->module_id    = $request->module_id;       
        $article->save();

        return Reply::success( 'messages.createSuccess');       
    }

    public function articleUpdate(ArticleUpdateRequest $request, $categoryId, $id)
    {
        $article = PageTipArticle::find($id);
        $article->title         = $request->title;
        $article->description  = $request->description;
        $article->module_id    = $request->module_id;  

        $article->save();

        return Reply::success('messages.updateSuccess');
    }

    public function articleDestroy($categoryId, $id)
    {
        PageTipArticle::destroy($id);
        return Reply::success('messages.deleteSuccess');
    }

    public function articleSort(Request $request, $categoryId)
    {
        $sortArray = $request->sortArray;        

        foreach($sortArray as $key=>$id)
        {
            $article = PageTipArticle::find($id);
            $article->sort_order   = $key+1;
            $article->save();
        }

        
    }

}
