<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PubblySubscription extends BaseModel
{
    protected $table = 'pubbly_subscriptions';

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }
}
