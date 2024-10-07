<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\PageTipArticle;
use App\PageTipModule;
use App\Helper\Reply;

use Yajra\DataTables\Facades\DataTables;


class AdminPageTipController extends AdminBaseController
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        $this->articles = PageTipArticle::where('module_id', $id)->orderBy('sort_order')->get();
        return view('admin.pagetips.articles-ajax', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->article = PageTipArticle::find($id);
        return view('admin.pagetips.article-details-ajax', $this->data);
    }

   

}
