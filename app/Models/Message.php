<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id', 'chat_type', 'from_id', 'reply_to_msg_id', 'message', 'is_read'
    ];

    /**
     * Get the owning chat model.
     */
    public function chat()
    {
        return $this->morphTo();
    }
}
