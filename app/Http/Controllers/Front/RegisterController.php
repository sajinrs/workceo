<?php

namespace App\Http\Controllers\Front;

use App\Company;
use App\Currency;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Requests\Front\Register\StoreRequest;
use App\Libraries\WceoPubbly;
use App\Libraries\WceoZohoSubscriptions;
use App\Notifications\EmailVerification;
use App\Notifications\EmailVerificationSuccess;
use App\Notifications\NewCompanyRegister;
use App\Package;
use App\Role;
use App\User;
use App\UserExtra;
use App\ZohoSubscription;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\GlobalSetting;

class RegisterController extends FrontBaseController
{
    public function index() {
        $this->pageTitle = 'Sign Up';
        $this->messsage = '';
        $this->class = '';
        
        if($this->setting->front_design == 1){
            return view('saas.register', $this->data);
        }
        return view('front.register', $this->data);

    } 

    public function store(StoreRequest $request) {
        $is_success = true;
        if(!is_null($this->global->google_recaptcha_key))
        {
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);
            if(!$validateRecaptcha)
            {
                return Reply::error('Recaptcha not validated.');
            }
        }

        DB::beginTransaction();
        // Save company name
        $globalSetting = GlobalSetting::first();

        $company = new Company();
        $company->company_name  = $request->first_name;
        $company->company_email = $request->email;
        //$company->company_phone = $request->phone;
        $company->address       = 'Address';
        $company->timezone = $globalSetting->timezone;
        $company->save();

        $currency = Currency::where('company_id', $company->id)->first();
        $company->currency_id = $currency->id;
        $company->save();

        // Save Admin
        $user = new User();
        $user->company_id = $company->id;
        $user->name       = $request->first_name.' '.$request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->mobile     = $request->phone;
        $user->password   = bcrypt($request->password);
        $user->status     = 'active';
        $user->email_verification_code = str_random(40);
        $user->save();

        $employee = new EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->employee_id = 'emp-'.$user->id;
        $employee->company_id = $user->company_id;
        $employee->address = 'address';
        $employee->hourly_rate = '50';
        $employee->save();

        // create zoho customer
        $customer_data = new \stdClass();
        $customer_data->id = $user->id;
        $customer_data->first_name = $request->first_name;
        $customer_data->last_name  = $request->last_name;
        $customer_data->email      = $request->email;
        $customer_data->mobile     = $request->phone;
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
        if($is_success){
            if($globalSetting->email_verification == 1) {
                $user->notify(new EmailVerification($user,$request->password));
                $user->status = 'deactive';
                $user->save();
                $message =  __('messages.signUpThankYouVerify');
                //$message = __('messages.signUpThankYou').' <a href="'.route('login').'">Login Now</a>.';
            } else {
                $adminRole = Role::where('name', 'admin')->where('company_id', $user->company_id)->first();
                $user->roles()->attach($adminRole->id);

                $employeeRole = Role::where('name', 'employee')->where('company_id', $user->company_id)->first();
                $user->roles()->attach($employeeRole->id);
                $message = __('messages.signUpThankYou').' <a href="'.route('login').'">Login Now</a>.';
            }

            DB::commit();

            return Reply::success($message);
        }else{
            return Reply::error($error_msg);
        }

    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=> $this->global->google_recaptcha_secret,
                    'response'=> $googleRecaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        return $body->success;
    }

    public function getEmailVerification($code)
    {
        $this->pageTitle = __('modules.accountSettings.emailVerification');

        $user = User::where('email_verification_code', $code)->whereNotNull('email_verification_code')->withoutGlobalScope('active')->first();

        if($user) {
            $user->status = 'active';
            $user->email_verification_code = '';
            $user->save();

            $user->notify(new EmailVerificationSuccess($user));

            $adminRole = Role::where('name', 'admin')->where('company_id', $user->company_id)->first();
            $user->roles()->attach($adminRole->id);

            $employeeRole = Role::where('name', 'employee')->where('company_id', $user->company_id)->first();
            $user->roles()->attach($employeeRole->id);

            $this->messsage = 'Your have successfully verified your email address. You must click  <a href="'.route('login').'">Here</a> to login.';
            $this->class = 'success';
            //return view('saas.email-verification', $this->data);
            return view('saas.register', $this->data);


        } else {
            $this->messsage = 'Verification url doesn\'t exist. Click <a href="'.route('login').'">Here</a> to login.';
            $this->class = 'success';
            if($this->setting->front_design == 1){
                //return view('saas.email-verification', $this->data);
                return view('saas.register', $this->data);
            }
            //return view('front.email-verification', $this->data);
            return view('saas.register', $this->data);
        }

    }
}
