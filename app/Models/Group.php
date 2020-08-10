<?php

namespace App\Models;

use App\Abstracts\AbstractChat;

class Group extends AbstractChat
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
}
