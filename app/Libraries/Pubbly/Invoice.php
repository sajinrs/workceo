<?php


namespace App\Libraries\Pubbly;


class Invoice extends Pubbly
{
    public function listAllInvoicesByCustomerId($customer_id){
        $url = $this->apiBase.'invoices/'.$customer_id;

        return $this->request('get', $url);
    }

}