<?php


namespace App\Libraries\ZohoSubscriptions;


class Plans extends ZohoSubscription
{
    public function create($data){
        $url = $this->apiBase.'plans';

        return $this->request('post', $url, json_encode($data));
    }

    public function update($id,$data){
        $url = $this->apiBase.'plans/'.$id;

        return $this->request('put', $url, json_encode($data));
    }

    public function markAsActive($id){
        $url = $this->apiBase.'plans/'.$id.'/markasactive';

        return $this->request('post', $url);
    }

    public function markAsInactive($id){
        $url = $this->apiBase.'plans/'.$id.'/markasinactive';

        return $this->request('post', $url);
    }

    public function delete($id){
        $url = $this->apiBase.'plans/'.$id;

        return $this->request('delete', $url);
    }

    public function retrieve($id){
        $url = $this->apiBase.'plans/'.$id;

        return $this->request('get', $url);
    }

}