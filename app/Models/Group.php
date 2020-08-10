<?php

namespace App\Models;

use App\Models\BaseChat;

class Group extends BaseChat
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'creator_id', 'photo_url', 'members_count', 'description',
    ];

    /**
     * Get the chat's creator
     */
    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }

    /**
     * @param $value
     * @return bool
     */
    public function getVerifiedAttribute($value)
    {
        return $value === "";
    }
}
