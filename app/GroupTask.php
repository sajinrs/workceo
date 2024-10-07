<?php

namespace App;

//use App\Observers\ProductObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupTask extends BaseModel
{
    protected $table = 'group_tasks';

    
    protected static function boot()
    {
        parent::boot();

        //static::observe(ProductObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    
}
