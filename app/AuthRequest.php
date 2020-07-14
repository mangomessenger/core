<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'country_code', 'code', 'timeout', 'is_new',
    ];
}
