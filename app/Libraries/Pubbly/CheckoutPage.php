<?php


namespace App\Libraries\Pubbly;


class CheckoutPage extends Pubbly
{
    public function getCheckoutPageByProductId($productId){
        $url = $this->apiBase.'checkoutpage/'.$productId;

        return $this->request('get', $url);
    }

    public function verifyHostedPage($data){
        $url = $this->apiBase.'verifyhosted';

        return $this->request('post', $url, $data);
    }
}