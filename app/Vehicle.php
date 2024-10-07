<?php

namespace App;

use App\Observers\VehicleObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends BaseModel
{
    protected $table = 'vehicles';
    protected $fillable = ['vehicle_name'];
    protected $appends = ['image_url'];

    protected static function boot()
    {
        parent::boot();

        static::observe(VehicleObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function getImageUrlAttribute()
    {
        if (is_null($this->photo)) {
            $global = GlobalSetting::first();
            return $global->image_url;
        }
        return asset_url('vehicle/' . $this->photo);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
