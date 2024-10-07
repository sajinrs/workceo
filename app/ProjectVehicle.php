<?php

namespace App;

use App\Observers\ProjectVehicleObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Notifications\Notifiable;

class ProjectVehicle extends BaseModel
{
    //use Notifiable;

    protected static function boot()
    {
        parent::boot();

        static::observe(ProjectVehicleObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    
}
