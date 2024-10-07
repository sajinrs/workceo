<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PropertyReportDataTable;
use App\ClientPropertie;
use App\ClientDetails;
use App\Country;
use App\Helper\Reply;
use App\Http\Requests\ClientProperties\StoreProperty;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class PropertyReportController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.reports';
        $this->pageIcon = 'icofont icofont-chart-histogram';
        $this->middleware(function ($request, $next) {
            if (!in_array('clients', $this->user->modules)) {
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
    public function index(PropertyReportDataTable $dataTable)
    {

        $this->clients = User::allClients();
        $this->totalClients = count($this->clients);

        // return view('admin.clients.index', $this->data);
        return $dataTable->render('admin.reports.client.property', $this->data);
    }

    
    
}
