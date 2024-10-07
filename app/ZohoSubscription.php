<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZohoSubscription extends BaseModel
{
    protected $table = 'zoho_subscriptions';

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }
}
