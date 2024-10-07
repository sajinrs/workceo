<?php


namespace App\Libraries\ZohoSubscriptions;


class Cards extends ZohoSubscription
{
    public function retrieve($customer_id, $card_id){
        $url = $this->apiBase.'customers/'.$customer_id.'/cards/'.$card_id;

        return $this->request('get', $url);
    }

    public function delete($customer_id, $card_id){
        $url = $this->apiBase.'customers/'.$customer_id.'/cards/'.$card_id;

        return $this->request('delete', $url);
    }

    public function list($customer_id){
        $url = $this->apiBase.'customers/'.$customer_id.'/cards';

        return $this->request('get', $url);
    }
}