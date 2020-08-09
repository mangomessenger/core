<?php

namespace App;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;

class DirectChat extends Model
{

    public $incrementing = false;

    /**
     * Get the chat's members
     */
    public function members()
    {
        return $this->hasMany('App\ChatMember', 'chat_id', 'id');
    }

    /**
     * Get the chat's messages
     */
    public function messages()
    {
        return $this->hasMany('App\Message', 'chat_id', 'id');
    }

    /**
     * Users that are in chat
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_chat', 'chat_id', 'user_id');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Snowflake::id());
        });
    }
}
