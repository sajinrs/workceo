<?php

namespace App\Http\Controllers;

use App\Company;
use App\Currency;
use App\EmployeeDetails;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Libraries\WceoPubbly;
use App\Libraries\WceoZohoSubscriptions;
use App\Package;
use App\Role;
use App\User;
use App\UserExtra;
use App\ZohoSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        if($provider === 'facebook'){
            return Socialite::driver($provider)->fields([
                'first_name', 'last_name', 'email'
            ])->scopes([
                'email'
            ])->redirect();
        }else{
            return Socialite::driver($provider)->redirect();
        }

    }

    public function Callback($provider, Request $request)
    {
        if (! $request->input('code')) {
            return redirect('login')->withErrors('Login failed: '.$request->input('error').' - '.$request->input('error_reason'));
        }
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $users = User::where(['email' => $userSocial->getEmail()])->first();
        if ($users) {
            Auth::login($users);
            return redirect($this->redirectTo());
        } else {
            $is_success = true;
            // new user
            DB::beginTransaction();
            // Save company name
            $globalSetting = GlobalSetting::first();

            $company = new Company();
            $company->company_name  = $userSocial->getName();
            $company->company_email = $userSocial->getEmail();
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
            $user->name       = $userSocial->getName();
            $user->first_name = $userSocial->getName();
            $user->last_name  = '';
            $user->email      = $userSocial->getEmail();
            $user->image = $userSocial->getAvatar();
            $user->provider_id = $userSocial->getId();
            $user->provider = $provider;
            $user->save();

            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
            $employee->employee_id = 'emp-'.$user->id;
            $employee->company_id = $user->company_id;
            $employee->address = 'address';
            $employee->hourly_rate = '50';
            $employee->save();

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
            if($is_success) {
                $adminRole = Role::where('name', 'admin')->where('company_id', $company->id)->withoutGlobalScope('active')->first();

                $adminRole = Role::where('name', 'admin')->where('company_id', $user->company_id)->first();
                $user->roles()->attach($adminRole->id);

                $employeeRole = Role::where('name', 'employee')->where('company_id', $user->company_id)->first();
                $user->roles()->attach($employeeRole->id);
                $message = __('messages.signUpThankYou') . ' <a href="' . route('login') . '">Login Now</a>.';

                DB::commit();

                if ($user) {
                    Auth::login($user);
                    return redirect()->route('admin.dashboard');
                }
                \Session::put('error','Login Failed');
                return redirect()->route('login');
            }else{
                \Session::put('error',$error_msg);
                return redirect()->route('login');
            }
        }
    }


    protected function redirectTo()
    {
        $user = auth()->user();
        if ($user->super_admin == '1') {
            return 'super-admin/dashboard';
        } elseif ($user->hasRole('admin')) {
            $user->company()->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return 'admin/dashboard';
        }

        if ($user->hasRole('employee')) {
            return 'member/dashboard';
        }

        if ($user->hasRole('client')) {
            return 'client/dashboard';
        }
    }

}
