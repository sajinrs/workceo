<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\FaqCategory;
use App\Traits\CurrencyExchange;
use App\Helper\Reply;
use Illuminate\Http\Request;

class FaqController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.workcoach';
        $this->pageIcon = 'icofont icofont-star-shape';
    }

    public function index()
    {
        $this->faqCategories = FaqCategory::all();
        return view('admin.faqs.index', $this->data);
    }

    public function details($id)
    {
        $this->faqDetails = Faq::findOrFail($id);

        return view('admin.faqs.details', $this->data);
    }

    public function search(Request $request)
    {
        $cat_id = $request->input('faqcat');    
        $keyword = $request->input('keyword');  
        $query = Faq::query();

        if(!empty($keyword)){
            $query = $query->where('title', 'LIKE', '%' . $keyword . '%');
        }
        if(!empty($cat_id)){
            $query = $query->whereIn('faq_category_id', $cat_id);
        }

        $query = $query->paginate(12);


        $this->faqs = $query;
        $view = view('admin.faqs.ajaxresult', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function viewFaq($id)
    {
        $this->faq = Faq::find($id);
        return view('admin.faqs.faq-popup', $this->data);
    }
}
