<?php


namespace App\Libraries\Pubbly;


class ClientPortal extends Pubbly
{
    public function createClientPortal($data){
        $url = $this->apiBase.'portal_sessions';

        return $this->request('post', $url, $data);
    }

}