<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends BaseModel
{
    protected $table = 'global_settings';
    protected $default = ['id'];
    protected $appends = ['login_background_url', 'signup_background_url', 'logo_url','logo_front_url', 'logo_gif_front_url', 'show_public_message', 'package_banner_monthly_url', 'package_banner_annually_url', 'billing_footer_image_url', 'checkout_left_image_url'];

    public function getLoginBackgroundUrlAttribute()
    {
        if (is_null($this->login_background)) {
            return asset('img/login-bg.jpg');
        }

        return asset_url('user-uploads/' . $this->login_background);
    }

    public function getSignupBackgroundUrlAttribute()
    {
        if (is_null($this->signup_background)) {
            return asset('img/login-bg.jpg');
        }

        return asset_url('user-uploads/' . $this->signup_background);
    }

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            return asset('img/worksuite-logo.png');
        }

        return asset_url('app-logo/' . $this->logo);
    }

    public function getLogoFrontUrlAttribute()
    {
        if (is_null($this->logo_front)) {
            if (is_null($this->logo)) {
                return asset('front/img/worksuite-logo.png');
            }
            return $this->logo_url;
        }
        return asset_url('app-logo/'.$this->logo_front);
    }

    public function getLogoGifFrontUrlAttribute()
    {
        if (is_null($this->logo_gif_front)) {
            if (is_null($this->logo)) {
                return asset('front/img/worksuite-logo.png');
            }
            return $this->logo_url;
        }
        return asset_url('app-logo/'.$this->logo_gif_front);
    }

    public function currency()
    {
        return $this->belongsTo(GlobalCurrency::class, 'currency_id')->withTrashed();
    }

    public function getShowPublicMessageAttribute()
    {
        if (strpos(request()->url(), request()->getHost().'/public') !== false){
            return true;
        }
        return false;
    }

    public function getPackageBannerMonthlyUrlAttribute()
    {
        
        if (is_null($this->package_banner_monthly)) {
            
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $this->package_banner_monthly);
    }

    public function getPackageBannerAnnuallyUrlAttribute()
    {
        
        if (is_null($this->package_banner_annually)) {
            
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $this->package_banner_annually);
    }

    public function getBillingFooterImageUrlAttribute()
    {
        if (is_null($this->billing_footer_image)) {
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $this->billing_footer_image);
    }

    public function getCheckoutLeftImageUrlAttribute()
    {
        if (is_null($this->checkout_left_image)) {
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $this->checkout_left_image);
    }

    
    
}
