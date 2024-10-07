<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\Reply;
use App\User;
use App\Currency;
use App\LeadAgent;
use App\LeadSource;
use App\LeadStatus;
use App\Tax;
use App\Vehicle;
use App\ProjectCategory;
use App\ProductCategory;
use App\Project;
use App\Product;
use App\Country;
use App\Http\Requests\CommonRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\ApiResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class ApiAdminDynamicController extends ApiAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($source)
    {
        $data = '';
        if($source == 'currency') {
            $data = Currency::all();            
        }

        if($source == 'lead-agent') {
            $data = LeadAgent::with('user')->get();
        }

        if($source == 'lead-source') {
            $data = LeadSource::all();
        }

        if($source == 'tax') {
            $data = Tax::all();
        }

        if($source == 'clients') {
            $data = User::allClients();
        }

        if($source == 'project-categories') {
            $data = ProjectCategory::all();
        }

        if($source == 'vehicles') {
            $data = Vehicle::all();
        }

        if($source == 'product-categories') {
            $data = ProductCategory::all();
        }

        if($source == 'countries') {
            $data = Country::all(['id', 'name']);
        }

        if($source == 'agents') {
            $data = User::doesntHave('lead_agent')
                        ->join('role_user', 'role_user.user_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'role_user.role_id')
                        ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                        ->where('roles.name', 'employee')
                        ->get();
        }
        
        if($source == 'lead-status') {
            $data = LeadStatus::all();
        }

        if($source == 'projects') {
            $data = $this->projects = Project::select('id', 'project_name')->get();
        }


        return new ApiResource($data);
    }

    
    public function show($id)
    {
        $data['lead'] = Lead::findOrFail($id);
        $data['lead']['source'] = $data['lead']->lead_source->type;
        return new ApiResource($data);
    }

    
    public function getCategoryProducts($cid)
    {
        $data = Product::select('name','id')->where(['category_id' => $cid, 'allow_purchase' => 1])->get();
        return new ApiResource($data);
    }

    public function getProductInvoice($id)
    {
        $data = Product::with('tax')->findOrFail($id);
        return new ApiResource($data);
    } 
}
