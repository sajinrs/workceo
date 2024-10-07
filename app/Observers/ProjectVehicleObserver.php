<?php

namespace App\Observers;


//use App\Notifications\NewProjectMember;
use App\ProjectVehicle;

class ProjectVehicleObserver
{

    public function saving(ProjectVehicle $vehicle)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $vehicle->company_id = company()->id;
        }
    }

    /* public function created(ProjectMember $member)
    {
        if (!app()->runningInConsole() ) {
            $member->user->notify(new NewProjectMember($member));
        }
    } */
}
