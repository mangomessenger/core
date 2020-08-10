<?php

namespace App\Models;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Adding new members to chat
     *
     * @param $members
     */
    public function addMembers($members)
    {
        $userIds = is_array($members) ? $members : (array)func_get_args();

        collect($userIds)->each(function ($userId) {
            $this->members()->firstOrCreate([
                'user_id' => $userId,
                'chat_id' => $this->id,
            ]);
        });
    }

    /**
     * Remove members from chat.
     *
     * @param $members
     * @return void
     */
    public function removeMembers($members)
    {
        $userIds = is_array($members) ? $members : (array)func_get_args();

        $this->members()->where('chat_id', $this->id)->whereIn('user_id', $userIds)->delete();
    }

    /**
     * Returns threads between given user ids.
     *
     * @param Builder $query
     * @param array $users
     * @return Builder
     */
    public function scopeBetween(Builder $query, array $users)
    {
        // Trying to retrieve already created chat
        return $query->whereHas('members', function (Builder $q) use ($users) {
            $q->whereIn('user_id', $users)
                ->select(DB::raw('count(chat_members.chat_id) AS count, chat_members.chat_id'))
                ->groupBy('chat_members.chat_id')
                ->having('count', count($users));
        });
    }
}
