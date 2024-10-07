<?php

namespace App\Observers;

use App\ClientPropertie;

class ClientPropertieObserver
{
    public function saving(ClientPropertie $client_property)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $client_property->company_id = company()->id;
        }
    }
}
