<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsSpace extends BaseModel
{
    protected $table = 'ads_space';
    protected $appends = ['image_url','global_image_url'];

    public function getImageUrlAttribute()
    {
        if (is_null($this->photo)) {
            $global = GlobalSetting::first();
            return $global->image_url;
        }
        return asset_url('ads/' . $this->photo);
    }

    public function getGlobalImageUrlAttribute()
    {
        $global = GlobalSetting::first();
        return $global->image_url;
    }

    public function adsCategory()
    {
        return $this->belongsTo(AdsCategory::class,'category_id');
    }

}


