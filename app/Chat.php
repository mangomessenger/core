<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public const TYPES = [
        'TYPE_USER',
        'TYPE_SELF',
//        'TYPE_CHAT'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'user1_id', 'user2_id',
    ];
}
