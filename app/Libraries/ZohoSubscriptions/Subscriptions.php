<?php


namespace App\Libraries\ZohoSubscriptions;


class Subscriptions extends ZohoSubscription
{
    public function create($data){
        $url = $this->apiBase.'subscriptions';

        return $this->request('post', $url, json_encode($data));
    }

    public function retrieve($id){
        $url = $this->apiBase.'subscriptions/'.$id;
        return $this->request('get', $url);
    }

    public function update($id,$data){
        $url = $this->apiBase.'subscriptions/'.$id;

        return $this->request('put', $url, json_encode($data));
    }

    public function cancel($id){
        $url = $this->apiBase.'subscriptions/'.$id.'/cancel?cancel_at_end=false';

        return $this->request('post', $url);
    }

    public function delete($id){
        $url = $this->apiBase.'subscriptions/'.$id;

        return $this->request('delete', $url);
    }

    public function listByCustomer($id,$status = 'All'){
        $url = $this->apiBase.'subscriptions?customer_id='.$id.'&filter_by=SubscriptionStatus.'.$status;

        return $this->request('get', $url);
    }
}