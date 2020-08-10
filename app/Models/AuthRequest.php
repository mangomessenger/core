<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthRequest extends Model
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
        'phone_number', 'country_code', 'phone_code_hash', 'fingerprint', 'is_new',
    ];
}
