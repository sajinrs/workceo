<?php


namespace App\Libraries\Pubbly;


class Products extends Pubbly
{
    public function createProduct($data){
        $url = $this->apiBase.'product/create';

        return $this->request('post', $url, $data);
    }

    public function updateProduct($plan_id,$data){
        $url = $this->apiBase.'product/update/'.$plan_id;

        return $this->request('put', $url, $data);
    }
}