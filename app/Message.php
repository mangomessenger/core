<?php

namespace App;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id', 'from_id', 'reply_to_msg_id', 'message', 'is_read'
    ];

    public $incrementing = false;

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Snowflake::id());
        });
    }
}
