<?php


namespace App\Libraries\Pubbly;


class Plans extends Pubbly
{
    public function createPlan($data){
        $url = $this->apiBase.'plan/create';

        return $this->request('post', $url, $data);
    }

    public function updatePlan($plan_id,$data){
        $url = $this->apiBase.'plan/update/'.$plan_id;

        return $this->request('put', $url, $data);
    }

    public function getSinglePlan($plan_id){
        $url = $this->apiBase.'plan/'.$plan_id;

        return $this->request('get', $url);
    }
}