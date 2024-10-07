<?php
namespace App\Http\Controllers\Client;

use App\ClientPayment;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Invoice;
use App\Payment;
use App\RoleUser;
use App\PaymentGatewayCredentials;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Subscription;
use Validator;
use URL;
use Session;
use Redirect;
use Auth;

use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'Stripe';
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentWithStripe(Request $request, $invoiceId)
    {
        $redirectRoute = 'client.invoices.show';
        $id = $invoiceId;

        if($id == 0){
            return $this->makeStripeAddPayment($request, $invoiceId, $redirectRoute, $id);
        } else {
            return $this->makeStripePayment($request, $invoiceId, $redirectRoute, $id);
        }

    }

    public function paymentWithStripePublic(Request $request, $invoiceId)
    {
        $redirectRoute = 'front.invoice';
        $id = md5($invoiceId);

        return $this->makeStripePayment($request, $invoiceId, $redirectRoute, $id);
    }

    private function makeStripePayment($request, $invoiceId, $redirectRoute, $id)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $stripeCredentials = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
                                                      ->where('company_id', $invoice->company_id)
                                                      ->first();

        /** setup Stripe credentials **/
        Stripe::setApiKey($stripeCredentials->stripe_secret);

        $tokenObject  = $request->get('token');
        $token  = $tokenObject['id'];
        $email  = $tokenObject['email'];

        $userID = auth()->user()->id;
        $userRole = RoleUser::where('user_id', $userID)
                            ->join('roles', 'roles.id', '=', 'role_user.role_id')
                            ->pluck('roles.name')->first();

        if($invoice->recurring == 'no')
        {
            try {
                $customer = Customer::create(array(
                    'email' => $email,
                    'source'  => $token
                ));

                $charge = Charge::create(array(
                    'customer' => $customer->id,
                    'amount'   => $invoice->total*100,
                    'currency' => $invoice->currency->currency_code
                ));

            } catch (\Exception $ex) {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Reply::redirect(route($redirectRoute, $id), 'Payment fail');
            }

            $payment = new Payment();
            $payment->project_id = $invoice->project_id;
            $payment->invoice_id = $invoice->id;
            $payment->company_id = $invoice->company_id;
            $payment->currency_id = $invoice->currency_id;
            $payment->amount = $invoice->total;
            $payment->gateway = 'Stripe';
            $payment->transaction_id = $charge->id;
            $payment->paid_on = Carbon::now();
            $payment->user_id  = $userID;
            $payment->added_by = $userRole;
            $payment->status = 'complete';
            $payment->save();

        } else {

            $plan = Plan::create(array(
                "amount" => $invoice->total*100,
                "currency" => $invoice->currency->currency_code,
                "interval" => $invoice->billing_frequency,
                "product" => ['name' => $invoice->invoice_number],
                "id" => 'plan-'.$invoice->id.'-'.str_random('10'),
                "interval_count" => $invoice->billing_interval,
                "metadata" => [
                    "invoice_id" => $invoice->id
                ],
            ));

            try {

                $customer = Customer::create(array(
                    'email' => $email,
                    'source'  => $token
                ));

                $subscription = Subscription::create(array(
                    "customer" => $customer->id,
                    "items" => array(
                        array(
                            "plan" => $plan->id,
                        ),
                    ),
                    "metadata" => [
                        "invoice_id" => $invoice->id
                    ],
                ));

            } catch (\Exception $ex) {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Reply::redirect(route($redirectRoute, $id), 'Payment fail');
            }

            // Save details in database
            $payment = new Payment();
            $payment->project_id = $invoice->project_id;
            $payment->invoice_id = $invoice->id;
            $payment->company_id = $invoice->company_id;
            $payment->currency_id = $invoice->currency_id;
            $payment->amount = $invoice->total;
            $payment->gateway = 'Stripe';
            $payment->plan_id = $plan->id;
            $payment->transaction_id = $subscription->id;
            $payment->paid_on = Carbon::now();
            $payment->user_id  = $userID;
            $payment->added_by = $userRole;
            $payment->status = 'complete';
            $payment->save();
        }

        $invoice->status = 'paid';
        $invoice->save();

        \Session::put('success','Payment success');
        if($userRole == 'employee'){
            return Reply::redirect(route('member.projects.show', $invoice->project_id));
        } else {
            return Reply::redirect(route($redirectRoute, $id), 'Payment success');
        }
        
    }


    //Add Payments Method
    private function makeStripeAddPayment($request, $redirectRoute, $id)
    {

        $company_id   = $request->get('company_id');
        $stripeCredentials = PaymentGatewayCredentials::withoutGlobalScope(CompanyScope::class)
                                                      ->where('company_id', $company_id)
                                                      ->first();

        /** setup Stripe credentials **/
        Stripe::setApiKey($stripeCredentials->stripe_secret);

        $tokenObject  = $request->get('token');
        $token        = $tokenObject['id'];
        $email        = $tokenObject['email'];
        $amount       = $request->get('amount');
        $project_id   = $request->get('project_id');
        $currency_id  = $request->get('currency_id');
        

        $userID = auth()->user()->id;
        $userRole = RoleUser::where('user_id', $userID)
                            ->join('roles', 'roles.id', '=', 'role_user.role_id')
                            ->pluck('roles.name')->first();
       
            try {
                $customer = Customer::create(array(
                    'email' => $email,
                    'source'  => $token
                ));

                $charge = Charge::create(array(
                    'customer' => $customer->id,
                    'amount'   => $amount*100,
                    'currency' => 'USD'
                ));

            } catch (\Exception $ex) {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Reply::redirect(route($redirectRoute, $id), 'Payment fail');
            }

            $payment = new Payment();
            $payment->project_id = $project_id;
            $payment->company_id = $company_id;
            $payment->currency_id = $currency_id;
            $payment->amount = $amount;
            $payment->gateway = 'Stripe';
            $payment->transaction_id = $charge->id;
            $payment->paid_on = Carbon::now();
            $payment->user_id  = $userID;
            $payment->added_by = $userRole;
            $payment->status = 'complete';
            $payment->save();

        \Session::put('success','Payment success');
        return Reply::redirect(route('admin.payments.index'), 'Payment success');
        
    }
}
