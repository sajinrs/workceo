<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Admin\Billing\OfflinePaymentRequest;
use App\Http\Requests\Admin\Billing\PubblyAddressRequest;
use App\Http\Requests\Admin\Billing\PubblyBillingRequest;
use App\Http\Requests\Admin\Billing\SaveCardRequest;
use App\Http\Requests\Admin\Billing\ZohoAddressRequest;
use App\Http\Requests\StripePayment\PaymentRequest;
use App\Libraries\WceoPubbly;
use App\Libraries\WceoZohoSubscriptions;
use App\Module;
use App\Notifications\OfflinePackageChangeRequest;
use App\OfflineInvoice;
use App\OfflinePaymentMethod;
use App\OfflinePlanChange;
use App\Package;
use App\PaypalInvoice;
use App\PubblySubscription;
use App\RazorpayInvoice;
use App\RazorpaySubscription;
use App\Scopes\CompanyScope;
use App\StripeSetting;
use App\Subscription;
use App\TicketFile;
use App\Traits\StripeSettings;
use App\UserExtra;
use App\Country;
use App\ZohoSubscription;
use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CompanyUpdatedPlan;
use Razorpay\Api\Api;

class AdminBillingController extends AdminBaseController
{
    use StripeSettings;

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.billing';
        $this->setStripConfigs();
        $this->pageIcon = 'fa fa-money-bill';
    }

    public function index() {
        $this->packages = Package::where('default', 'no')->where('status','active')->get();
        $this->modulesData = Module::all();
        $this->stripeSettings = StripeSetting::first();
        $this->offlineMethods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->whereNull('company_id')->where('status', 'yes')->count();

        $this->nextPaymentDate = '-';
        $this->previousPaymentDate = '';
        $this->amount = '-';
        $this->interval = '-';
        $this->activeZohoSubscription = null;
        $this->zohoClientPortal = '-';
        $company = Company::findOrFail(company()->id);

        $zoho_subscription = DB::table("zoho_subscriptions")
            ->join('packages', 'packages.id', 'zoho_subscriptions.package_id')
            ->where('zoho_subscriptions.company_id', company()->id)
            ->orderByDesc('zoho_subscriptions.id')
            ->first();
        if($zoho_subscription){
            if($zoho_subscription->subscription_id){
                $zohoSubscription = WceoZohoSubscriptions::getSubscriptionById($zoho_subscription->subscription_id);
               if($zohoSubscription['status'] == 'success'){
                   $this->nextPaymentDate = (isset($zohoSubscription['data']['next_billing_at']))?Carbon::parse($zohoSubscription['data']['next_billing_at'])->toFormattedDateString():'';
                   $this->previousPaymentDate = (isset($zohoSubscription['data']['last_billing_at']))?Carbon::parse($zohoSubscription['data']['last_billing_at'])->toFormattedDateString():'';
                   $this->amount =  $zohoSubscription['data']['amount'];
                   $this->interval = ($zohoSubscription['data']['interval_unit'] == 'months')?'Month':(($zohoSubscription['data']['interval_unit'] == 'years')?'Year':'Week');
                   $this->activeZohoSubscription = $zohoSubscription['data'];
               }
            }
        }

        $maxEmp              = $this->company->package->max_employees;
        $crntEmp             = $this->company->employees->count();
        $this->empPercentage = ($crntEmp/$maxEmp) * 100;

        if($company->package->default == 'yes'){
            // get subscription details
            $zoho_subscription = DB::table("zoho_subscriptions")
                ->join('packages', 'packages.id', 'zoho_subscriptions.package_id')
                ->where('zoho_subscriptions.company_id', company()->id)
                ->orderByDesc('zoho_subscriptions.id')
                ->first();
            if($zoho_subscription) {
                if ($zoho_subscription->subscription_id) {
                    $zohoSubscription = WceoZohoSubscriptions::getSubscriptionById($zoho_subscription->subscription_id);
                    if ($zohoSubscription['status'] == 'success') {
                        $this->zoho_subscription_details = $zohoSubscription['data'];
                    }
                }
            }

        }
        return view('admin.billing.index', $this->data);

    }


    public function index_BKUP() {

        $this->nextPaymentDate = '-';
        $this->previousPaymentDate = '-';
        $this->stripeSettings = StripeSetting::first();
        $this->subscription = Subscription::where('company_id', company()->id)->first();
        $this->razorPaySubscription = RazorpaySubscription::where('company_id', company()->id)->orderBy('id', 'Desc')->first();

        $stripe = DB::table("stripe_invoices")
            ->join('packages', 'packages.id', 'stripe_invoices.package_id')
            ->selectRaw('stripe_invoices.id , "Stripe" as method, stripe_invoices.pay_date as paid_on, "" as end_on ,stripe_invoices.next_pay_date, stripe_invoices.created_at')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', company()->id);

        $razorpay = DB::table("razorpay_invoices")
            ->join('packages', 'packages.id', 'razorpay_invoices.package_id')
            ->selectRaw('razorpay_invoices.id , "Razorpay" as method, razorpay_invoices.pay_date as paid_on, "" as end_on ,razorpay_invoices.next_pay_date, razorpay_invoices.created_at')
            ->whereNotNull('razorpay_invoices.pay_date')
            ->where('razorpay_invoices.company_id', company()->id);

        $allInvoices = DB::table("paypal_invoices")
            ->join('packages', 'packages.id', 'paypal_invoices.package_id')
            ->selectRaw('paypal_invoices.id, "Paypal" as method, paypal_invoices.paid_on, paypal_invoices.end_on,paypal_invoices.next_pay_date,paypal_invoices.created_at')
            ->where('paypal_invoices.status', 'paid')
            ->where('paypal_invoices.company_id', company()->id)
            ->union($stripe)
            ->union($razorpay)
            ->get();

        $this->firstInvoice = $allInvoices->sortByDesc(function ($temp, $key) {
            return Carbon::parse($temp->created_at)->getTimestamp();
        })->first();

        if($this->firstInvoice){
            if($this->firstInvoice->next_pay_date)
            {
                if($this->firstInvoice->method == 'Paypal'  && $this->firstInvoice !== null  &&  is_null($this->firstInvoice->end_on)){
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if($this->firstInvoice->method == 'Stripe' && $this->subscription !== null &&  is_null($this->subscription->ends_at)){
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
                if($this->firstInvoice->method == 'Razorpay' && $this->razorPaySubscription !== null &&  is_null($this->razorPaySubscription->ends_at)){
                    $this->nextPaymentDate = Carbon::parse($this->firstInvoice->next_pay_date)->toFormattedDateString();
                }
            }
            if($this->firstInvoice->paid_on)
            {
                $this->previousPaymentDate = Carbon::parse($this->firstInvoice->paid_on)-> toFormattedDateString();
            }
        }
        $this->paypalInvoice = PaypalInvoice::where('company_id', company()->id)->orderBy('created_at', 'desc')->first();

        return view('admin.billing.index', $this->data);

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function invoiceData()
    {
        $invoices = [];

        $zoho_customer_data = WceoZohoSubscriptions::getCustomerData(auth()->user()->id);
        $zoho_customer_id = ($zoho_customer_data)?$zoho_customer_data['customer_id']:'';
        $this->customer_details = $zoho_customer_data;

        $invoice_resp = WceoZohoSubscriptions::allInvoicesByCustomerId($zoho_customer_id);
        if($invoice_resp['status'] === 'success'){
            $invoices = $invoice_resp['data'];
        }

        return DataTables::of($invoices)
            ->editColumn('due_date', function ($row) {
                return Carbon::parse($row['due_date'])->format($this->global->date_format);
            })
            ->editColumn('amount', function ($row) {
                return $row['currency_symbol'].$row['total'];

            })
            ->editColumn('email', function ($row) {

                return $this->customer_details['email'];
            })
            ->editColumn('name', function ($row) {

                return $this->customer_details['email'];
            })
            ->editColumn('due_amount', function ($row) {
                return $row['currency_symbol'].$row['balance'];

            })

//            ->editColumn('invoice', function ($row) {
//                $invoice = '<a href="#" class="invoice-btn"><i class="fa fa-file"></i></a>';
//                return $invoice;
//
//            })

            ->rawColumns(['email', 'invoice'])
            ->make(true);



    }

    public function packages() {
        $this->packages = Package::where('default', 'no')->where('status','active')->get();
        $this->modulesData = Module::all();
        $this->stripeSettings = StripeSetting::first();
        $this->offlineMethods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->whereNull('company_id')->where('status', 'yes')->count();
        $this->pageTitle = 'app.menu.packages';

        return view('admin.billing.package', $this->data);
    }

    public function payment(PaymentRequest $request) {
//        dd($request->all());
        $this->setStripConfigs();
        $token = $request->payment_method;
        $email = $request->stripeEmail;
        $plan = Package::find($request->plan_id);


        $stripe = DB::table("stripe_invoices")
            ->join('packages', 'packages.id', 'stripe_invoices.package_id')
            ->selectRaw('stripe_invoices.id , "Stripe" as method, stripe_invoices.pay_date as paid_on ,stripe_invoices.next_pay_date')
            ->whereNotNull('stripe_invoices.pay_date')
            ->where('stripe_invoices.company_id', company()->id);

        $razorpay = DB::table("razorpay_invoices")
            ->join('packages', 'packages.id', 'razorpay_invoices.package_id')
            ->selectRaw('razorpay_invoices.id ,"Razorpay" as method, razorpay_invoices.pay_date as paid_on ,razorpay_invoices.next_pay_date')
            ->whereNotNull('razorpay_invoices.pay_date')
            ->where('razorpay_invoices.company_id', company()->id);

        $allInvoices = DB::table("paypal_invoices")
            ->join('packages', 'packages.id', 'paypal_invoices.package_id')
            ->selectRaw('paypal_invoices.id, "Paypal" as method, paypal_invoices.paid_on,paypal_invoices.next_pay_date')
            ->where('paypal_invoices.status', 'paid')
            ->whereNull('paypal_invoices.end_on')
            ->where('paypal_invoices.company_id', company()->id)
            ->union($stripe)
            ->union($razorpay)
            ->get();

        $firstInvoice = $allInvoices->sortByDesc(function ($temp, $key) {
            return Carbon::parse($temp->paid_on)->getTimestamp();
        })->first();

        $subcriptionCancel = true;

        if(!is_null($firstInvoice) && $firstInvoice->method == 'Paypal'){
            $subcriptionCancel = $this->cancelSubscriptionPaypal();
        }
        if(!is_null($firstInvoice) && $firstInvoice->method == 'Razorpay'){
            $subcriptionCancel = $this->cancelSubscriptionPaypal();
        }

        if($subcriptionCancel){
            if($plan->max_employees < $this->company->employees->count() ) {
                return back()->withError('You can\'t downgrade package because your employees length is '.$this->company->employees->count().' and package max employees lenght is '.$plan->max_employees)->withInput();
            }

            $company = $this->company;
            $subscription = $company->subscriptions;

            try {
                if ($subscription->count() > 0) {
                    $company->subscription('main')->swap($plan->{'stripe_' . $request->type . '_plan_id'});
                }
                else {
                    $company->newSubscription('main', $plan->{'stripe_'.$request->type.'_plan_id'})->create($token, [
                        'email' => $email,
                    ]);
                }

                $company = $this->company;

                $company->package_id = $plan->id;
                $company->package_type = $request->type;

                // Set company status active
                $company->status = 'active';
                $company->licence_expire_on = null;

                $company->save();

                //send superadmin notification
                $superAdmin = User::whereNull('company_id')->get();
                Notification::send($superAdmin, new CompanyUpdatedPlan($company, $plan->id));

//                \Session::put('message', 'Payment successfully done.');
                $request->session()->flash('message', 'Payment successfully done.');
//                return Reply::success('Payment successfully done.');
                return redirect(route('admin.billing'));

            } catch (\Exception $e) {
                return back()->withError($e->getMessage())->withInput();
            }
        }
//        return back()->withError('User not found by ID ' . $request->input('user_id'))->withInput();
    }

    public function download(Request $request, $invoiceId) {
        $this->setStripConfigs();

        return $this->company->downloadInvoice($invoiceId, [
            'vendor'  => $this->company->company_name,
            'product' => $this->company->package->name,
            'global' => GlobalSetting::first(),
            'logo' => $this->company->logo,
        ]);
    }

    public function cancelSubscriptionPaypal(){
        $credential = StripeSetting::first();
        $paypal_conf = Config::get('paypal');
        $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
        $api_context->setConfig($paypal_conf['settings']);

        $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
            ->where('company_id', company()->id)->where('status', 'paid')->first();

        if($paypalInvoice){
            $agreementId = $paypalInvoice->transaction_id;
            $agreement = new Agreement();

            $agreement->setId($agreementId);
            $agreementStateDescriptor = new AgreementStateDescriptor();
            $agreementStateDescriptor->setNote("Cancel the agreement");

            try {
                $agreement->cancel($agreementStateDescriptor, $api_context);
                $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                // Set subscription end date
                $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                $paypalInvoice->save();

            } catch (Exception $ex) {
                //\Session::put('error','Some error occur, sorry for inconvenient');
                return false;
            }

            return true;
        }
    }

    public function cancelSubscriptionRazorpay(){
        $credential = StripeSetting::first();
        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;
        $api       = new Api($apiKey, $secretKey);

        // Get subscription for unsubscribe
        $subscriptionData = RazorpaySubscription::where('company_id', company()->id)->whereNull('ends_at')->first();

        if($subscriptionData){
            try {
//                  $subscriptions = $api->subscription->all();
                $subscription  = $api->subscription->fetch($subscriptionData->subscription_id);
                if($subscription->status == 'active'){

                    // unsubscribe plan
                    $subData = $api->subscription->fetch($subscriptionData->subscription_id)->cancel(['cancel_at_cycle_end' => 0]);

                    // plan will be end on this date
                    $subscriptionData->ends_at = \Carbon\Carbon::createFromTimestamp($subData->end_at)->format('Y-m-d');
                    $subscriptionData->save();
                }

            } catch (Exception $ex) {
                return false;
            }
            return true;
        }
    }

    public function cancelSubscription(Request $request) {
        $type = $request->type;
        $credential = StripeSetting::first();
        if($type == 'paypal'){
            $paypal_conf = Config::get('paypal');
            $api_context = new ApiContext(new OAuthTokenCredential($credential->paypal_client_id, $credential->paypal_secret));
            $api_context->setConfig($paypal_conf['settings']);

            $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
                ->where('company_id', company()->id)->where('status', 'paid')->first();

            if($paypalInvoice){
                $agreementId = $paypalInvoice->transaction_id;
                $agreement = new Agreement(); $paypalInvoice = PaypalInvoice::whereNotNull('transaction_id')->whereNull('end_on')
                    ->where('company_id', company()->id)->where('status', 'paid')->first();

                $agreement->setId($agreementId);
                $agreementStateDescriptor = new AgreementStateDescriptor();
                $agreementStateDescriptor->setNote("Cancel the agreement");

                try {
                    $agreement->cancel($agreementStateDescriptor, $api_context);
                    $cancelAgreementDetails = Agreement::get($agreement->getId(), $api_context);

                    // Set subscription end date
                    $paypalInvoice->end_on = Carbon::parse($cancelAgreementDetails->agreement_details->final_payment_date)->format('Y-m-d H:i:s');
                    $paypalInvoice->save();
                } catch (Exception $ex) {
                    \Session::put('error','Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }

        }
        elseif($type == 'razorpay'){

            $apiKey    = $credential->razorpay_key;
            $secretKey = $credential->razorpay_secret;
            $api       = new Api($apiKey, $secretKey);

            // Get subscription for unsubscribe
            $subscriptionData = RazorpaySubscription::where('company_id', company()->id)->whereNull('ends_at')->first();
            if($subscriptionData){
                try {
//                  $subscriptions = $api->subscription->all();
                    $subscription  = $api->subscription->fetch($subscriptionData->subscription_id);
                    if($subscription->status == 'active'){

                        // unsubscribe plan
                        $subData = $api->subscription->fetch($subscriptionData->subscription_id)->cancel(['cancel_at_cycle_end' => 1]);

                        // plan will be end on this date
                        $subscriptionData->ends_at = \Carbon\Carbon::createFromTimestamp($subData->end_at)->format('Y-m-d');
                        $subscriptionData->save();
                    }

                } catch (Exception $ex) {
                    \Session::put('error','Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
                return Reply::redirectWithError(route('admin.billing.packages'), 'There is no data found for this subscription');
            }

        } else
            {
            $this->setStripConfigs();
            $company = company();
            $subscription = Subscription::where('company_id', company()->id)->whereNull('ends_at')->first();
            if($subscription){
                try {
                    $company->subscription('main')->cancel();
                } catch (Exception $ex) {
                    \Session::put('error','Some error occur, sorry for inconvenient');
                    return Redirect::route('admin.billing.packages');
                }
            }
        }

        return Reply::redirect(route('admin.billing'), __('messages.unsubscribeSuccess'));
    }

    public function selectPackage_BKUP(Request $request, $packageID) {
        $this->setStripConfigs();
        $this->package = Package::findOrFail($packageID);
        $this->company = company();
        $this->type    = $request->type;
        $this->stripeSettings = StripeSetting::first();
        $this->logo = $this->company->logo_url;

        $this->methods = OfflinePaymentMethod::withoutGlobalScope(CompanyScope::class)->where('status', 'yes')->whereNull('company_id')->get();
        return View::make('admin.billing.payment-method-show', $this->data);
    }

    public function selectPackage(Request $request, $packageID) {
        $this->setStripConfigs();
        $this->package = Package::findOrFail($packageID);
        $this->company = company();
        $this->amount = '-';
        $checkout_url = null;
        $this->cards = null;
        $zoho_customer_id = WceoZohoSubscriptions::getCustomerId(auth()->user()->id);
        if($this->package->max_employees < $this->company->employees->count() ) {
            $this->data['error'] = 'You can\'t downgrade package because your employees length is '.$this->company->employees->count().' and package max employees lenght is '.$this->package->max_employees;
        }else{
            $this->type    = $request->type;
            if($this->type == 'monthly'){
                $zoho_plan_id = WceoZohoSubscriptions::getMonthlyPlanCode($this->package->zoho_product_id);
                $checkout_url_resp = WceoZohoSubscriptions::getCheckoutUrl($zoho_plan_id, $zoho_customer_id);//'https://payments.pabbly.com/subscribe/'.$pubbly_plan_id.'/monthly';
                if($checkout_url_resp['status'] === 'success') {
                    $checkout_url = $checkout_url_resp['checkout_url'];
                }else{
                    $this->data['error'] = $checkout_url_resp['message'];
                }
            }else{
                $zoho_plan_id = WceoZohoSubscriptions::getYearlyPlanCode($this->package->zoho_product_id);
                $checkout_url_resp = WceoZohoSubscriptions::getCheckoutUrl($zoho_plan_id, $zoho_customer_id);//'https://payments.pabbly.com/subscribe/'.$pubbly_plan_id.'/yearly';
                if($checkout_url_resp['status'] === 'success'){
                    $checkout_url = $checkout_url_resp['checkout_url'];
                }else{
                    $this->data['error'] = $checkout_url_resp['message'];
                }
            }
            $this->logo = $this->company->logo_url;
            //******************** new code START *****************
            $this->zoho_plan = WceoZohoSubscriptions::getPlanDetails($zoho_plan_id);

            $zoho_subscription = DB::table("zoho_subscriptions")
                                    ->join('packages', 'packages.id', 'zoho_subscriptions.package_id')
                                    ->where('zoho_subscriptions.company_id', company()->id)
                                    ->orderByDesc('zoho_subscriptions.id')
                                    ->first();

            if($zoho_subscription)
            {
                if($zoho_subscription->subscription_id)
                {
                    $zohoSubscription = WceoZohoSubscriptions::getSubscriptionById($zoho_subscription->subscription_id);
                    if($zohoSubscription['status'] == 'success'){
                        $this->amount =  $zohoSubscription['data']['amount'];
                    }
                }
            }

//            $cards_resp = WceoZohoSubscriptions::listCardsByCustomerId($zoho_customer_id);
//            if($cards_resp['status'] === 'success'){
//                $this->cards = $cards_resp['data'];
//            }
            //******************** new code END *****************
        }
        /*$url_var = '';
        if($checkout_url){
            $user_extra = UserExtra::where('user_id',auth()->user()->id)->where('key_name','ZOHO_CUSTOMER_DATA')->first();
            $customer_data = json_decode($user_extra->key_value);
            $url_vars['cf_wceo_user_id'] = auth()->user()->id;
            $url_vars['first_name'] = $customer_data->first_name;
            $url_vars['last_name'] = $customer_data->last_name;
            $url_vars['email'] = $customer_data->email;
            $url_vars['company_name'] = $customer_data->company_name;
            $url_vars['phone'] = $customer_data->phone;
            $url_vars['mobile'] = $customer_data->mobile;
            $url_vars['website'] = $customer_data->website;
            $url_vars = array_filter($url_vars);
            foreach ($url_vars as $param=>$val){
                $url_params[] =$param.'='.urlencode($val);
            }

            $url_var = implode('&',$url_params);
            $checkout_url = $checkout_url.'?'.$url_var;
        }*/
        $this->data['checkout_url'] = $checkout_url;
        //return View::make('admin.billing.payment-method-show', $this->data);
        return view('admin.billing.payment-method-show', $this->data);
    }

    public function razorpayPayment(Request $request){
        $credential = StripeSetting::first();

        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;

        $paymentId = request('paymentId');
        $razorpaySignature = $request->razorpay_signature;
        $subscriptionId = $request->subscription_id;

        $api = new Api($apiKey, $secretKey);

        $plan = Package::with('currency')->find($request->plan_id);
        $type = $request->type;

        $expectedSignature = hash_hmac('sha256', $paymentId . '|' . $subscriptionId, $secretKey);

        if($expectedSignature === $razorpaySignature){
            if($plan->max_employees < $this->company->employees->count() ) {
                return back()->withError('You can\'t downgrade package because your employees length is '.$this->company->employees->count().' and package max employees lenght is '.$plan->max_employees)->withInput();
            }

           try {
                $api->payment->fetch($paymentId);

                $payment = $api->payment->fetch($paymentId); // Returns a particular payment

                if ($payment->status == 'authorized') {
                    //TODO::change INR into default currency code
                    $payment->capture(array('amount' => $payment->amount, 'currency' => $plan->currency->currency_code));
                }

                $company = $this->company;

                $company->package_id = $plan->id;
                $company->package_type = $type;

                // Set company status active
                $company->status = 'active';
                $company->licence_expire_on = null;

                $company->save();

                $subscription = new RazorpaySubscription();

                $subscription->subscription_id = $subscriptionId;
                $subscription->company_id      = company()->id;
                $subscription->razorpay_id     = $paymentId;
                $subscription->razorpay_plan   = $type;
                $subscription->quantity        = 1;
                $subscription->save();

                //send superadmin notification
                $superAdmin = User::whereNull('company_id')->get();
                Notification::send($superAdmin, new CompanyUpdatedPlan($company, $plan->id));

                return Reply::redirect(route('admin.billing'), 'Payment successfully done.');

            } catch (\Exception $e) {
                return back()->withError($e->getMessage())->withInput();
            }
        }
    }

    public function razorpaySubscription(Request $request){
        $credential = StripeSetting::first();

        $plan = Package::find($request->plan_id);
        $type =  $request->type;

        $planID = ($type == 'annual') ? $plan->razorpay_annual_plan_id : $plan->razorpay_monthly_plan_id;

        $apiKey    = $credential->razorpay_key;
        $secretKey = $credential->razorpay_secret;

        $api        = new Api($apiKey, $secretKey);
        $subscription  = $api->subscription->create(array('plan_id' => $planID, 'customer_notify' => 1, 'total_count' => 2));

        return Reply::dataOnly(['subscriprion' => $subscription->id]);
    }

    public function razorpayInvoiceDownload($id)
    {
        $this->invoice = RazorpayInvoice::with(['company','currency','package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('razorpay-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format).'-'.$this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function offlineInvoiceDownload($id)
    {
        $this->invoice = OfflineInvoice::with(['company','package'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('offline-invoice.invoice-1', $this->data);
        $filename = $this->invoice->pay_date->format($this->global->date_format).'-'.$this->invoice->next_pay_date->format($this->global->date_format);
        return $pdf->download($filename . '.pdf');
    }

    public function offlinePayment(Request $request)
    {
        $this->package_id = $request->package_id;
        $this->offlineId = $request->offlineId;
        $this->type = $request->type;

        return \view('admin.billing.offline-payment', $this->data);
    }

    public function offlinePaymentSubmit(OfflinePaymentRequest $request)
    {
        $checkAlreadyRequest = OfflinePlanChange::where('company_id', company()->id)->where('status', 'pending')->first();

        if($checkAlreadyRequest) {
            return Reply::error('You have already raised a request.');
        }

        $package = Package::find($request->package_id);

        // create offline invoice
        $offlineInvoice = new OfflineInvoice();
        $offlineInvoice->package_id = $request->package_id;
        $offlineInvoice->package_type = $request->type;
        $offlineInvoice->offline_method_id = $request->offline_id;
        $offlineInvoice->amount = $request->type == 'monthly' ? $package->monthly_price : $package->annual_price;
        $offlineInvoice->pay_date = Carbon::now()->format('Y-m-d');
        $offlineInvoice->next_pay_date = $request->type == 'monthly' ? Carbon::now()->addMonth()->format('Y-m-d') : Carbon::now()->addYear()->format('Y-m-d');
        $offlineInvoice->save();

        // create offline plan change request
        $offlinePlanChange = new OfflinePlanChange();
        $offlinePlanChange->package_id = $request->package_id;
        $offlinePlanChange->package_type = $request->type;
        $offlinePlanChange->company_id = company()->id;
        $offlinePlanChange->invoice_id = $offlineInvoice->id;
        $offlinePlanChange->offline_method_id = $request->offline_id;
        $offlinePlanChange->description = $request->description;

        if (!\File::exists(public_path('user-uploads/offline-payment-files'))) {
            \File::makeDirectory(public_path('user-uploads/offline-payment-files'), 0775, true);
        }

        $request->slip->image->store('offline-payment-files/', $request->slip->hashName());

        $offlinePlanChange->file_name = $request->slip->hashName();
        $offlinePlanChange->save();

        return Reply::redirect(route('admin.billing'));
    }

    public function pubblyPayment(Request $request) {
        $pubbly_response = WceoPubbly::getCheckoutResponse($request->get('hostedpage'));
        if($pubbly_response['status'] === 'success'){
            $pubbly_data = $pubbly_response['data'];

           // if($pubbly_data['status'] === 'live'){
                $plan = Package::where('pubbly_product_id',$pubbly_data['product_id'])->first();

                $last_pubbly_subscription = DB::table("pubbly_subscriptions")
                    ->join('packages', 'packages.id', 'pubbly_subscriptions.package_id')
                    //->selectRaw('stripe_invoices.id , "Stripe" as method, stripe_invoices.pay_date as paid_on ,stripe_invoices.next_pay_date')
                    //->whereNotNull('stripe_invoices.pay_date')
                    ->where('pubbly_subscriptions.company_id', company()->id)
                    ->orderByDesc('pubbly_subscriptions.id')->first();

                $subcriptionCancel = true;


                if(!is_null($last_pubbly_subscription) && ($last_pubbly_subscription->subscription_id !== $pubbly_data['id'])){
                    $pubbly_cancel_sub_resp  = WceoPubbly::cancelSubscription($last_pubbly_subscription->subscription_id);
                    if($pubbly_cancel_sub_resp['status'] === 'success'){
                        //$last_pubbly_subscription->canceled_date = date('Y-m-d H:i:s');
                        //$last_pubbly_subscription->save();
                        $subcriptionCancel = true;
                    }else{
                        $subcriptionCancel = false;
                        $this->data['error'] = $pubbly_cancel_sub_resp['message'];
                    }
                }else if(!is_null($last_pubbly_subscription)){
                    $this->data['success'] = 'Already updated!';
                }

                if($subcriptionCancel && (is_null($last_pubbly_subscription) || $last_pubbly_subscription->subscription_id !== $pubbly_data['id'])){
                    if($plan->max_employees < $this->company->employees->count() ) {
                        return back()->withError('You can\'t downgrade package because your employees length is '.$this->company->employees->count().' and package max employees lenght is '.$plan->max_employees)->withInput();
                    }


                    $company = $this->company;

                    $company->package_id = $plan->id;
                    $company->package_type = ($pubbly_data['plan']['billing_period'] == 'm')?'monthly':'annual';

                    // Set company status active
                    $company->status = 'active';
                    $company->licence_expire_on = null;

                    $company->save();

                    $subscription = new PubblySubscription();

                    $subscription->company_id = company()->id;
                    $subscription->package_id = $plan->id;
                    $subscription->pubbly_customer_id = $pubbly_data['customer_id'];
                    $subscription->subscription_id = $pubbly_data['id'];
                    $subscription->plan_id = $pubbly_data['plan_id'];
                    $subscription->amount = $pubbly_data['amount'];
                    $subscription->currency_symbol = $pubbly_data['currency_symbol'];
                    $subscription->plan_name = $plan->name;
                    $subscription->status = $pubbly_data['status'];
                    $subscription->activation_date = $pubbly_data['activation_date'];
                    $subscription->next_billing_date = $pubbly_data['next_billing_date'];
                    $subscription->canceled_date = $pubbly_data['canceled_date'];
                    $subscription->more_details = json_encode($pubbly_data);

                    $subscription->save();
                    //send superadmin notification
                    $superAdmin = User::whereNull('company_id')->get();
                    Notification::send($superAdmin, new CompanyUpdatedPlan($company, $plan->id));

//                \Session::put('message', 'Payment successfully done.');
                    $request->session()->flash('message', 'Payment successfully done.');
                    $this->data['success'] = 'Payment successfully done.';
                    // return redirect(route('admin.billing'));

                }
//            }else{
//
//                $this->data['error'] = 'Plan Does not active';
//            }

        }else{
            $this->data['error'] = $pubbly_response['message'];

        }

        return View::make('admin.billing.pubbly-payment', $this->data);

    }

    public function invoices(){
        return view('admin.billing.invoices', $this->data);
    }
    public function transactions(){
        return view('admin.billing.transactions', $this->data);
    }

    public function transactionsData()
    {
        $transactions = [];

        $customer_data = WceoZohoSubscriptions::getCustomerData(auth()->user()->id);
        $customer_id = ($customer_data)?$customer_data['customer_id']:null;
        $transaction_resp = WceoZohoSubscriptions::allTransactionsByCustomerId($customer_id);
        if($transaction_resp['status'] === 'success'){
            $transactions = $transaction_resp['data'];
        }
        $this->customer_details = $customer_data;

        foreach ($transactions as $key=>$transaction){
            if($transaction['type'] != 'payment'){
                unset($transactions[$key]);
            }
        }
        return DataTables::of($transactions)
            ->editColumn('status', function ($row) {

                $status = '<div class="status-mark '.$row['status'].'"></div>';
                return $status;

            })
            ->editColumn('createdAt', function ($row) {
                return Carbon::parse($row['date'])->format($this->global->date_format);
            })
            ->editColumn('amount', function ($row) {
                return '$'.$row['amount'];
              //  $row['currency_symbol']
            })
            ->editColumn('email', function ($row) {

                return $this->customer_details['email'];
            })
            ->rawColumns(['email', 'status'])
            ->make(true);



    }

    public function paymentSettings(){
        $this->customer_data = [];
        $this->countries = Country::all(['id', 'name', 'iso_alpha2']);
        $this->customer_data = WceoZohoSubscriptions::getCustomerData(auth()->user()->id);


        return view('admin.billing.payment-settings', $this->data);
    }

    public function updateZohoBilling(ZohoAddressRequest $request){
        $customer_id = WceoZohoSubscriptions::getCustomerId(auth()->user()->id);

        $data['billing_address'] = [
            "attention" => $request->get('attention'),
            "street" => $request->get('street'),
            "street2" => $request->get('street2'),
            "city" => $request->get('city'),
            "state" => $request->get('state'),
            "zip" => $request->get('zip'),
            "country" => $request->get('country')
        ];
        if($customer_id){
            $customer_update_resp = WceoZohoSubscriptions::updateCustomerAddress($customer_id, $data);

            if($customer_update_resp['status'] === 'success'){
                return Reply::success('Billing Address Updated Successfully');
            }else{
                return Reply::error($customer_update_resp['message']);
            }

        }

    }

    public function updateZohoShipping(ZohoAddressRequest $request){
        $customer_id = WceoZohoSubscriptions::getCustomerId(auth()->user()->id);
        $data['shipping_address'] = [
            "attention" => $request->get('attention'),
            "street" => $request->get('street'),
            "street2" => $request->get('street2'),
            "city" => $request->get('city'),
            "state" => $request->get('state'),
            "zip" => $request->get('zip'),
            "country" => $request->get('country')
        ];
        if($customer_id){
            $customer_update_resp = WceoZohoSubscriptions::updateCustomerAddress($customer_id, $data);
            if($customer_update_resp['status'] === 'success'){
                return Reply::success('Shipping Address Updated Successfully');
            }else{
                return Reply::error($customer_update_resp['message']);
            }
        }

    }

    public function creditCardData()
    {
        $cards = [];
        $zoho_customer_id = '';
        $customer_data = WceoZohoSubscriptions::getCustomerData(auth()->user()->id);
        if($customer_data){
            $zoho_customer_id = $customer_data['customer_id'];
        }
        if($zoho_customer_id) {
            $cards_resp = WceoZohoSubscriptions::listCardsByCustomerId($zoho_customer_id);

            if($cards_resp['status'] === 'success'){
                $cards = $cards_resp['data'];
            }
         }


        return DataTables::of($cards)
            ->editColumn('card_num', function ($row) {
                return 'XXXX-XXXX-XXXX-'.$row['last_four_digits'];
            })
            ->editColumn('exp_date', function ($row) {
                return $row['expiry_month'].'/'.$row['expiry_year'];
            })
            ->addColumn('action', function ($row) {
                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <a href="javascript:;" class="viewZohoCard" data-card-id="' . $row['card_id'] . '"><i class="fa fa-eye" aria-hidden="true"></i> ' . trans('app.view') . '</a>
                                <a href="javascript:;" class="delZohoCard" data-card-id="' . $row['card_id'] . '" ><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>                         
                            </div>
                        </div>
                </div>';

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);



    }

    public function zohoCardDetails($card_id){
        $customer_id = WceoZohoSubscriptions::getCustomerId(auth()->user()->id);
        $card_details_resp= WceoZohoSubscriptions::getCardDetails($customer_id,$card_id);
        if($card_details_resp['status'] === 'success') {
            $this->data['card_details'] = $card_details_resp['data'];
        }else{
            $this->data['error'] = $card_details_resp['message'];
        }
        return View::make('admin.billing.card-details', $this->data);
    }

    public function delZohoCard(Request $request){
        $customer_id = WceoZohoSubscriptions::getCustomerId(auth()->user()->id);
        $card_details_resp= WceoZohoSubscriptions::deleteCard($customer_id,$request->id);
        return Reply::dataOnly(['status' => $card_details_resp['status'], 'message' => $card_details_resp['message']]);

    }




    public function savedCardData()
    {
        $transactions = [];
        
            return DataTables::of($transactions)
                ->addColumn('action', function ($row) {

                $action = '
                <div class="dropdown-basic">
                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-sm btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                              <a href="javascript:;" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a>
                        </div>
                    </div>
                </div>';

                return $action;

            })
                
                ->editColumn('email', function ($row) {

                    return 'xxxx-xxxx-xxxx-4242';
                })
                

                ->rawColumns(['email', 'action'])
                ->make(true);


    }



    public function zohoPayment(Request $request) {

        $zoho_response = WceoZohoSubscriptions::getCheckoutResponse($request->get('hostedpage_id'));
        if($zoho_response['status'] === 'success'){
            $zoho_data = $zoho_response['data'];

            // if($pubbly_data['status'] === 'live'){
            $plan = Package::where('zoho_product_id',$zoho_data['subscription']['product_id'])->first();

            $last_zoho_subscription = DB::table("zoho_subscriptions")
                ->join('packages', 'packages.id', 'zoho_subscriptions.package_id')
                ->where('zoho_subscriptions.company_id', company()->id)
                ->orderByDesc('zoho_subscriptions.id')->first();

            $subcriptionCancel = true;


            if(!is_null($last_zoho_subscription) && ($last_zoho_subscription->subscription_id !== $zoho_data['subscription']['subscription_id'])){
                $zoho_cancel_sub_resp  = WceoZohoSubscriptions::cancelSubscription($last_zoho_subscription->subscription_id);
                if($zoho_cancel_sub_resp['status'] === 'success'){
                    $subcriptionCancel = true;
                }else{
                    $subcriptionCancel = false;
                    $this->data['error'] = $zoho_cancel_sub_resp['message'];
                }
            }else if(!is_null($last_zoho_subscription)){
                $this->data['success'] = 'Already updated!';
            }

           // if($subcriptionCancel && (is_null($last_zoho_subscription) || $last_zoho_subscription->subscription_id !== $zoho_data['subscription']['subscription_id'])){
           if((is_null($last_zoho_subscription) || $last_zoho_subscription->subscription_id !== $zoho_data['subscription']['subscription_id'])){
//                if($plan->max_employees < $this->company->employees->count() ) {
//                    return back()->withError('You can\'t downgrade package because your employees length is '.$this->company->employees->count().' and package max employees lenght is '.$plan->max_employees)->withInput();
//                }


                $company = $this->company;

                $company->package_id = $plan->id;
                $company->package_type = ($zoho_data['subscription']['interval_unit'] == 'months')?'monthly':'annual';

                // Set company status active
                $company->status = 'active';
                $company->licence_expire_on = null;

                $company->save();

                $subscription = new ZohoSubscription();

                $subscription->company_id = $company->id;
                $subscription->package_id = $plan->id;
                $subscription->zoho_customer_id = $zoho_data['subscription']['customer']['customer_id'];
                $subscription->subscription_id = $zoho_data['subscription']['subscription_id'];
                $subscription->amount = $zoho_data['subscription']['amount'];
                $subscription->currency_symbol = $zoho_data['subscription']['currency_symbol'];
                $subscription->plan_code = $zoho_data['subscription']['plan']['plan_code'];
                $subscription->plan_name = $plan->name;
                $subscription->status = $zoho_data['subscription']['status'];
                $subscription->activation_date = $zoho_data['subscription']['activated_at'];
                $subscription->next_billing_date = $zoho_data['subscription']['next_billing_at'];
                $subscription->more_details = json_encode($zoho_data['subscription']);

                $subscription->save();
                //send superadmin notification
                $superAdmin = User::whereNull('company_id')->get();
                Notification::send($superAdmin, new CompanyUpdatedPlan($company, $plan->id));

//                \Session::put('message', 'Payment successfully done.');
//                \Session::put('success', true);
//                $request->session()->flash('success', true);
//                $request->session()->flash('message', 'Payment successfully done.');
                $this->data['success'] = 'Payment successfully done.';
                // return redirect(route('admin.billing'));

            }


        }else{
            //$request->session()->flash('success', false);
           // $request->session()->flash('message', $zoho_response['message']);

//            \Session::put('message', $zoho_response['message']);
//            \Session::put('success', false);
            $this->data['error'] = $zoho_response['message'];

        }

        if(isset($this->data['error']) && $this->data['error'] != ''){
            \Session::put('message', $this->data['error']);
            \Session::put('success', false);
        }elseif(isset($this->data['success']) && $this->data['success'] != ''){
            \Session::put('message', $this->data['success']);
            \Session::put('success', true);
        }

        return redirect(route('admin.billing'));
//        return View::make('admin.billing.pubbly-payment', $this->data);

    }


}
