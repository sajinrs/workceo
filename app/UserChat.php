<?php

namespace App;

use App\Observers\NewChatObserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Trebol\Entrust\Entrust;
use Trebol\Entrust\Traits\EntrustUserTrait;

class UserChat extends Authenticatable
{

    protected $table = 'users_chat';
    protected $appends = ['attachment_url'];

    public $timestamps = true;

    protected $guarded = ["id"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $dates = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::observe(NewChatObserver::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from')->withoutGlobalScopes(['active']);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to')->withoutGlobalScopes(['active']);
    }

    public static function chatDetail($id,$userID)
    {
        return UserChat::where(function($q) use ($id,$userID) {
            $q->Where('user_id', $id)->Where('user_one', $userID)
                ->orwhere(function($q) use ($id,$userID) {
                    $q->Where('user_one', $id)
                        ->Where('user_id', $userID);
                });
        })

            ->orderBy('created_at', 'asc')->get();
    }

    public static function chatUnSeen($id,$userID)
    {
        return UserChat::where('user_one', $id)
                        ->where('user_id', $userID)
                        ->where('message_seen', 'no')
                        ->count();
    }

    public static function messageSeenUpdate($loginUser,$toUser,$updateData)
    {
        return UserChat::where('from', $toUser)->where('to', $loginUser)->update($updateData);
    }

    public function getAttachmentUrlAttribute()
    {
        if (is_null($this->attachment)) {
            $global = GlobalSetting::first();
            return $global->image_url;
        }
        return asset_url('attachment/' . $this->attachment);
    }

    public static function chatMedia($id,$userID)
    {
        return UserChat::where(function($q) use ($id,$userID) {
            $q->Where('user_id', $id)->Where('user_one', $userID) ->whereNotNull('attachment')
                ->orwhere(function($q) use ($id,$userID) {
                    $q->Where('user_one', $id)
                        ->Where('user_id', $userID)
                        ->whereNotNull('attachment');
                });
        })

            ->orderBy('created_at', 'asc')->get();
    }

}
