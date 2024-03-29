<?php

namespace App\Models;

use App\Contracts\Chat;
use App\Facades\SnowflakeFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseChat extends Model implements Chat
{
    public $incrementing = false;

    /**
     * Get the chat's members
     */
    public function members()
    {
        return $this->belongsToMany('App\Models\User', 'chat_members', 'chat_id', 'user_id');
    }

    /**
     * Get the chat's messages
     */
    public function messages()
    {
        return $this->morphMany('App\Models\Message', 'chat');
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
            $model->setAttribute($model->getKeyName(), SnowflakeFacade::id());
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
            $this->members()->attach($userId);

            // members_count++;
            if(in_array('members_count', $this->fillable)) $this->increment('members_count');
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

        collect($userIds)->each(function ($userId) {
            $this->members()->detach($userId);

            // members_count--;
            if(in_array('members_count', $this->fillable)) $this->decrement('members_count');
        });
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

    /**
     * Checks to see if a user is a current member of the chat.
     *
     * @param int $userId
     * @return bool
     */
    public function hasMember(int $userId): bool
    {
        return $this->members()->where('user_id', '=', $userId)->exists();
    }
}
