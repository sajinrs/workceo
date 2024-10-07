<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\Currency;
use App\EmployeeDetails;
use App\GlobalCurrency;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Companies\DeleteRequest;
use App\Http\Requests\SuperAdmin\Companies\PackageUpdateRequest;
use App\Http\Requests\SuperAdmin\Companies\StoreRequest;
use App\Http\Requests\SuperAdmin\Companies\UpdateRequest;
use App\Libraries\WceoPubbly;
use App\Libraries\WceoZohoSubscriptions;
use App\OfflineInvoice;
use App\OfflinePaymentMethod;
use App\Package;
use App\Role;
use App\Scopes\CompanyScope;
use App\StripeInvoice;
use App\Traits\CurrencyExchange;
use App\User;
use App\UserExtra;
use App\ZohoSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SuperAdminCompanyController extends SuperAdminBaseController
{
    use CurrencyExchange;

    /**
     * AdminProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Companies';
        $this->pageIcon = 'fa fa-th-list';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->totalCompanies = Company::count();
        $this->packages = Package::all();
        return view('super-admin.companies.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $this->currencies = GlobalCurrency::all();
        $this->industries  = DB::table('industries')->get();
        $this->findus      = DB::table('find_us')->get();

        return view('super-admin.companies.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $is_success = true;
        DB::beginTransaction();

        $company = new Company();

        $companyDetail = $this->storeAndUpdate($company, $request);

        $globalCurrency = GlobalCurrency::findOrFail($request->currency_id);
        $currency = Currency::where('currency_code', $globalCurrency->currency_code)
            ->where('company_id', $companyDetail->id)->first();

        if (is_null($currency)) {
            $currency = new Currency();
            $currency->currency_name = $globalCurrency->currency_name;
            $currency->currency_symbol = $globalCurrency->currency_symbol;
            $currency->currency_code = $globalCurrency->currency_code;
            $currency->is_cryptocurrency = $globalCurrency->is_cryptocurrency;
            $currency->usd_price = $globalCurrency->usd_price;
            $currency->company_id = $companyDetail->id;
            $currency->save();
        }

        $company->currency_id = $currency->id;
        $company->save();

        $adminRole = Role::where('name', 'admin')->where('company_id', $companyDetail->id)->withoutGlobalScope('active')->first();

        $user = new User();
        $user->company_id = $companyDetail->id;
        $user->name = 'Admin';
        $user->first_name = 'Admin';
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $employee = new EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->employee_id = 'emp-' . $user->id;
        $employee->company_id = $user->company_id;
        $employee->address = 'address';
        $employee->hourly_rate = '50';
        $employee->save();

        $user->roles()->attach($adminRole->id);

        $employeeRole = Role::where('name', 'employee')->where('company_id', $user->company_id)->first();
        $user->roles()->attach($employeeRole->id);


        // create zoho customer
        $customer_data = $user;
        $customer_data->company_name = $company->company_name;
        $customer_data->display_name = 'WCEO USER #'.$user->id;
        $zoho_customer_resp = WceoZohoSubscriptions::createCustomer($customer_data);


        if($zoho_customer_resp['status'] == 'success'){
            // save customer to db
            $user_extra = new UserExtra();
            $user_extra->user_id = $user->id;
            $user_extra->key_name = 'ZOHO_CUSTOMER_DATA';
            $user_extra->key_value = json_encode($zoho_customer_resp['data']);
            $user_extra->save();

            //assign Default zoho package and trial plan subscription
            $zoho_customer = $zoho_customer_resp['data'];
            $zoho_subscription_resp = WceoZohoSubscriptions::assignTrialSubscription($zoho_customer['customer_id']);

            if($zoho_subscription_resp['status'] == 'success'){
                $zoho_subscription = $zoho_subscription_resp['data'];
                // save subscription to db
                $plan = Package::where('default','yes')->first();

                $subscription = new ZohoSubscription();

                $subscription->company_id = $company->id;
                $subscription->package_id = $plan->id;
                $subscription->zoho_customer_id = $zoho_subscription['customer']['customer_id'];
                $subscription->subscription_id = $zoho_subscription['subscription_id'];
                $subscription->amount = $zoho_subscription['amount'];
                $subscription->currency_symbol = $zoho_subscription['currency_symbol'];
                $subscription->plan_code = $zoho_subscription['plan']['plan_code'];
                $subscription->plan_name = $plan->name;
                $subscription->status = $zoho_subscription['status'];
                $subscription->activation_date = $zoho_subscription['activated_at'];
                $subscription->next_billing_date = $zoho_subscription['next_billing_at'];
                $subscription->more_details = json_encode($zoho_subscription);

                $subscription->save();
            }else{
                $is_success = false;
                $error_msg = $zoho_subscription_resp['message'];
            }
        }else{
            $is_success = false;
            $error_msg = $zoho_customer_resp['message'];
        }
        // create pubbly customer
        /*$pubbly_customer_resp = WceoPubbly::createCustomer($user);
        if($pubbly_customer_resp['status'] == 'success'){
            // save customer to db
            $user_extra = new UserExtra();
            $user_extra->user_id = $user->id;
            $user_extra->key_name = 'PUBBLY_CUSTOMER_DATA';
            $user_extra->key_value = json_encode($pubbly_customer_resp['data']);
            $user_extra->save();
        }*/
        if($is_success){
            DB::commit();
            return Reply::redirect(route('super-admin.companies.index'), 'Company added successfully.');
        }else{
            return Reply::error($error_msg);
        }
     }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        //
    }


    /**
     * @param $companyId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function editPackage($companyId)
    {
        $packages = Package::all();
        $global = $this->global;
        $company = Company::find($companyId);
        $currentPackage = Package::find($company->package_id);
        $lastInvoice = StripeInvoice::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
        $packageInfo = [];
        foreach ($packages as $package) {
            $packageInfo[$package->id] = [
                'monthly' => $package->monthly_price,
                'annual' => $package->annual_price
            ];
        }

        $offlinePaymentMethod = OfflinePaymentMethod::whereNull('company_id')->get();
        $modal = view('super-admin.companies.editPackage', compact('packages', 'company', 'currentPackage', 'lastInvoice', 'packageInfo', 'global', 'offlinePaymentMethod'))->render();

        return response(['status' => 'success', 'data' => $modal], 200);
    }

    public function updatePackage(PackageUpdateRequest $request, $companyId)
    {
        $company = Company::find($companyId);

        try {
            $package = Package::find($request->package);
            $company->package_id = $package->id;
            $company->package_type = $request->packageType;
            $company->status = 'active';

            $payDate = $request->pay_date ? Carbon::parse($request->pay_date) : Carbon::now();

            $company->licence_expire_on = ($company->package_type == 'monthly') ?
                $payDate->copy()->addMonth()->format('Y-m-d') :
                $payDate->copy()->addYear()->format('Y-m-d');

            $nextPayDate = $request->next_pay_date ? Carbon::parse($request->next_pay_date) : $company->licence_expire_on;

            if ($company->isDirty('package_id') || $company->isDirty('package_type')) {
                $offlineInvoice = new OfflineInvoice();

            } else {
                $offlineInvoice = OfflineInvoice::where('company_id', $companyId)->orderBy('created_at', 'desc')->first();
                if (!$offlineInvoice) {
                    $offlineInvoice = new OfflineInvoice();
                }
            }
            $offlineInvoice->company_id = $company->id;
            $offlineInvoice->package_id = $company->package_id;
            $offlineInvoice->package_type = $request->packageType;
            $offlineInvoice->amount = $request->amount ?: $package->{$request->packageType . '_price'};
            $offlineInvoice->pay_date = $payDate;
            $offlineInvoice->next_pay_date = $nextPayDate;
            $offlineInvoice->status = 'paid';

            $offlineInvoice->save();
            $company->save();

            return response(['status' => 'success', 'message' => 'Package Updated Successfully.'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->company = Company::find($id);

        $this->timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $this->currencies = Currency::where('company_id', $id)->get();
        $this->packages = Package::all();
        $this->companyUser = User::where('company_id',$id)->first();
        $this->industries  = DB::table('industries')->get();
        $this->findus      = DB::table('find_us')->get();
        $this->interests   = DB::table('interests')->get();

        return view('super-admin.companies.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $is_success = true;
        $error_msg = '';
        DB::beginTransaction();
        $company = Company::find($id);
        $this->storeAndUpdate($company, $request);

        $company->currency_id = $request->currency_id;
        $company->save();
        $user = User::where('company_id',$company->id)->first();
        if(!is_null($request->password)){
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
        }

        // create/update customer data
        $customer_data = $user;
        $customer_data->company_name = $company->company_name;
        $customer_data->display_name = 'WCEO USER #'.$user->id;
        $user_extra = UserExtra::where('user_id',$user->id)->where('key_name','ZOHO_CUSTOMER_DATA')->first();
        if(!$user_extra){
            //create customer data
            $user_extra = new UserExtra();
            $zoho_customer_resp = WceoZohoSubscriptions::createCustomer($customer_data);
        }else{
            $customer = json_decode($user_extra->key_value,true);
            $zoho_customer_resp = WceoZohoSubscriptions::updateCustomer($customer['customer_id'],$customer_data);
        }

        if($zoho_customer_resp['status'] == 'success') {
            // save customer to db
            $user_extra->user_id = $user->id;
            $user_extra->key_name = 'ZOHO_CUSTOMER_DATA';
            $user_extra->key_value = json_encode($zoho_customer_resp['data']);
            $user_extra->save();
        }else{
            $is_success = false;
            $error_msg = $zoho_customer_resp['message'];
        }

        if($is_success){
            DB::commit();
            return Reply::redirect(route('super-admin.companies.index'), 'Company updated successfully.');
        }else{
            return Reply::error($error_msg);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRequest $request
     * @param int $id
     * @return array
     */
    public function destroy(DeleteRequest $request, $id)
    {
        $user = User::where('company_id',$id)->first();
        if($user){
            $zoho_customer_id = WceoZohoSubscriptions::getCustomerId($user->id);
            //Cancel and Delete all subscriptions of that zoho customer
            $zoho_cancel_all_subs_resp = WceoZohoSubscriptions::cancelAllActiveSubscriptions($zoho_customer_id);
            if($zoho_cancel_all_subs_resp['status'] == 'success'){
                //change customer status inactive
                $zoho_customer_status_resp = WceoZohoSubscriptions::makeCustomerInactive($zoho_customer_id);
                if($zoho_customer_status_resp['status'] != 'success'){
                    return Reply::error($zoho_customer_status_resp['message']);
                }
            }else{
                return Reply::error($zoho_cancel_all_subs_resp['message']);
            }
         }

        Company::destroy($id);
        return Reply::success('Company deleted successfully.');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function data(Request $request)
    {
        $packages = Company::with('currency', 'package');

        if ($request->package != 'all' && $request->package != '') {
            $packages = $packages->where('package_id', $request->package);
        }

        if ($request->type != 'all' && $request->type != '') {
            $packages = $packages->where('package_type', $request->type);
        }

        return Datatables::of($packages)
            ->addColumn('action', function ($row) {
                return '<a href="' . route('super-admin.companies.edit', [$row->id]) . '" class="btn btn-outline-info btn-circle m-b-5"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-outline-danger btn-circle sa-params m-b-5"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('company_name', function ($row) {
                return ucfirst($row->company_name).'<br />'.'<img src="' . $row->logo_url . '" class="img-responsive" style="max-height: 35px" />';
            })
            ->editColumn('status', function ($row) {
                $class = ($row->status == 'active') ? 'badge-success' : 'badge-danger';
                return '<label class="badge ' . $class . '">' . ucfirst($row->status) . '</label>';
            })
            ->editColumn('company_email', function ($row) {
                return '<a href="mailto:' . $row->company_email . '" target="_blank">' . $row->company_email . '</a>';
            })
            ->editColumn('last_login', function ($row) {
                if ($row->last_login != null) {
                    return $row->last_login->diffForHumans();
                }
                return '-';
            })
            ->editColumn('package', function ($row) {
                $package = '<div class="w-100 text-center">';
                $package .= '<div class="m-b-5">' . ucwords($row->package->name) . ' (' . ucfirst($row->package_type) . ')' . '</div>';
               // $package .=
                 '<a href="javascript:;" class="badge badge-secondary package-update-button"
                      data-toggle="tooltip" data-company-id="' . $row->id . '" data-original-title="Change"><i class="fa fa-edit" aria-hidden="true"></i> Change </a>';
                $package .= '</div>';
                return $package;
            })
            ->addColumn('details', function ($row) {
                $companyUser = User::withoutGlobalScope(CompanyScope::class)->withoutGlobalScope('active')->where('company_id', $row->id)->first();

                if ($companyUser && $companyUser->email_verification_code == null) {
                    $verified = '<i class="fa fa-check-circle" style="color: green;"></i>';
                } else if ($companyUser && $companyUser->email_verification_code != null) {
                    $verified = '<i class="fa fa-times" style="color: red;"></i>';
                } else {
                    $verified = '-';
                }

                $registerDate = $row->created_at->format('d-m-Y');
                $totalUsers = User::withoutGlobalScope(CompanyScope::class)->withoutGlobalScope('active')->where('company_id', $row->id)->count();

                $string = "<ul>";
                $string .= "<li>" . __('modules.superadmin.verified') . ": " . $verified . "</li>";
                $string .= "<li>" . __('modules.superadmin.registerDate') . ": " . $registerDate . "</li>";
                $string .= "<li>" . __('modules.superadmin.totalUsers') . ": " . $totalUsers . "</li>";
                $string .= "</ul>";

                return $string;
            })
            ->rawColumns(['action', 'details', 'company_email', 'company_name', 'status', 'package'])
            ->make(true);
    }

    public function storeAndUpdate($company, $request)
    {
        $company->company_name = $request->input('company_name');
        $company->company_email = $request->input('company_email');
        $company->company_phone = $request->input('company_phone');
        $company->website = $request->input('website');
        $company->address = $request->input('address');
        $company->company_size = $request->input('company_size');
        $company->industry = $request->input('industry');
        $company->source = $request->input('source');
        $company->timezone = $request->input('timezone');
        $company->locale = $request->input('locale');
        $company->status = $request->status;

        if ($request->hasFile('logo')) {
            $company->logo = Files::upload($request->logo, 'app-logo');
        }

        $company->last_updated_by = $this->user->id;

        $company->save();


        try{
            $this->updateExchangeRatesCompanyWise($company);
        }catch(\Exception $e){

        }


        return $company;

    }
}
