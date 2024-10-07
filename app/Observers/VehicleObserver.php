<?php

namespace App\Observers;

use App\Vehicle;

class VehicleObserver
{

    public function saving(Vehicle $vehicle)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $vehicle->company_id = company()->id;
        }
    }

}
