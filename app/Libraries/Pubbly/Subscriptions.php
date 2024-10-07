<?php


namespace App\Libraries\Pubbly;


class Subscriptions extends Pubbly
{
    public function cancelSubscription($subscription_id, $data){
        $url = $this->apiBase.'subscription/'.$subscription_id.'/cancel';

        return $this->request('post', $url, $data);
    }

    public function getSingleSubscription($subscription_id){
        $url = $this->apiBase.'subscription/'.$subscription_id;

        return $this->request('get', $url);
    }

    public function createSubscriptionForCustomer($customer_id, $data){
        $url = $this->apiBase.'subscription/'.$customer_id;

        return $this->request('post', $url, $data);
    }
}