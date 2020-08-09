<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'chat_id',
    ];
}
