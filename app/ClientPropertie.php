<?php

namespace App;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\ClientPropertieObserver;

class ClientPropertie extends BaseModel
{
    protected static function boot()
    {
        parent::boot();

        static::observe(ClientPropertieObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
