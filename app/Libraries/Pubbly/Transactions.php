<?php


namespace App\Libraries\Pubbly;


class Transactions extends Pubbly
{
    public function listAllTransactionsByCustomerId($customer_id){
        $url = $this->apiBase.'transactions/'.$customer_id;

        return $this->request('get', $url);
    }

}