<?php

namespace App\Models;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * Primary id incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'bio', 'phone_number', 'country_code', 'photo_url', 'verified', 'last_time_online',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The user's direct chats
     */
    public function directChats()
    {
        return $this->belongsToMany('App\Models\DirectChat', 'chat_members', 'user_id', 'chat_id');
    }

    /**
     * The user's channels
     */
    public function channels()
    {
        return $this->belongsToMany('App\Models\Channel', 'chat_members', 'user_id', 'chat_id');
    }

    /**
     * The user's groups
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', 'chat_members', 'user_id', 'chat_id');
    }


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
