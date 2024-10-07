<?php
namespace App\Libraries\Pubbly;


class Customers extends Pubbly
{

    public function createCustomer($data){
        $url = $this->apiBase.'customer';
        return $this->request('post', $url, $data);
    }

    public function getSingleCustomer($customer_id){
        $url = $this->apiBase.'customer/'.$customer_id;
        return $this->request('get', $url);
    }

    public function updateCustomer($customer_id,$data){
        $url = $this->apiBase.'customer/'.$customer_id;
        return $this->request('put', $url, $data);
    }



}