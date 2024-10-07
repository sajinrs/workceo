<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageTipArticle extends BaseModel
{
    protected $table = 'pagetip_articles';
    protected $appends = ['image_url','global_image_url'];

    public function getImageUrlAttribute()
    {
        if (is_null($this->image)) {
            $global = GlobalSetting::first();
            return $global->image_url;
        }
        return asset_url('faq-files/' . $this->image);
    }

    public function getGlobalImageUrlAttribute()
    {
        $global = GlobalSetting::first();
        return $global->image_url;
    }

    public function faq_category()
    {
        return $this->belongsTo(PageTipModule::class);
    }

}


