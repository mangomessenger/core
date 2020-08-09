<?php

namespace App\Models;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $incrementing = false;

    /**
     * Get the chat's members
     */
    public function members()
    {
        return $this->hasMany('App\Models\ChatMember', 'chat_id', 'id');
    }

    /**
     * Get the chat's messages
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'chat_id', 'id');
    }

    /**
     * Users that are in chat
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_chat', 'chat_id', 'user_id');
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

    public function addMembers($members)
    {
        $userIds = is_array($members) ? $members : (array) func_get_args();

        collect($userIds)->each(function ($userId) {
            $this->members()->firstOrCreate([
                'user_id' => $userId,
                'chat_id' => $this->id,
            ]);
        });
    }
}
