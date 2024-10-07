<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnBoarding extends BaseModel
{
    protected $table = 'onboardings';
    protected $appends = ['image_url','global_image_url'];

    public function getImageUrlAttribute()
    {
        if (is_null($this->popup_image)) {
            $global = GlobalSetting::first();
            return $global->image_url;
        }
        return asset_url('boarding/' . $this->popup_image);
    }

    public function getGlobalImageUrlAttribute()
    {
        $global = GlobalSetting::first();
        return $global->image_url;
    }

    
}


