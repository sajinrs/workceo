<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageTipModule extends BaseModel
{
    protected $table = 'pagetip_modules';

    public function articles()
    {
        return $this->hasMany(PageTipArticle::class, 'module_id', 'id')->orderBy('sort_order');
    }
}
