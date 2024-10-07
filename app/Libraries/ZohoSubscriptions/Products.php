<?php


namespace App\Libraries\ZohoSubscriptions;


class Products extends ZohoSubscription
{
    public function create($data){
        $url = $this->apiBase.'products';

        return $this->request('post', $url, json_encode($data));
    }

    public function update($product_id,$data){
        $url = $this->apiBase.'products/'.$product_id;

        return $this->request('put', $url, json_encode($data));
    }

    public function markAsActive($product_id){
        $url = $this->apiBase.'products/'.$product_id.'/markasactive';

        return $this->request('post', $url);
    }

    public function markAsInactive($product_id){
        $url = $this->apiBase.'products/'.$product_id.'/markasinactive';

        return $this->request('post', $url);
    }

    public function delete($product_id){
        $url = $this->apiBase.'products/'.$product_id;

        return $this->request('delete', $url);
    }
}