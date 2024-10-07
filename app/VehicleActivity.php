<?php

namespace App;

use App\Observers\VehicleActivityObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VehicleActivity extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(VehicleActivityObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    
}
