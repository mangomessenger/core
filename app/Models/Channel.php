<?php

namespace App\Models;

use App\Abstracts\AbstractChat;

class Channel extends AbstractChat
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
