<?php


namespace App\Libraries\ZohoSubscriptions;


class Invoices extends ZohoSubscription
{
    public function list($status = 'All'){
        $url = $this->apiBase.'invoices?filter_by=Status.'.$status;

        return $this->request('get', $url);
    }

    public function listByCustomer($id,$status = 'All'){
        $url = $this->apiBase.'invoices?customer_id='.$id.'&filter_by=Status.'.$status;

        return $this->request('get', $url);
    }

    public function listBySubscription ($id,$status = 'All'){
        $url = $this->apiBase.'invoices?subscription_id='.$id.'&filter_by=Status.'.$status;

        return $this->request('get', $url);
    }

    public function email($id, $data){
        $url = $this->apiBase.'invoices/'.$id.'/email';

        return $this->request('post', $url, json_encode($data));
    }
}