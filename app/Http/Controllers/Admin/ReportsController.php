<?php

namespace App\Http\Controllers\Admin;

use App\Project;
use App\ClientDetails;
use App\Lead;
use App\EmployeeDetails;
use App\User;
use App\Designation;
use Carbon\Carbon;
use App\Traits\CurrencyExchange;
use App\Helper\Reply;
use Illuminate\Http\Request;

class ReportsController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.reports';
        $this->pageIcon = 'icofont icofont-chart-histogram';
    }

    public function index()
    {
        $this->projects = Project::all();
        return view('admin.reports.index', $this->data);
    }

    public function filter(Request $request)
    {
        $tab = $request->input('tab'); 
        $this->clients = $this->projects = $this->leads = $this->employees = '' ;

        if($tab == 'jobs'){
            $this->projects = Project::paginate(10);
        } elseif($tab == 'clients'){
            $this->clients = ClientDetails::paginate(10);
        } elseif($tab == 'leads'){
            $this->leads = Lead::paginate(10);
        }  elseif($tab == 'employees'){
            $this->employees = EmployeeDetails::paginate(10);
        }
        
        $view = view('admin.map.ajaxresult', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }
    
}
