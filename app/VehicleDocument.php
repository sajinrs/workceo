<?php

namespace App;

use App\Observers\VehicleDocumentObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VehicleDocument extends BaseModel
{

    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (asset_url_local_s3('vehicle-documents/'.$this->vehicle_id.'/'.$this->hashname));
    }

    protected static function boot()
    {
        parent::boot();

        static::observe(VehicleDocumentObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
