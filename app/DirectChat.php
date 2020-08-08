<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DirectChat extends Model
{
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
}
