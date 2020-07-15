<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'access_token',
    ];


    /**
     * Get the user record associated with the session.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
