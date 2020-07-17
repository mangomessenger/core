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
        'phone_number', 'country_code', 'phone_code_hash', 'fingerprint', 'timeout', 'is_new',
    ];
}
