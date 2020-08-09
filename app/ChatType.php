<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatType extends Model
{

    const DIRECT_CHAT = 'direct';

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
}
