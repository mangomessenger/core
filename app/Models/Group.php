<?php

namespace App\Models;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;

class Group extends Chat
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'creator_id', 'photo_url', 'members_count',
    ];
}
