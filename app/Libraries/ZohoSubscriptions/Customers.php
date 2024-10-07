<?php


namespace App\Libraries\ZohoSubscriptions;


class Customers extends ZohoSubscription
{
    public function create($data){
        $url = $this->apiBase.'customers';
        return $this->request('post', $url, json_encode($data));
    }

    public function retrieve($customer_id){
        $url = $this->apiBase.'customers/'.$customer_id;
        return $this->request('get', $url);
    }

    public function update($customer_id,$data){
        $url = $this->apiBase.'customers/'.$customer_id;
        return $this->request('put', $url, json_encode($data));
    }

    public function delete($customer_id){
        $url = $this->apiBase.'customers/'.$customer_id;
        return $this->request('delete', $url);
    }

    public function transactions($customer_id){
        $url = $this->apiBase.'transactions?filter_by=TransactionType.All&customer_id='.$customer_id;
        return $this->request('get', $url);
    }

    public function markAsInactive($id){
        $url = $this->apiBase.'customers/'.$id.'/markasinactive';
        return $this->request('post', $url);
    }

}