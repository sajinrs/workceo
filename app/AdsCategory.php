<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsCategory extends BaseModel
{
    protected $table = 'ads_category';

    public function adsSpace()
    {
        return $this->hasMany(AdsSpace::class, 'category_id');
    }
}
