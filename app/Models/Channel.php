<?php

namespace App\Models;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;

class Channel extends Chat
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'creator_id', 'tag', 'photo_url', 'verified', 'members_count', 'description',
    ];
}
