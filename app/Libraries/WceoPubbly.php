<?php


namespace App\Libraries;


use App\Libraries\Pubbly\CheckoutPage;
use App\Libraries\Pubbly\ClientPortal;
use App\Libraries\Pubbly\Customers;
use App\Libraries\Pubbly\Invoice;
use App\Libraries\Pubbly\PaymentMethods;
use App\Libraries\Pubbly\Plans;
use App\Libraries\Pubbly\Products;
use App\Libraries\Pubbly\Subscriptions;
use App\Libraries\Pubbly\Transactions;
use App\UserExtra;
use Illuminate\Support\Facades\DB;

class WceoPubbly
{
    public static function createWceoTemplate($data){
        //create pubbly product with annual and monthly package
        $pubbly_product = new Products();
        $product_data = ['product_name'=>$data['name'],
                         'description'=>$data['description'],
                        'redirect_url'=>route('admin.billing.pubbly-payment')];
        //create pubbly product
        $pubbly_product_resp = $pubbly_product->createProduct($product_data);

        $pubbly_product_details = (isset($pubbly_product_resp[0]['data']))?$pubbly_product_resp[0]['data']:[];
        if($pubbly_product_resp[0]['status'] == 'success' && isset($pubbly_product_details['id'])){
            // create plans
            $pubbly_plan = new Plans();
            $monthly_plan_data = ['product_id' => $pubbly_product_details['id'],
                          'plan_name' => 'Monthly',
                          'plan_code' => 'monthly',
                          'billing_cycle' => 'lifetime',
                          'price' => $data['monthly_price'],
                          'billing_period' => 'm',
                          'billing_period_num' => '1',
                          'plan_active' => 'false',
                          'trial_period' => $data['trial_days'],
                          'redirect_url' => route('admin.billing.pubbly-payment')
                          ];
            $yearly_plan_data = ['product_id' => $pubbly_product_details['id'],
                'plan_name' => 'Yearly',
                'plan_code' => 'yearly',
                'billing_cycle' => 'lifetime',
                'price' => $data['annual_price'],
                'billing_period' => 'y',
                'billing_period_num' => '1',
                'plan_active' => 'false',
                'trial_period' => $data['trial_days'],
                'redirect_url' => route('admin.billing')
            ];
            $monthly_plan_resp = $pubbly_plan->createPlan($monthly_plan_data);
            $yearly_plan_resp = $pubbly_plan->createPlan($yearly_plan_data);
            if($monthly_plan_resp[0]['status'] == 'success' && $yearly_plan_resp[0]['status'] == 'success'){
                $monthly_plan_details = $monthly_plan_resp[0]['data'];
                $yearly_plan_details = $yearly_plan_resp[0]['data'];
                return [
                        'status' => 'success',
                        'pubbly_product_id'     => $pubbly_product_details['id'],
                        'pubbly_monthly_plan_id'=> $monthly_plan_details['id'],
                        'pubbly_yearly_plan_id' => $yearly_plan_details['id'],
                       ];
            }else{
                return [
                    'status' => 'error',
                    'message'=> $monthly_plan_resp[0]['message']
                ];
            }
        }else{
            return [
                'status' => 'error',
                'message'=> $pubbly_product_resp[0]['message']
            ];
        }

    }

    public static function updateWceoTemplate($package, $data){
        $pubbly_product = new Products();
        //update pupply product
        $product_data = ['product_name'=>$data['name'],
            'description'=>$data['description'],
            'redirect_url'=>route('admin.billing.pubbly-payment')];
        //update pubbly product
        $pubbly_product_resp = $pubbly_product->updateProduct($package->pubbly_product_id,$product_data);

        $pubbly_plan = new Plans();
        $monthly_plan_data = [
            'product_id' => $package->pubbly_product_id,
            'plan_name' => 'Monthly',
            'plan_code' => 'monthly',
            'billing_cycle' => 'lifetime',
            'price' => $data['monthly_price'],
            'billing_period' => 'm',
            'billing_period_num' => '1',
            'trial_period' => $data['trial_days'],
            'plan_active' => ($package->status === 'active')?'true':'false',
            'redirect_url' => route('admin.billing.pubbly-payment')
        ];
        $yearly_plan_data = [
            'product_id' => $package->pubbly_product_id,
            'plan_name' => 'Yearly',
            'plan_code' => 'yearly',
            'billing_cycle' => 'lifetime',
            'price' => $data['annual_price'],
            'billing_period' => 'y',
            'billing_period_num' => '1',
            'trial_period' => $data['trial_days'],
            'plan_active' => ($package->status === 'active')?'true':'false',
            'redirect_url' => route('admin.billing.pubbly-payment')
        ];
        $monthly_plan_resp = $pubbly_plan->updatePlan($package->pubbly_monthly_plan_id, $monthly_plan_data);
        $yearly_plan_resp = $pubbly_plan->updatePlan($package->pubbly_yearly_plan_id, $yearly_plan_data);
        if($monthly_plan_resp[0]['status'] == 'success' && $yearly_plan_resp[0]['status'] == 'success'){
            $monthly_plan_details = $monthly_plan_resp[0]['data'];
            $yearly_plan_details = $yearly_plan_resp[0]['data'];
            return [
                'status' => 'success',
                'pubbly_monthly_plan_id'=> $monthly_plan_details['id'],
                'pubbly_yearly_plan_id' => $yearly_plan_details['id'],
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $monthly_plan_resp[0]['message']
            ];
        }

    }

    public static function activateWceoTemplate($package){
        $pubbly_plan = new Plans();
        $monthly_plan_data = [
            'product_id' => $package->pubbly_product_id,
            'plan_name' => 'Monthly',
            'plan_code' => 'monthly',
            'billing_cycle' => 'lifetime',
            'price' => $package->monthly_price,
            'billing_period' => 'm',
            'billing_period_num' => '1',
            'plan_active' => 'true',
            'redirect_url' => route('admin.billing.pubbly-payment')
        ];
        $yearly_plan_data = [
            'product_id' => $package->pubbly_product_id,
            'plan_name' => 'Yearly',
            'plan_code' => 'yearly',
            'billing_cycle' => 'lifetime',
            'price' => $package->annual_price,
            'billing_period' => 'y',
            'billing_period_num' => '1',
            'plan_active' => 'true',
            'redirect_url' => route('admin.billing.pubbly-payment')
        ];
        $monthly_plan_resp = $pubbly_plan->updatePlan($package->pubbly_monthly_plan_id, $monthly_plan_data);
        $yearly_plan_resp = $pubbly_plan->updatePlan($package->pubbly_yearly_plan_id, $yearly_plan_data);
        if($monthly_plan_resp[0]['status'] == 'success' && $yearly_plan_resp[0]['status'] == 'success'){
            $monthly_plan_details = $monthly_plan_resp[0]['data'];
            $yearly_plan_details = $yearly_plan_resp[0]['data'];
            return [
                'status' => 'success',
                'pubbly_monthly_plan_id'=> $monthly_plan_details['id'],
                'pubbly_yearly_plan_id' => $yearly_plan_details['id'],
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $monthly_plan_resp[0]['message']
            ];
        }

    }

    public static function inactivateWceoTemplate($package){
        //change product name
        $pubbly_product = new Products();
        $pubbly_product_data = [
            'product_name' => $package->name.'_'.time()
        ];
        $pubbly_product_resp = $pubbly_product->updateProduct($package->pubbly_product_id,$pubbly_product_data);
        // inactivate all plans
        $pubbly_plan = new Plans();
        $monthly_plan_data = [
            'product_id' => $package->pubbly_product_id,
            'plan_name' => 'Monthly',
            'plan_code' => 'monthly',
            'billing_cycle' => 'lifetime',
            'price' => $package->monthly_price,
            'billing_period' => 'm',
            'billing_period_num' => '1',
            'plan_active' => 'false',
            'redirect_url' => route('admin.billing')
        ];
        $yearly_plan_data = [
            'product_id' => $package->pubbly_product_id,
            'plan_name' => 'Yearly',
            'plan_code' => 'yearly',
            'billing_cycle' => 'lifetime',
            'price' => $package->annual_price,
            'billing_period' => 'y',
            'billing_period_num' => '1',
            'plan_active' => 'false',
            'redirect_url' => route('admin.billing')
        ];
        $monthly_plan_resp = $pubbly_plan->updatePlan($package->pubbly_monthly_plan_id, $monthly_plan_data);
        $yearly_plan_resp = $pubbly_plan->updatePlan($package->pubbly_yearly_plan_id, $yearly_plan_data);
        if($monthly_plan_resp[0]['status'] == 'success' && $yearly_plan_resp[0]['status'] == 'success'){
            $monthly_plan_details = $monthly_plan_resp[0]['data'];
            $yearly_plan_details = $yearly_plan_resp[0]['data'];
            return [
                'status' => 'success',
                'pubbly_monthly_plan_id'=> $monthly_plan_details['id'],
                'pubbly_yearly_plan_id' => $yearly_plan_details['id'],
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $monthly_plan_resp[0]['message']
            ];
        }

    }

    public static function getCheckoutUrl($plan_id){
        $pubbly_plan = new Plans();
        $plan_resp = $pubbly_plan->getSinglePlan($plan_id);
        if($plan_resp[0]['status'] == 'success') {
            $plan_details = $plan_resp[0]['data'];
            return [
                'status' => 'success',
                'checkout_url'=> 'https://payments.pabbly.com/subscribe/'.$plan_details['id'].'/'.$plan_details['plan_code']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $plan_resp[0]['message']
            ];
        }
    }

    public static function getCheckoutResponse($hostedpage){
        $pubbly_checkout = new CheckoutPage();
        $checkout_data = ['hostedpage'=>$hostedpage];
        //create pubbly product
        $checkout_resp = $pubbly_checkout->verifyHostedPage($checkout_data);
        if($checkout_resp[0]['status'] == 'success') {
            $checkout_details = $checkout_resp[0]['data'];
            return [
                'status' => 'success',
                'data'=> $checkout_details
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $checkout_resp[0]['message']
            ];
        }
    }

    public static function cancelSubscription($subscription_id){
        $pubbly_subs = new Subscriptions();
        $subs_data = ['cancel_at_end'=>true];
        $subs_resp = $pubbly_subs->cancelSubscription($subscription_id,$subs_data);
        if($subs_resp[0]['status'] == 'success') {
            return $subs_resp[0];
        }else{
            return [
                'status' => 'error',
                'message'=> $subs_resp[0]['message']
            ];
        }
    }

    public static function getSubscriptionById($subscription_id){
        $pubbly_subs = new Subscriptions();
        $subs_resp = $pubbly_subs->getSingleSubscription($subscription_id);
        if($subs_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $subs_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $subs_resp[0]['message']
            ];
        }
    }

    public static function allInvoicesByCustomerId($customer_id){
        $pubbly_invoice = new Invoice();
        $invoice_resp = $pubbly_invoice->listAllInvoicesByCustomerId($customer_id);
        if($invoice_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $invoice_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $invoice_resp[0]['message']
            ];
        }
    }

    public static function allTransactionsByCustomerId($customer_id){
        $pubbly_transactions = new Transactions();
        $transactions_resp = $pubbly_transactions->listAllTransactionsByCustomerId($customer_id);
        if($transactions_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $transactions_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $transactions_resp[0]['message']
            ];
        }
    }

    public static function createCustomer($data){
        $pubbly_customer = new Customers();
        $customer_data = ['first_name' => $data->first_name,
                          'last_name' => ($data->last_name)??'-',
                          'email_id' => $data->email,
                          'phone'=>(isset($data->mobile))?$data->mobile:'',
                          'is_affiliate' => 'false'
                         ];

        $customer_resp = $pubbly_customer->createCustomer($customer_data);
        if($customer_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }

    }

    public static function createClientPortal($pubbly_customer_id ){
        $pubbly_client_portal = new ClientPortal();
        $client_portal_data = ['customer_id' => $pubbly_customer_id];
        $client_portal_resp = $pubbly_client_portal->createClientPortal($client_portal_data);
        if($client_portal_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $client_portal_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $client_portal_resp[0]['message']
            ];
        }
    }

    public static function getCustomer($pubbly_customer_id ){
        $pubbly_customer = new Customers();
        $customer_resp = $pubbly_customer->getSingleCustomer($pubbly_customer_id);
        if($customer_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }

    }

    public static function updateCustomer($pubbly_customer_id,$data){
        $pubbly_customer = new Customers();
        $customer_resp = $pubbly_customer->updateCustomer($pubbly_customer_id,$data);
        if($customer_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }

    }

    public static function addCreditCard($customer_id, $cc_data){
        $pubbly_card = new PaymentMethods();
        $card_data = ["gateway_type" => "stripe",
                      "first_name" => $cc_data->first_name,
                      "last_name" => $cc_data->last_name,
                      "email" => $cc_data->first_name,
                      "card_number" => $cc_data->card_number,
                      "month" => $cc_data->month,
                      "year" => $cc_data->year,
                      "cvv" => $cc_data->cvv,
                      "street" =>  (isset($cc_data->street))?$cc_data->street:'',
                      "city" => (isset($cc_data->city))?$cc_data->city:'',
                      "state" => (isset($cc_data->state))?$cc_data->state:'',
                      "zip_code" => (isset($cc_data->zip_code))?$cc_data->zip_code:'',
                      "country" => (isset($cc_data->country))?$cc_data->country:''
                    ];
        $card_resp = $pubbly_card->createPaymentMethod($customer_id,$card_data);
        if($card_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $card_resp[0]['message']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $card_resp[0]['message']
            ];
        }
    }

    public static function listCardsByCustomerId($customer_id){
        $pubbly_card = new PaymentMethods();
        $card_resp = $pubbly_card->listAllPaymentMethods($customer_id);
        if($card_resp[0]['status'] == 'success') {
            return [
                'status' => 'success',
                'data'=> $card_resp[0]['data']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $card_resp[0]['message']
            ];
        }
    }

    public static function getPlanDetails($plan_id){
        $pubbly_plan = new Plans();
        $plan_resp = $pubbly_plan->getSinglePlan($plan_id);
        if($plan_resp[0]['status'] == 'success') {
            $plan_details = $plan_resp[0]['data'];
            return [
                'status' => 'success',
                'data'=> $plan_details
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $plan_resp[0]['message']
            ];
        }
    }

    public static function getCustomerId(){
        $customer_id = false;
        $pubbly_subscription = DB::table("pubbly_subscriptions")
            ->join('packages', 'packages.id', 'pubbly_subscriptions.package_id')
            ->where('pubbly_subscriptions.company_id', company()->id)
            ->orderByDesc('pubbly_subscriptions.id')->first();

        if($pubbly_subscription && $pubbly_subscription->pubbly_customer_id) {
            $customer_id = $pubbly_subscription->pubbly_customer_id;
        }else{
            $customer_data = UserExtra::where('user_id',auth()->user()->id)->where('key_name','PUBBLY_CUSTOMER_DATA')->first();
            if($customer_data){
                $customer_data = json_decode($customer_data->key_value, true);
                $customer_id = $customer_data['id'];
            }
        }

        return $customer_id;
    }


}