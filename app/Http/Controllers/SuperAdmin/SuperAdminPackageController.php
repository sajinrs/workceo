<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\Helper\Reply;
use App\Helper\Files;
use App\Http\Requests\SuperAdmin\Packages\DeleteRequest;
use App\Http\Requests\SuperAdmin\Packages\StoreRequest;
use App\Http\Requests\SuperAdmin\Packages\UpdateRequest;
use App\Libraries\Pubbly\Products;
use App\Libraries\WceoPubbly;
use App\Libraries\WceoZohoSubscriptions;
use App\Module;
use App\ModuleSetting;
use App\Package;
use App\GlobalSetting;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class SuperAdminPackageController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.templates';
        $this->pageIcon = 'fa fa-cubes';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->totalPackages = Package::where('default', '!=', 'trial')->count();
        return view('super-admin.packages.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->modules = Module::all();
        return view('super-admin.packages.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $zoho_package =  WceoZohoSubscriptions::createWceoTemplate($request->all());
        if($zoho_package['status'] == 'success'){
            $package = new Package();
            $request->zoho_product_id = $zoho_package['zoho_product_id'];
            $this->storeAndUpdate($package, $request, 'add');
            $msg = 'Package successfully added.';
            return Reply::redirect(route('super-admin.packages.index'), $msg);
        }else{
            $msg = 'Zoho Error : '.$zoho_package['message'];
            return Reply::error($msg);
        }

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
        $this->package = Package::find($id);
        $this->modules = Module::all();

        return view('super-admin.packages.edit', $this->data);
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
        $package = Package::find($id);
        if($package->default != 'yes'){
            //update pubbly plans
            $api_resp = WceoZohoSubscriptions::updateWceoTemplate($package,$request->all());
        }else{
            $api_resp = WceoZohoSubscriptions::updateWceoDefaultTemplate($package,$request->all());
        }

        if($package->default == 'yes' || $api_resp['status'] == 'success'){
            $this->storeAndUpdate($package, $request, 'edit');
            $msg = 'Package updated successfully.';
            return Reply::redirect(route('super-admin.packages.index'), $msg);
        }else{
            $msg = 'ERROR : '.$api_resp['message'];
            return Reply::error($msg);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, $id)
    {
        $companies = Company::where('package_id', $id)->get();
        /*if($companies){
            $defaultPackage = Package::where('default', 'yes')->first();
            if($defaultPackage){
                foreach($companies as $company){
                    ModuleSetting::where('company_id', $company->id)->delete();

                    $moduleInPackage = (array)json_decode($defaultPackage->module_in_package);
                    $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices'];
                    if($moduleInPackage){
                        foreach ($moduleInPackage as $module) {
                            if(in_array($module, $clientModules)){
                                $moduleSetting = new ModuleSetting();
                                $moduleSetting->company_id = $company->id;
                                $moduleSetting->module_name = $module;
                                $moduleSetting->status = 'active';
                                $moduleSetting->type = 'client';
                                $moduleSetting->save();
                            }

                            $moduleSetting = new ModuleSetting();
                            $moduleSetting->company_id = $company->id;
                            $moduleSetting->module_name = $module;
                            $moduleSetting->status = 'active';
                            $moduleSetting->type = 'employee';
                            $moduleSetting->save();

                            $moduleSetting = new ModuleSetting();
                            $moduleSetting->company_id = $company->id;
                            $moduleSetting->module_name = $module;
                            $moduleSetting->status = 'active';
                            $moduleSetting->type = 'admin';
                            $moduleSetting->save();
                        }
                    }
                    $company->package_id = $defaultPackage->id;
                    $company->save();
                }
            }
        }*/

        if(!$companies->count()){
            $package = Package::find($id);
            //change product name and inactivate pubbly plans
            //TODO Zoho
            //WceoPubbly::inactivateWceoTemplate($package);


            $zoho_package = WceoZohoSubscriptions::deleteTemplate($package);
            if($zoho_package['status'] == 'success'){
                Package::destroy($id);
            }else{
                $msg = 'Zoho Error : '.$zoho_package['message'];
                return Reply::error($msg);
            }
            return Reply::success('Package deleted successfully.');
        }else{
            return Reply::error('Not able to delete, Subscriptions associated with this Plan.');
        }


    }

    public function activate($package_id){
        // activate zoho package
        $package = Package::find($package_id);
        //activate zoho plans
        $api_resp = WceoZohoSubscriptions::activateWceoTemplate($package);
        if($api_resp['status'] == 'success'){
            $package->status = 'active';
            $package->save();
            $msg = 'Package activated successfully.';
            return Reply::success($msg);
        }else{
            $msg = 'ERROR : '.$api_resp['message'];
            return Reply::error($msg);
        }


    }

    /**
     * @return mixed
     */
    public function data()
    {
        $packages = Package::where('default', '!=', 'trial')->get();
        return Datatables::of($packages)
            ->addColumn('action', function($row){
                $action = '';
                if($row->default == 'no'){
                    $action = ' <a href="javascript:;" class="btn btn-sm btn-outline-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="'.$row->id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return '<a href="'.route('super-admin.packages.edit', [$row->id]).'" class="btn btn-sm btn-outline-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>'.$action;
            })
            ->addColumn('status', function($row){
                $status = '<div class="form-label-group form-group">
                            <div class="switch-showcase icon-state">
                                <label class="switch">';
                if($row->status === 'active'){
                    $status .= '<input disabled type="checkbox" name="status" checked  ><span class="switch-state"></span>';
                }else{
                    $status .= '<input class="package_status"  data-id="'.$row->id.'" type="checkbox" name="status"><span class="switch-state"></span>';

                }
                $status .= '</label>
                            </div> 
                       </div>';
                return $status;
            })
            ->editColumn('name', function ($row) {
                    return ucfirst($row->name);
            })
            ->editColumn('module_in_package', function ($row) {
                $modules = json_decode($row->module_in_package);
//                dd($modules);
                $string = '<ul class="list-style-disc">';
                if ($modules)
                {
                    foreach ($modules as $module) {
                        $string .= '<li>' . $module . '</li>';
                    }
                 }
                else{
                    return 'No module selected';
                }
                $string .= '<ul>';
                return $string;
            })

           ->rawColumns(['status','action', 'module_in_package'])
            ->make(true);
    }

    public function storeAndUpdate( $package, $request, $type) {

        $package->name = $request->name;
        $package->description = $request->description;
        $package->annual_price = $request->annual_price;
        $package->monthly_price = $request->monthly_price;
        $package->max_employees = $request->max_employees;
        $package->module_in_package = json_encode($request->module_in_package);
        $package->stripe_annual_plan_id = $request->stripe_annual_plan_id;
        $package->stripe_monthly_plan_id = $request->stripe_monthly_plan_id;
        $package->razorpay_annual_plan_id = $request->razorpay_annual_plan_id;
        $package->razorpay_monthly_plan_id = $request->razorpay_monthly_plan_id;
        $package->currency_id = $this->global->currency_id;
        $package->free_trial_days = $request->trial_days;
        if(isset($request->zoho_product_id) && $request->zoho_product_id != ''){
            $package->zoho_product_id = $request->zoho_product_id;
        }

        if ($request->hasFile('pack_image')) {
            Files::deleteFile($package->pack_image,'package');
            $crop = [
                'width' => $request->input('width'),
                'height'=> $request->input('height'),
                'x'     => $request->input('x'),
                'y'     => $request->input('y')
            ];
            $package->pack_image = Files::upload($request->pack_image, 'package' ,300, false, $crop);
        }

        $package->save();

        ModuleSetting::whereNull('company_id')->delete();

        if($type == 'edit'){
            if($request->has('module_in_package')){
                $companies = Company::where('package_id', $package->id)->get();

                foreach($companies as $company){
                    ModuleSetting::where('company_id', $company->id)->delete();

                    $moduleInPackage = (array)json_decode($package->module_in_package);
                    $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices'];
                    foreach ($moduleInPackage as $module) {

                        if(in_array($module, $clientModules)){
                            $moduleSetting = new ModuleSetting();
                            $moduleSetting->company_id = $company->id;
                            $moduleSetting->module_name = $module;
                            $moduleSetting->status = 'active';
                            $moduleSetting->type = 'client';
                            $moduleSetting->save();
                        }

                        $moduleSetting = new ModuleSetting();
                        $moduleSetting->company_id = $company->id;
                        $moduleSetting->module_name = $module;
                        $moduleSetting->status = 'active';
                        $moduleSetting->type = 'employee';
                        $moduleSetting->save();

                        $moduleSetting = new ModuleSetting();
                        $moduleSetting->company_id = $company->id;
                        $moduleSetting->module_name = $module;
                        $moduleSetting->status = 'active';
                        $moduleSetting->type = 'admin';
                        $moduleSetting->save();
                    }
                }
            }
        }

    }


    public function updatePackBanner(Request $request, $companyId)
    {
        $setting = GlobalSetting::findOrFail($companyId);

        if ($request->hasFile('package_banner_monthly')) 
        {
            $this->validate($request, [
                'package_banner_monthly' => 'dimensions:width=680,height=270'
            ]);

            $setting->package_banner_monthly = Files::upload($request->package_banner_monthly, 'package' ,null,null);
        }

        if ($request->hasFile('package_banner_annually')) 
        {
            $this->validate($request, [
                'package_banner_annually' => 'dimensions:width=680,height=270'
            ]);

            $setting->package_banner_annually = Files::upload($request->package_banner_annually, 'package' ,null,null);
        }

        if ($request->hasFile('billing_footer_image')) 
        {
            $this->validate($request, [
                'billing_footer_image' => 'dimensions:width=680,height=130'
            ]);

            $setting->billing_footer_image = Files::upload($request->billing_footer_image, 'package' ,null,null);
        }

        if ($request->hasFile('checkout_left_image')) 
        {
            $this->validate($request, [
                'checkout_left_image' => 'dimensions:width=680,height=130'
            ]);

            $setting->checkout_left_image = Files::upload($request->checkout_left_image, 'package' ,null,null);
        }

        $setting->save();
        return Reply::success('messages.updateSuccess');
    }

}
