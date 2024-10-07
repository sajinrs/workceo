<?php

namespace App;

use App\Observers\CompanyObserver;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Invoice;
use Stripe\Invoice as StripeInvoice;

class Company extends BaseModel
{
    protected $table = 'companies';
    protected $dates = ['trial_ends_at', 'licence_expire_on', 'created_at', 'updated_at', 'last_login'];
    protected $fillable = ['last_login', 'company_name', 'company_email', 'company_phone', 'website', 'address', 'currency_id', 'timezone', 'locale', 'date_format', 'time_format', 'week_start', 'longitude', 'latitude'];
    protected $appends = ['logo_url','global_logo_url' ,'logo_gif_front_url', 'package_banner_monthly_url', 'package_banner_annually_url', 'billing_footer_image_url', 'checkout_left_image_url'];
    use Notifiable, Billable;

    public function findInvoice($id)
    {
        try {
            $stripeInvoice = StripeInvoice::retrieve(
                $id,
                $this->getStripeKey()
            );

            $stripeInvoice->lines = StripeInvoice::retrieve($id, $this->getStripeKey())
                ->lines
                ->all(['limit' => 1000]);

            $stripeInvoice->date = $stripeInvoice->created;
            return new Invoice($this, $stripeInvoice);

        } catch (\Exception $e) {
            //
        }


    }

    public static function boot()
    {
        parent::boot();
        static::observe(CompanyObserver::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class)
            ->join('employee_details', 'employee_details.user_id', 'users.id');
    }


    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            return asset('front/company_default.png');
        }
        return asset_url('app-logo/' . $this->logo);

        
    }

    
    public function getGlobalLogoUrlAttribute()
    {
       $global = GlobalSetting::first();
       return $global->logo_url;
    }

    public function getLogoGifFrontUrlAttribute()
    {
       $global = GlobalSetting::first();
       return asset_url('app-logo/' . $global->logo_gif_front);
    }

    public function getPackageBannerMonthlyUrlAttribute()
    {
        $global = GlobalSetting::first();
        if (is_null($global->package_banner_monthly)) {
            
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $global->package_banner_monthly);
    }

    public function getPackageBannerAnnuallyUrlAttribute()
    {
        $global = GlobalSetting::first();
        if (is_null($global->package_banner_annually)) {
            
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $global->package_banner_annually);
    }

    public function getBillingFooterImageUrlAttribute()
    {
        $global = GlobalSetting::first();
        if (is_null($global->billing_footer_image)) {
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $global->billing_footer_image);
    }

    public function getCheckoutLeftImageUrlAttribute()
    {
        $global = GlobalSetting::first();
        if (is_null($global->checkout_left_image)) {
            return asset('img/default-package.jpg');
        }
        return asset_url('package/' . $global->checkout_left_image);
    }

}
