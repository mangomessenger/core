<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatType extends Model
{

    const DIRECT_CHAT = 'direct';
    const CHANNEL_CHAT = 'channel';
    const GROUP_CHAT = 'group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function isDirect(): bool
    {
        return $this->name === self::DIRECT_CHAT;
    }

    public function isChannel(): bool
    {
        return $this->name === self::CHANNEL_CHAT;
    }

    public function isGroup(): bool
    {
        return $this->name === self::GROUP_CHAT;
    }

}
