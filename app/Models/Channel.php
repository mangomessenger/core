<?php

namespace App\Models;

class Channel extends BaseChat
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'creator_id', 'tag', 'photo_url', 'verified', 'members_count', 'description',
    ];

    /**
     * Get the chat's creator
     */
    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }
}
