<?php

namespace App\Observers;


use App\VehicleDocument;

class VehicleDocumentObserver
{

    public function saving(VehicleDocument $file)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $file->company_id = company()->id;
        }
    }

}
