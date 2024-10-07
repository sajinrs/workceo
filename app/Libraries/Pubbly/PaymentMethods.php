<?php


namespace App\Libraries\Pubbly;


class PaymentMethods extends Pubbly
{
    public function listAllPaymentMethods($customer_id){
        $url = $this->apiBase.'paymentmethods/'.$customer_id;

        return $this->request('get', $url);
    }

    public function createPaymentMethod($customer_id, $data){
        $url = $this->apiBase.'paymentmethod/'.$customer_id;

        return $this->request('post', $url, $data);
    }

}