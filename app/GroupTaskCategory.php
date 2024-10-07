<?php

namespace App;

use App\Observers\GroupTaskCategoryObserver;
use App\Scopes\CompanyScope;

class GroupTaskCategory extends BaseModel
{
    protected $table = 'group_task_categories';

    protected static function boot()
    {
        parent::boot();

        static::observe(GroupTaskCategoryObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
