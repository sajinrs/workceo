<?php


namespace App\Libraries;


use App\Libraries\ZohoSubscriptions\Cards;
use App\Libraries\ZohoSubscriptions\Customers;
use App\Libraries\ZohoSubscriptions\HostedPages;
use App\Libraries\ZohoSubscriptions\Invoices;
use App\Libraries\ZohoSubscriptions\Plans;
use App\Libraries\ZohoSubscriptions\Products;
use App\Libraries\ZohoSubscriptions\Subscriptions;
use App\Libraries\ZohoSubscriptions\ZohoSubscription;
use App\UserExtra;
use GuzzleHttp\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Config;
use SudiptoChoudhury\Zoho\Subscriptions\Api;

class WceoZohoSubscriptions
{

    public function __construct()
    {

    }

    public static function createWceoTemplate($data){
        //create zoho product with annual and monthly plan
        $zoho_product = new Products();
        $product_data = ['name'=>$data['name'],
            'description'=>$data['description'],
            'redirect_url'=>route('admin.billing.zoho-payment')
        ];

        //create zoho product
        $zoho_product_resp = $zoho_product->create($product_data);


        $zoho_product_details = (isset($zoho_product_resp[0]['product']))?$zoho_product_resp[0]['product']:[];
        if($zoho_product_resp[0]['code'] == 0 && isset($zoho_product_details['product_id'])) {
            // create plans
            $zoho_plan = new Plans();
            $monthly_plan_data = [
                'product_id'       => $zoho_product_details['product_id'],
                'name'        => 'Monthly',
                'plan_code'        => self::getMonthlyPlanCode($zoho_product_details['product_id']),//'monthly_plan_'.str_replace(' ','_',strtolower($zoho_product_details['name'])),
                'recurring_price'  => $data['monthly_price'],
                'interval'         => 1,
                'interval_unit'    =>  'months',
                'trial_period'     => $data['trial_days'],
                'redirect_url'     => route('admin.billing.zoho-payment')
            ];
            $yearly_plan_data = [
                'product_id' => $zoho_product_details['product_id'],
                'name' => 'Yearly',
                'plan_code' => self::getYearlyPlanCode($zoho_product_details['product_id']),//'yearly_plan_'.str_replace(' ','_',strtolower($zoho_product_details['name'])),
                'recurring_price'  => $data['annual_price'],
                'interval'         => 1,
                'interval_unit'    =>  'years',
                'trial_period' => $data['trial_days'],
                'redirect_url' => route('admin.billing.zoho-payment')
            ];

            $monthly_plan_resp = $zoho_plan->create($monthly_plan_data);
            $yearly_plan_resp = $zoho_plan->create($yearly_plan_data);
            if($monthly_plan_resp[0]['code'] == 0 && $yearly_plan_resp[0]['code'] == 0){
                $monthly_plan_details = $monthly_plan_resp[0]['plan'];
                $yearly_plan_details = $yearly_plan_resp[0]['plan'];
                //change product status inactive
                $zoho_product->markAsInactive($zoho_product_details['product_id']);
                $zoho_plan->markAsInactive($monthly_plan_details['plan_code']);
                $zoho_plan->markAsInactive($yearly_plan_details['plan_code']);
                return [
                    'status' => 'success',
                    'zoho_product_id' => $zoho_product_details['product_id']
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
                'message'=> $zoho_product_resp[0]['message']
            ];
        }
    }

    public static function updateWceoTemplate($package, $data){
        $zoho_product = new Products();
        //update zoho product
        $product_data = [
            'name'=>$data['name'],
            'description'=>$data['description'],
            'redirect_url'=>route('admin.billing.zoho-payment')
        ];
        //update zoho product
        $zoho_product_resp = $zoho_product->update($package->zoho_product_id,$product_data);
        $zoho_product_details = (isset($zoho_product_resp[0]['product']))?$zoho_product_resp[0]['product']:[];
        if($zoho_product_resp[0]['code'] == 0 && isset($zoho_product_details['product_id'])) {

            $zoho_plan = new Plans();
            $monthly_plan_data = [
                'product_id' => $package->zoho_product_id,
                'name' => 'Monthly',
                'plan_code' => self::getMonthlyPlanCode($package->zoho_product_id),//'monthly_plan_'.str_replace(' ','_',strtolower($zoho_product_details['name'])),
                'recurring_price' => $data['monthly_price'],
                'interval' => 1,
                'interval_unit' => 'months',
                'trial_period' => $data['trial_days'],
                'redirect_url' => route('admin.billing.zoho-payment')
            ];
            $yearly_plan_data = [
                'product_id' => $package->zoho_product_id,
                'name' => 'Yearly',
                'plan_code' => self::getYearlyPlanCode($package->zoho_product_id),//'yearly_plan_'.str_replace(' ','_',strtolower($zoho_product_details['name'])),
                'recurring_price' => $data['annual_price'],
                'interval' => 1,
                'interval_unit' => 'years',
                'trial_period' => $data['trial_days'],
                'redirect_url' => route('admin.billing.zoho-payment')
            ];
            $monthly_plan_resp = $zoho_plan->update(self::getMonthlyPlanCode($package->zoho_product_id), $monthly_plan_data);
            $yearly_plan_resp = $zoho_plan->update(self::getYearlyPlanCode($package->zoho_product_id), $yearly_plan_data);
            if($monthly_plan_resp[0]['code'] == 0 && $yearly_plan_resp[0]['code'] == 0){
                $monthly_plan_details = $monthly_plan_resp[0]['plan'];
                $yearly_plan_details = $yearly_plan_resp[0]['plan'];
                return [
                    'status' => 'success'
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
                'message'=> $zoho_product_resp[0]['message']
            ];
        }
    }

    public static function updateWceoDefaultTemplate($package, $data){
        $zoho_product = new Products();
        //update zoho product
        $product_data = [
            'name'=>$data['name'],
            'description'=>$data['description'],
            'redirect_url'=>route('admin.billing.zoho-payment')
        ];
        //update zoho product
        $zoho_product_resp = $zoho_product->update($package->zoho_product_id,$product_data);
        $zoho_product_details = (isset($zoho_product_resp[0]['product']))?$zoho_product_resp[0]['product']:[];
        if($zoho_product_resp[0]['code'] == 0 && isset($zoho_product_details['product_id'])) {

            $zoho_plan = new Plans();
            $trial_plan_data = [
                'trial_period' => $data['trial_days']
            ];
            $defaultPlanCode = Config::get('services.zoho.default_plan_code');
            $trial_plan_resp = $zoho_plan->update($defaultPlanCode, $trial_plan_data);
            if($trial_plan_resp[0]['code'] == 0 && $trial_plan_resp[0]['code'] == 0){
                $trial_plan_details = $trial_plan_resp[0]['plan'];
                return [
                    'status' => 'success'
                ];
            }else{
                return [
                    'status' => 'error',
                    'message'=> $trial_plan_resp[0]['message']
                ];
            }
        }else{
            return [
                'status' => 'error',
                'message'=> $zoho_product_resp[0]['message']
            ];
        }
    }

    public static function activateWceoTemplate($package){
        $zoho_product = new Products();
        $zoho_product_resp = $zoho_product->markAsActive($package->zoho_product_id);
        if($zoho_product_resp[0]['code'] == 0){
            $zoho_plan = new Plans();
            $monthly_plan_resp = $zoho_plan->markAsActive(self::getMonthlyPlanCode($package->zoho_product_id));
            $yearly_plan_resp = $zoho_plan->markAsActive(self::getYearlyPlanCode($package->zoho_product_id));
            if($monthly_plan_resp[0]['code'] == 0 && $yearly_plan_resp[0]['code'] == 0){
                return [
                    'status' => 'success'
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
                'message'=> $zoho_product_resp[0]['message']
            ];
        }
    }

    public static function inactivateWceoTemplate($package){

    }

    public static function getCheckoutUrl($plan_id, $customer_id){
        $zoho_hosted_page = new HostedPages();
        $hosted_page_data = [
            'customer_id' => $customer_id,
            'plan' => ['plan_code'=>$plan_id]
        ];
        $hosted_page_resp = $zoho_hosted_page->create($hosted_page_data);
        if($hosted_page_resp[0]['code'] == '0') {
            $hostedpage_details = $hosted_page_resp[0]['hostedpage'];
            return [
                'status' => 'success',
                'checkout_url'=> $hostedpage_details['url']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $hosted_page_resp[0]['message']
            ];
        }
    }

    public static function getCheckoutResponse($hostedpage_id){
        $zoho_hosted_page = new HostedPages();
        $hosted_page_resp = $zoho_hosted_page->retrieve($hostedpage_id);
        if($hosted_page_resp[0]['code'] == '0') {
            $hostedpage_details = $hosted_page_resp[0]['data'];
            return [
                'status' => 'success',
                'data'=> $hostedpage_details
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $hosted_page_resp[0]['message']
            ];
        }
    }

    public static function cancelSubscription($subscription_id){
        $zoho_subs = new Subscriptions();
        $subs_resp = $zoho_subs->cancel($subscription_id);
        if($subs_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $subs_resp[0]['subscription']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $subs_resp[0]['message']
            ];
        }
    }

    public static function cancelAllActiveSubscriptions($customer_id){
        // get all active subscriptions of customer
        $zoho_subs = new Subscriptions();
        $subs_resp = $zoho_subs->listByCustomer($customer_id,'ACTIVE');
        if($subs_resp[0]['code'] == '0') {
            $active_subs = $subs_resp[0]['subscriptions'];
            //cancel all active subscription
            foreach ($active_subs as $subscription){
                $cancel_subs_resp = $zoho_subs->cancel($subscription['subscription_id']);
                if($cancel_subs_resp[0]['code'] != '0') {
                    return [
                        'status' => 'error',
                        'message'=> $cancel_subs_resp[0]['message']
                    ];
                }
            }
            return [
                'status' => 'success',
                'message'=> 'Cancelled all Active Subscriptions'
            ];

        }else{
            return [
                'status' => 'error',
                'message'=> $subs_resp[0]['message']
            ];
        }

    }

    public static function makeCustomerInactive($customer_id){
        //change customer status to inactive
        $zoho_customer = new Customers();
        $customer_resp = $zoho_customer->markAsInactive($customer_id);
        if($customer_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['message']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }
    }

    public static function getSubscriptionById($subscription_id){
        $zoho_subs = new Subscriptions();
        $subs_resp = $zoho_subs->retrieve($subscription_id);
        if($subs_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $subs_resp[0]['subscription']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $subs_resp[0]['message']
            ];
        }
    }

    public static function allInvoicesByCustomerId($customer_id){
        $zoho_invoice = new Invoices();
        $invoice_resp = $zoho_invoice->listByCustomer($customer_id);
        if($invoice_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $invoice_resp[0]['invoices']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $invoice_resp[0]['message']
            ];
        }
    }

    public static function allTransactionsByCustomerId($customer_id){
        $zoho_transactions = new Customers();
        $transactions_resp = $zoho_transactions->transactions($customer_id);

        if($transactions_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $transactions_resp[0]['transactions']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $transactions_resp[0]['message']
            ];
        }
    }

    public static function createCustomer($data){
        $zoho_customer = new Customers();
        $customer_data = [
            'display_name' => $data->display_name,
            'first_name' => (isset($data->first_name))?$data->first_name:'',
            'last_name' => (isset($data->last_name))?$data->last_name:'',
            'email' => $data->email,
            'company_name' => $data->company_name,
            'mobile'=>(isset($data->mobile))?$data->mobile:'',
            'is_portal_enabled'=> false,
            'custom_fields' => [
                ['value'=> $data->id,
                 'label'=> 'wceo_user_id']
            ]
        ];

        $customer_resp = $zoho_customer->create($customer_data);
        if($customer_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['customer']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }
    }

    public static function assignTrialSubscription($customer_id,$plan_code=null){
        if(!$plan_code){
            $plan_code = Config::get('services.zoho.default_plan_code');
        }
        $zoho_plan = new Plans();
        $plan_resp = $zoho_plan->retrieve($plan_code);
        if($plan_resp[0]['code'] == '0') {
            $plan = $plan_resp[0]['plan'];
            unset($plan['url']);
        }else{
            return [
                'status' => 'error',
                'data'=> $plan_resp[0]['message']
            ];
        }

        $zoho_subscriptions = new Subscriptions();
        $subscription_data = [
            'customer_id' => $customer_id,
            'plan' => $plan
        ];

        $subscription_resp = $zoho_subscriptions->create($subscription_data);



        if($subscription_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $subscription_resp[0]['subscription']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $subscription_resp[0]['message']
            ];
        }
    }

    public static function createClientPortal($customer_id ){

    }

    public static function getCustomer($customer_id ){

    }

    public static function updateCustomer($customer_id,$data){
        $zoho_customer = new Customers();
        $customer_data = [
            'first_name' => (isset($data->first_name))?$data->first_name:'',
            'last_name' => (isset($data->last_name))?$data->last_name:'',
            'email' => $data->email,
            'company_name' => $data->company_name,
            'mobile'=>(isset($data->mobile))?$data->mobile:'',
            'is_portal_enabled'=> false,
            'custom_fields' => [
                ['value'=> $data->id,
                    'label'=> 'wceo_user_id']
            ]
        ];
        $customer_resp = $zoho_customer->update($customer_id,$customer_data);
        if($customer_resp[0]['code'] == 0) {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['customer']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }
    }

    public static function updateCustomerAddress($customer_id,$address_data){
        $zoho_customer = new Customers();
        $customer_resp = $zoho_customer->update($customer_id,$address_data);
        if($customer_resp[0]['code'] == 0) {
            return [
                'status' => 'success',
                'data'=> $customer_resp[0]['customer']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $customer_resp[0]['message']
            ];
        }
    }



    public static function listCardsByCustomerId($customer_id){
        $zoho_card = new Cards();
        $card_resp = $zoho_card->list($customer_id);
        if($card_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'data'=> $card_resp[0]['cards']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $card_resp[0]['message']
            ];
        }
    }

    public static function getCardDetails($customer_id,$card_id){
        $zoho_card = new Cards();
        $card_resp = $zoho_card->retrieve($customer_id,$card_id);
        if($card_resp[0]['code'] == '0') {
            $card_details = $card_resp[0]['card'];
            return [
                'status' => 'success',
                'data'=> $card_details
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $card_resp[0]['message']
            ];
        }
    }

    public static function deleteCard($customer_id,$card_id){
        $zoho_card = new Cards();
        $card_resp = $zoho_card->delete($customer_id,$card_id);
        if($card_resp[0]['code'] == '0') {
            return [
                'status' => 'success',
                'message'=> $card_resp[0]['message']
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $card_resp[0]['message']
            ];
        }
    }




    public static function getPlanDetails($plan_id){
        $zoho_plan = new Plans();
        $plan_resp = $zoho_plan->retrieve($plan_id);
        if($plan_resp[0]['code'] == '0') {
            $plan_details = $plan_resp[0]['plan'];
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

    public static function getMonthlyPlanCode($product_id){
       return 'monthly_plan_'.$product_id;
    }

    public static function getYearlyPlanCode($product_id){
        return 'yearly_plan_'.$product_id;
    }

    public static function getCustomerId($user_id){
        $customer_id = false;

        $customer_data = UserExtra::where('user_id',$user_id)->where('key_name','ZOHO_CUSTOMER_DATA')->first();
        if($customer_data){
            $customer_data = json_decode($customer_data->key_value, true);
            $customer_id = $customer_data['customer_id'];
        }


        return $customer_id;
    }

    public static function getCustomerData($user_id){
        $customer_id = false;
        $customer_data = null;
        $customer_data = UserExtra::where('user_id',$user_id)->where('key_name','ZOHO_CUSTOMER_DATA')->first();
        if($customer_data){
            $customer_data = json_decode($customer_data->key_value, true);
            $customer_id = $customer_data['customer_id'];
        }
        $zoho_customer = new Customers();
        $customer_resp = $zoho_customer->retrieve($customer_id);
        if($customer_resp[0]['code'] == '0') {
            $customer_data = $customer_resp[0]['customer'];
        }

        return $customer_data;
    }

    public static function deleteTemplate($package){
        $zoho_product = new Products();
        $zoho_product_resp = $zoho_product->delete($package->zoho_product_id);
        if($zoho_product_resp[0]['code'] == 0){
            return [
                'status' => 'success'
            ];
        }else{
            return [
                'status' => 'error',
                'message'=> $zoho_product_resp[0]['message']
            ];
        }
    }









    public static function test(){

//        $headers = [
//            "Content-Type"=> "application/json;charset=UTF-8",
//            'Authorization' => 'Zoho-oauthtoken 1000.3a75f14867f6bb472796b8750f15e77a.32806b6c8dc3f0d854c7e16343a043da',
//            'X-com-zoho-subscriptions-organizationid' => '748274151'
//        ];
        // Create a client with a base URI
//        $client = new Client();

        // Send a request to https://foo.com/api/test
//         $response = $client->get('https://subscriptions.zoho.com/api/v1/customers', [
//             'headers' => $headers
//         ]  );
//        echo "<pre>";
//        print_r($response);


        $url = 'https://subscriptions.zoho.com/api/v1/customers';
        $zoho = new ZohoSubscription();
        die('here...');
        $resp = $zoho->request('get',$url);
        echo "<pre>";
        print_r($resp);
   //     die;


//        $additionalHeaders = array(
//            'Authorization:Zoho-authtoken 1000.bd2acf54f481a0474150752a66345976.2f3137774fc84de88117496c6957866b', // use your auth toke here
//            'X-com-zoho-subscriptions-organizationid: 748274151'  // use your organization id here
//        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-com-zoho-subscriptions-organizationid:748274151',
        'Authorization:Zoho-oauthtoken 1000.d06361f0254e63f5c26bf5c538c72ea3.f5369addd768fc15e66f7ccfa5aee5c2'  // use your organization id here
        ));
        curl_setopt($ch, CURLOPT_HEADER, 1);
       // curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->secretKey);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
       // curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadName);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($ch);
        curl_close($ch);
        echo "<pre>";
        print_r($return);
        die;

    }




}
