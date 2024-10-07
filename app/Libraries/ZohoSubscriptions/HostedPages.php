<?php


namespace App\Libraries\ZohoSubscriptions;


class HostedPages extends ZohoSubscription
{
    public function create($data){
        $url = $this->apiBase.'hostedpages/newsubscription';

        return $this->request('post', $url, json_encode($data));
    }

    public function retrieve($id){
        $url = $this->apiBase.'hostedpages/'.$id;

        return $this->request('get', $url);
    }

}